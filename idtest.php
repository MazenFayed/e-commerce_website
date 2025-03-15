<?php

use Instagram\FacebookLogin\FacebookLogin;

$config = array( // instantiation config params
    'app_id' => '<234253626285487>', // facebook app id
    'app_secret' => '<0e1bb46f54a7cdaf61819922c2c6e214>', // facebook app secret
);

// uri facebook will send the user to after they login
$redirectUri = 'https://localhost/vshop/idtest.php';

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
$facebookLogin = new FacebookLogin( $config );

// display login dialog link
echo '<a href="' . $facebookLogin->getLoginDialogUrl( $redirectUri, $permissions ) . '">' .
    'Log in with Facebook' .
'</a>';