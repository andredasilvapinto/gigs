<?php

require '../protected/config/GigsConfig.php';
require 'sdk/facebook.php';

$facebook = new Facebook(array(
    'appId'     => GigsConfig::FACEBOOK_APP_ID,
    'secret'    => GigsConfig::FACEBOOK_APP_SECRET,
    'cookie'    => true
));

$signed_request = $facebook->getSignedRequest();

$page_id = $signed_request["page"]["id"];
//$like_status = $signed_request["page"]["liked"];
//$app_data = $signed_request["app_data"];

if ($page_id === "")
{
	die("Page id not found");
}
else
{
    $relative_url = '../index.php?r=event/listWidget&pageId=' . $page_id;

    $absolute_url = "http://".$_SERVER['HTTP_HOST']
        .rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
        ."/".$relative_url;

    header("Location: $absolute_url");
}