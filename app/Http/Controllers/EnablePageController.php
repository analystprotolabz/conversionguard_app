<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

//use ShopifyApp;

class EnablePageController extends Controller
{
    public function index()
    {
        $shop = Auth::user();
        $credentialsData=DB::table('user_create_response')->select('credentials')->where('shop_domain','https://'.$shop->name.'/')->first();
        $credentialData = json_decode(json_encode($credentialsData), true);        
        $pix_stat = DB::table('pixel_status')
        ->select()
        ->where('shop_domain',$shop->name)
        ->get();
        $pix_stat = json_decode(json_encode($pix_stat), true);
        return view("layouts.enablePixelPage",compact(['credentialData','pix_stat']));
    }

    public function pageViewSnippetCreate()
    {
        $shop = Auth::user();
        $active_theme = "";
        $themes = $shop->api()->rest('GET', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes.json')['body']['themes'];

        foreach ($themes as $theme) {
            if ($theme->role == 'main') {
                $active_theme = $theme;
            }
        }
        $user_setting = DB::table('users')->where('name', $shop->name)->first();
        if ($user_setting <> "") {
            $data_to_put = [
                "asset" => [
                    "key" => "sections/pageview_pixel.liquid",
                    "value" => "
                    <script>
                        window._conversionguard.push({
                        dispatch: 'event',
                        event: {
                            name: 'page_view',
                            data: {
                            'value': 1.00,
                            }
                        }
                        }, false, false);
                    </script>"
                ]
            ];
            $shop->api()->rest('PUT', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes/' . $active_theme->id . '/assets.json', $data_to_put);
            $this->snippetInclude($active_theme->id, $shop);
                $result = DB::table('pixel_status')
                ->where('shop_domain',$shop->name)
                ->update(['page_view' => '1']);
        } else {
            $this->snippetInclude($active_theme->id, $shop);
        }
        return response()->json(['message' => "Page view script added successfully on Your Store", 'status' => 'success']);
    }

    public function snippetInclude($active_theme_id, $shop)
    {
        $html = $shop->api()->rest('GET', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes/' . $active_theme_id . '/assets.json', ['asset[key]' => 'layout/theme.liquid'])['body']['asset']['value'];
        $app_include = "\n{% comment %} //Theme Start {% endcomment %} \n {% capture snippet_content %} \n {% include 'pageview_pixel.liquid' %} \n {% endcapture %} \n {% unless snippet_check contains 'Liquid Error' %} \n {{ snippet_content }} \n {% endunless %} \n {% comment %} //Theme End {% endcomment %}\n\n";

        if (strpos($html, '{% comment %} pageview_pixel start {% endcomment %}') === false) {

            $pos = strpos($html, '</body>');
            $newhtml = substr($html, 0, $pos) . $app_include . substr($html, $pos);
            $toupdate = [
                "asset" => [
                    "key" => "layout/theme.liquid",
                    "value" => $newhtml
                ]
            ];
            $shop->api()->rest('PUT', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes/' . $active_theme_id . '/assets.json', $toupdate);
        } else if (strpos($html, '{% capture snippet_content %}') === true) {
            $pos = strpos($html, '{% capture snippet_content %}');
            $app_include1 = "{% comment %} DELETED {% endcomment %}";
            $newhtml = substr($html, 0, $pos) . $app_include1 . "</body> \n </html>";
            $toupdate = [
                "asset" => [
                    "key" => "layout/theme.liquid",
                    "value" => $newhtml
                ]
            ];
            $shop->api()->rest('PUT', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes/' . $active_theme_id . '/assets.json', $toupdate);
        }
    }

    public function pageViewSnippetDelete()
    {
        $shop = Auth::user();
        // status updated
        $result = DB::table('pixel_status')
                    ->where('shop_domain',$shop->name)
                    ->update(['page_view' => '0']);

        $active_theme = "";
        $themes = $shop->api()->rest('GET', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes.json')['body']['themes'];
        foreach ($themes as $theme) {
            if ($theme->role == 'main') {
                $active_theme = $theme;
            }
        }

        $html = $shop->api()->rest('GET', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes/' . $active_theme->id . '/assets.json', ['asset[key]' => 'layout/theme.liquid'])['body']['asset']['value'];
        $app_include = " ";
        if (strpos($html, '{% comment %} //Theme Start {% endcomment %}') == true) {
            $pos = strpos($html, '{% comment %} //Theme Start {% endcomment %}');
            $newhtml = substr($html, 0, $pos) . $app_include . "</body>\n</html>";
            $toupdate = [
                "asset" => [
                    "key" => "layout/theme.liquid",
                    "value" => $newhtml
                ]
            ];
            $shop->api()->rest('PUT', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes/' . $active_theme->id . '/assets.json', $toupdate);
            return response()->json(['message' => "Updated", 'status' => 'success']);
        }
        return response()->json(['message' => "Not Updated", 'status' => 'error']);
    }
}
