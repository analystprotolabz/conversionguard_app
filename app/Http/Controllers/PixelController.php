<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Validator;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PixelController extends Controller
{
    public function index()
    {
        return view("welcome");
    }

    public function userCreate(Request $request)
    {
        $shop = Auth::user();
        $shopData = $shop->api()->rest('GET', '/admin/api/2022-10/shop.json');
        $shopData = $shopData['body']['container']['shop'];
        $shop_id = $shopData['id'];
        $domain = $request->input('domain');
        $first_name  = $request->input('first_name');
        $email = $request->input('email');
        $phone = $request->input('phone');

        $validator = Validator::make($request->all(), [
            'domain' => 'required',
            'first_name' =>  'required',
            'email'  => 'required|email|unique:ownerdata',
            'phone'  => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'error_message' => $validator->errors()]);
        }

        if ($validator->passes()) {

            $ownerData = [
                'first_name' => $first_name,
                'domain'  => $domain,
                'email'  => $email,
                'phone'  => $phone,
                'channel_id' => $shop_id,
            ];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://dashboard.conversionguard.com/api/v1/third-party/account/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                'domain'  => $domain,
                'full_name' => $first_name,
                'email'  => $email,
                'phone'  => $phone,
                'channel_id' => $shop_id,
            )
            ));
            $response = curl_exec($curl);
            curl_close($curl);

            $userResponses = json_decode($response, true);
            $userResponse = array('data'=>$userResponses);
            Log::info("userResponse : " . json_encode($userResponses) );

            $site_key = "cdab4f94-2e52-4946-af6b-6cf98c3f8847";

            $status = $userResponse['data']['status'];
            $message = $userResponse['data']['message'];
            $organization_id = $userResponse['data']['data']['organization_id'];
            $site_key = $userResponse['data']['data']['site_key'];
            $credentials = $userResponse['data']['data']['credentials'];
            $created_at = date('y-m-d h:i:s', time());
            $updated_at = date('y-m-d h:i:s', time());

            $result = DB::table('user_create_response')->updateOrInsert(
                ['shop_domain' => $domain],
                ['shop_domain' => $domain, 'status' => $status, 'message' => $message, 'organization_id' => $organization_id ,
                 'site_key' => $site_key , 'credentials' => $credentials , 'created_at' => $created_at , 'updated_at' => $updated_at ]
            );

            $result  =  DB::table('ownerdata')->upsert(
                $ownerData,
                ['email', 'channel_id'],
                ['domain', 'first_name', 'phone']
            );
            if ($result) {
                $this->snippetCreate($site_key);
                return response()->json(['success_message' => "Data saved successfully.", 'status' => 1, "shop_id" => $shop_id]);
            } else {
                return response()->json(['error_message' => "Something went wrong, Please try again.", 'status' => 0]);
            }
        } else {
            return response()->json(['error_message' => $validator->errors()->first(), 'status' => 2]);
        }
    }

    public function snippetCreate($site_key)
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
                    "key" => "sections/conversionguard.liquid",
                    "value" => "<script>window._conversionguard = window._conversionguard || [];
                    </script><script src='https://cdn.conversionguard.com/js/$site_key'></script>"
                ]
            ];
            $shop->api()->rest('PUT', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes/' . $active_theme->id . '/assets.json', $data_to_put);
            $this->snippetInclude($active_theme->id, $shop);
        } else {
            $this->snippetInclude($active_theme->id, $shop);
        }
        return response()->json(['message' => "Conversionguard script added successfully On Your Store", 'status' => 'success']);
    }

    public function snippetInclude($active_theme_id, $shop)
    {
        $html = $shop->api()->rest('GET', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes/' . $active_theme_id . '/assets.json', ['asset[key]' => 'layout/theme.liquid'])['body']['asset']['value'];
        $app_include = "\n{% comment %} //Theme Start {% endcomment %} \n {% capture snippet_content %} \n {% include 'conversionguard.liquid' %} \n {% endcapture %} \n {% unless snippet_check contains 'Liquid Error' %} \n {{ snippet_content }} \n {% endunless %} \n {% comment %} //Theme End {% endcomment %}\n\n";

        if (strpos($html, '{% comment %} conversionguard start {% endcomment %}') === false) {

            $pos = strpos($html, '</body>');
            $newhtml = substr($html, 0, $pos) . $app_include . substr($html, $pos);
            $toupdate = [
                "asset" => [
                    "key" => "layout/theme.liquid",
                    "value" => $newhtml
                ]
            ];
            $shop->api()->rest('PUT', '/admin/api/' . env('SHOPIFY_API_VERSION') . '/themes/' . $active_theme_id . '/assets.json', $toupdate);
        }

        else if (strpos($html, '{% capture snippet_content %}') === true)
        {
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

}
