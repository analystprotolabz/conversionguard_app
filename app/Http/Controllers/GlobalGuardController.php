<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

//use ShopifyApp;

class GlobalGuardController extends Controller
{
    public function index()
    {
        return view("layouts.enablePixelPage");
    }

    public function globalGuardSnippetCreate()
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
                    "key" => "sections/globalguard_pixel.liquid",
                    "value" => "
                    <script>
  
                    // Specify a target node
                    const targetNode = document.documentElement;
                    
                    // Observers Configuration 
                    const config = { attributes: false, childList: true, subtree: true };
                    
                    // Generate a callback 
                    const callback = function(mutationList, observer) {
                    
                      let cg,l = null;
                    
                      cg = new CGAtlas({
                          dispatch: 'mutation',
                          event: {
                            name: 'observing',
                            data: mutationList
                          },
                          fn: function(event, observer, element) {
                            // Prediction function [Enterprise Pro]
                            return true;
                          },
                          after: function(element) {
                            // Prediction function [Enterprise Pro]
                            return true;
                          },
                          before: function(element, guard) {
                            // Prediction function [Enterprise Pro]
                            return true;
                          }
                        }, [], false);
                    };
                    
                    // Set Observer of the target document
                    const observer = new MutationObserver(callback);
                    
                    // Run Observe
                    observer.observe(targetNode, config);
                    
                  </script>
                    "
                ]
            ];
            $shop->api()->rest('PUT', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes/' . $active_theme->id . '/assets.json', $data_to_put);
            $this->snippetInclude($active_theme->id, $shop);

            $result = DB::table('pixel_status')
                        ->where('shop_domain',$shop->name)
                        ->update(['guard_pixel' => '1']);

        } else {
            $this->snippetInclude($active_theme->id, $shop);
        }
        return response()->json(['message' => "Page view script added successfully on Your Store", 'status' => 'success']);
    }

    public function snippetInclude($active_theme_id, $shop)
    {
        $html = $shop->api()->rest('GET', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes/' . $active_theme_id . '/assets.json', ['asset[key]' => 'layout/theme.liquid'])['body']['asset']['value'];
        $app_include = "\n{% comment %} //Theme Start {% endcomment %} \n {% capture snippet_content %} \n {% include 'globalguard_pixel.liquid' %} \n {% endcapture %} \n {% unless snippet_check contains 'Liquid Error' %} \n {{ snippet_content }} \n {% endunless %} \n {% comment %} //Theme End {% endcomment %}\n\n";

        if (strpos($html, '{% comment %} globalguard_pixel start {% endcomment %}') === false) {

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

    public function globalGuardSnippetDelete()
    {
        $shop = Auth::user();
        $result = DB::table('pixel_status')
                    ->where('shop_domain',$shop->name)
                    ->update(['guard_pixel' => '0']);
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
