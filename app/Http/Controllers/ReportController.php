<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Instagram\AccessToken\AccessToken;
use Phpfastcache\Helper\Psr16Adapter;

//require_once __DIR__ . 'Instagram\autoload.php'; // change path as needed
use Instagram\User\BusinessDiscovery;
use Instagram\FacebookLogin\FacebookLogin;
use Instagram\Page\Page;
use Instagram\User\Insights;
use Instagram\User\User;
use Sunra\PhpSimple\HtmlDomParser;
use function Laravel\Prompts\alert;
use Instagram\twitter\eciTwitterApi;
use Instagram\twitter\TwitterAPIExchange;
//use function simplehtmldom_1_5\file_get_html;


class ReportController extends Controller
{
    /**
     * This function show the image from instagram.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('User/choosereport');
    }
    public function username(Request $request)
    {
        //dd($request->username['_value']);
        $user = ModelsUser::where('id', Auth::user()->id)->update(['Pageusername' => $request->username['_value']]);
        return redirect()->route('instagram.data');
    }
    public function getinstagramreport()
    {
        return Inertia::render('User/instagramreport');
    }
    public function getinstagrampagesinfo()
    {
        $config = array( // instantiation config params
            'app_id' => '820116776464100', // facebook app id
            'app_secret' => '778a867cb0d335515f9b38a7e410258a', // facebook app secret
        );
        // uri facebook will send the user to after they login
        $redirectUri = 'https://localhost/instagram';
        //if(isset($_GET['code']))
        if (isset($_GET['code'])) {
            $accessToken = new AccessToken($config);
            // exchange our code for an access token
            $newToken = $accessToken->getAccessTokenFromCode($_GET['code'], $redirectUri);
            if (!$accessToken->isLongLived()) { // check if our access token is short lived (expires in hours)
                // exchange the short lived token for a long lived token which last about 60 days
                $newToken = $accessToken->getLongLivedAccessToken($newToken['access_token']);
            }
            $config = array( // instantiation config params
                'value' => $newToken['access_token'], // access token to debug
                'access_token' => $newToken['access_token'] // an admin or developer access token for the app
            );
            // instantiate our access token class
            $accessToken = new AccessToken($config);
            // debug the token
            $debug = $accessToken->debug();
            //print_r($debug['data']['user_id']);
            $config = array(
                'user_id' => $debug['data']['user_id'],
                'access_token' => $newToken['access_token']
            );
            // instantiate our access token class
            $accessToken = new AccessToken($config);
            // get permissions
            $permissions = $accessToken->getPermissions();
            //print_r($permissions);
            $config = array( // instantiation config params
                'user_id' =>  $debug['data']['user_id'],
                'access_token' => $newToken['access_token'],
            );
            // instantiate user for use
            $user = new User($config);
            // get user info
            $userInfo = $user->getSelf();
            $pages = $user->getUserPages();
            $config = array( // instantiation config params
                'page_id' => $pages['data'][0]['id'],
                'access_token' => $newToken['access_token'],
            );
            // instantiate page        
            $page = new Page($config);
            // get info
            $pageInfo = $page->getSelf();
            $username = ModelsUser::where('id', Auth::user()->id)->get('Pageusername');
            //dd($username[0]['instagram_username']);
            $searchUrl = 'https://graph.facebook.com/v3.2/' . $pageInfo['instagram_business_account']['id'] . '?fields=business_discovery.username(' . $username[0]['Pageusername'] . '){followers_count,follows_count,media_count,name,biography,id,username,website,media{id,username,caption,like_count,comments_count,timestamp,media_product_type,media_type,owner,permalink,media_url}}&access_token=' . $newToken['access_token'];
            $ch = curl_init($searchUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $html = curl_exec($ch);
            //print_r(json_decode($html, true));
            // print_r(array_key_exists('error', json_decode($html, true)));
            if (!array_key_exists('error', json_decode($html, true))) {
                $searchData = json_decode($html, true);
                //print_r($html);
                $user = ModelsUser::where('id', Auth::user()->id)->update(['Pagedata' => $html]);
                return redirect()->route('instagram.report');
            } else {
                print_r('Can\'t access this account');
                // print_r(json_decode($html, true)['error']['error_user_msg']);
            }
        } else {
            $permissions = array( // permissions to request from the user
                'instagram_basic',
                'instagram_content_publish',
                'instagram_manage_insights',
                'instagram_manage_comments',
                'pages_show_list',
                'ads_management',
                'business_management',
                'pages_read_engagement'
            );

            // instantiate new facebook login
            $facebookLogin = new FacebookLogin($config);
            // display login dialog link
            echo '<a href="' . $facebookLogin->getLoginDialogUrl($redirectUri, $permissions) . '">' .
                'Log in with Facebook' .
                '</a>';
            //return redirect()->route('getlink');
        }
    }
    public function getfacebookpagesinfo()
    {
        $config = array( // instantiation config params
            'app_id' => '820116776464100', // facebook app id
            'app_secret' => '778a867cb0d335515f9b38a7e410258a', // facebook app secret
        );

        // uri facebook will send the user to after they login
        $redirectUri = 'https://localhost/facebook';
        //if(isset($_GET['code']))
        if (isset($_GET['code'])) {
            $accessToken = new AccessToken($config);

            // exchange our code for an access token
            $newToken = $accessToken->getAccessTokenFromCode($_GET['code'], $redirectUri);

            if (!$accessToken->isLongLived()) { // check if our access token is short lived (expires in hours)
                // exchange the short lived token for a long lived token which last about 60 days
                $newToken = $accessToken->getLongLivedAccessToken($newToken['access_token']);
            }
            //print_r($newToken['paging_next_link']);
            //print_r($newToken['access_token']);
            $config = array( // instantiation config params
                'value' => $newToken['access_token'], // access token to debug
                'access_token' => $newToken['access_token'] // an admin or developer access token for the app
            );
            // instantiate our access token class
            $accessToken = new AccessToken($config);
            // debug the token
            $debug = $accessToken->debug();
            //print_r($debug['data']['user_id']);
            $config = array(
                'user_id' => $debug['data']['user_id'],
                'access_token' => $newToken['access_token']
            );
            // instantiate our access token class
            $accessToken = new AccessToken($config);
            // get permissions
            $permissions = $accessToken->getPermissions();
            //print_r($permissions);
            $config = array( // instantiation config params
                'user_id' =>  $debug['data']['user_id'],
                'access_token' => $newToken['access_token'],
            );
            // instantiate user for use
            $user = new User($config);
            // get user info
            $userInfo = $user->getSelf();
                
            $pages = $user->getUserPages();
            print_r($pages);
        } else {
            $permissions = array( // permissions to request from the user
                'instagram_basic',
                'instagram_content_publish',
                'instagram_manage_insights',
                'instagram_manage_comments',
                'pages_show_list',
                'ads_management',
                'business_management',
                'pages_read_engagement',
                'pages_read_user_content',
                'pages_manage_engagement',
                'pages_manage_metadata',
            );

            // instantiate new facebook login
            $facebookLogin = new FacebookLogin($config);

            // display login dialog link
            echo '<a href="' . $facebookLogin->getLoginDialogUrl($redirectUri, $permissions) . '">' .
                'Log in with Facebook' .
                '</a>';
        }
    }
    public function gettwitterpagesinfo()
    {
        $eciTwitterApi = new eciTwitterApi('UaxAs3JZhGfjGx16Zxh89tgj9', 'eEMEqVHXDdCmSb7WDlSHJcWj7xlAm4rou0OAakzpEUu9AXLXC1');
        $twitterPreLoginData = $eciTwitterApi->getDataForLogin('https://localhost/twitter');
        if ('fail' == $twitterPreLoginData['status']) {
            echo $twitterPreLoginData['message'];
        }
        if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) && isset($_SESSION['request_oauth_token'])) {
            //&&$_SESSION['request_oauth_token']==$_GET['oauth_token']
            $eciTwitterApi = new eciTwitterApi(
                'UaxAs3JZhGfjGx16Zxh89tgj9',
                'eEMEqVHXDdCmSb7WDlSHJcWj7xlAm4rou0OAakzpEUu9AXLXC1',
                $_GET['oauth_token'],
                $_SESSION['request_oauth_token_secret']
            );
            $twitterAccessToken = $eciTwitterApi->getAccessToken($_GET['oauth_verifier']);
             $eciTwitterApi = new eciTwitterApi(
                'UaxAs3JZhGfjGx16Zxh89tgj9',
                'eEMEqVHXDdCmSb7WDlSHJcWj7xlAm4rou0OAakzpEUu9AXLXC1',
                $twitterAccessToken['api_data']['oauth_token'],
                $twitterAccessToken['api_data']['oauth_token_secret']
            );
            $data = $eciTwitterApi->getUserInfo();
            // print_r($twitterAccessToken);
            print_r($data);
        } else {
            echo '<a href="' . $twitterPreLoginData['twitter_login_url'] . '">' .
                'Log in with Twitter' .
                '</a>';
        }
    }

    public function gettweet()
    {
        $eciTwitterApi = new eciTwitterApi('UaxAs3JZhGfjGx16Zxh89tgj9', 'eEMEqVHXDdCmSb7WDlSHJcWj7xlAm4rou0OAakzpEUu9AXLXC1');
        $twitterPreLoginData = $eciTwitterApi->getDataForLogin('https://localhost/twitter');
        if ('fail' == $twitterPreLoginData['status']) {
            echo $twitterPreLoginData['message'];
        }
        if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) && isset($_SESSION['request_oauth_token'])) {
            //&&$_SESSION['request_oauth_token']==$_GET['oauth_token']
            $eciTwitterApi = new eciTwitterApi(
                'UaxAs3JZhGfjGx16Zxh89tgj9',
                'eEMEqVHXDdCmSb7WDlSHJcWj7xlAm4rou0OAakzpEUu9AXLXC1',
                $_GET['oauth_token'],
                $_SESSION['request_oauth_token_secret']
            );
            $twitterAccessToken = $eciTwitterApi->getAccessToken($_GET['oauth_verifier']);
            $settings = array(
                'oauth_access_token' => $twitterAccessToken['api_data']['oauth_token'],
                'oauth_access_token_secret' => $twitterAccessToken['api_data']['oauth_token_secret'],
                'consumer_key' => 'UaxAs3JZhGfjGx16Zxh89tgj9',
                'consumer_secret' => 'eEMEqVHXDdCmSb7WDlSHJcWj7xlAm4rou0OAakzpEUu9AXLXC1'
            );

            // twitter api endpoint
            $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

            // twitter api endpoint request type
            $requestMethod = 'GET';

            // twitter api endpoint data
            $getfield = '?screen_name=f33871&count=1';

            // make our api call to twiiter
            $twitter = new TwitterAPIExchange($settings);
            $twitter->setGetfield($getfield);
            $twitter->buildOauth($url, $requestMethod);
            $response = $twitter->performRequest(true, array(CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0));
            $tweets = json_decode($response, true);
            print_r($tweets);
        } else {
            echo '<a href="' . $twitterPreLoginData['twitter_login_url'] . '">' .
                'Log in with Twitter' .
                '</a>';
        }
    }
}
