<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

//use ShopifyApp;

class CheckoutController extends Controller
{
    public function index()
    {
        return view("layouts.enablePixelPage");
    }

    public function checkoutSnippetCreate()
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
                    "key" => "sections/checkout_pixel.liquid",
                    "value" => "
                    <script>
                    window._conversionguard.push({
                        dispatch: 'event',
                        event: {
                        name: 'product_view',
                        data: {
                                'sku': /** MPN */, // Required
                                'gtin': /** GTIN */, // Required
                                'image_url': 'YOUR_URL', // OPTIONAL
                                'value': FLOAT, // OPTIONAL
                                'categories': [ // OPTIONAL
                                    'Bedroom',
                                    'Mattresses',
                                    'King',
                                    'Memory Foam'
                                ]
                            }
                        }
                    }, false, false);
                    </script>"
                ]
            ];
            $shop->api()->rest('PUT', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes/' . $active_theme->id . '/assets.json', $data_to_put);
            $this->snippetInclude($active_theme->id, $shop);
        } else {
            $this->snippetInclude($active_theme->id, $shop);
        }
        return response()->json(['message' => "Script added successfully on Your Store", 'status' => 'success']);
    }

    public function snippetInclude($active_theme_id, $shop)
    {
        $html = $shop->api()->rest('GET', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes/' . $active_theme_id . '/assets.json', ['asset[key]' => 'layout/theme.liquid'])['body']['asset']['value'];
        $app_include = "\n{% comment %} //Theme Start {% endcomment %} \n {% capture snippet_content %} \n {% include 'checkout_pixel.liquid' %} \n {% endcapture %} \n {% unless snippet_check contains 'Liquid Error' %} \n {{ snippet_content }} \n {% endunless %} \n {% comment %} //Theme End {% endcomment %}\n\n";

        if (strpos($html, '{% comment %} checkout_pixel start {% endcomment %}') === false) {

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

    public function checkoutSnippetDelete()
    {
        $shop = Auth::user();
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
