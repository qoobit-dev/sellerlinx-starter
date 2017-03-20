<?php

define ("STANDARD_TEMPLATE", "");
define ("FACEBOOK_TEMPLATE", "facebook");

include_once("php-graph-sdk/src/Facebook/Facebook.php");
include_once("php-graph-sdk/src/Facebook/SignedRequest.php");
include_once("php-graph-sdk/src/Facebook/FacebookApp.php");
include_once("php-graph-sdk/src/Facebook/FacebookClient.php");
include_once("php-graph-sdk/src/Facebook/HttpClients/FacebookHttpClientInterface.php");
include_once("php-graph-sdk/src/Facebook/HttpClients/FacebookStream.php");
include_once("php-graph-sdk/src/Facebook/HttpClients/FacebookStreamHttpClient.php");
include_once("php-graph-sdk/src/Facebook/HttpClients/HttpClientsFactory.php");
include_once("php-graph-sdk/src/Facebook/HttpClients/FacebookCurl.php");
include_once("php-graph-sdk/src/Facebook/HttpClients/FacebookCurlHttpClient.php");
include_once("php-graph-sdk/src/Facebook/Url/UrlDetectionInterface.php");
include_once("php-graph-sdk/src/Facebook/Url/FacebookUrlDetectionHandler.php");
include_once("php-graph-sdk/src/Facebook/PersistentData/PersistentDataInterface.php");
include_once("php-graph-sdk/src/Facebook/PersistentData/FacebookMemoryPersistentDataHandler.php");
include_once("php-graph-sdk/src/Facebook/PersistentData/FacebookSessionPersistentDataHandler.php");
include_once("php-graph-sdk/src/Facebook/PersistentData/PersistentDataFactory.php");
include_once("php-graph-sdk/src/Facebook/Helpers/FacebookSignedRequestFromInputHelper.php");
include_once("php-graph-sdk/src/Facebook/Helpers/FacebookCanvasHelper.php");
include_once("php-graph-sdk/src/Facebook/Authentication/OAuth2Client.php");
include_once("php-graph-sdk/src/Facebook/Helpers/FacebookPageTabHelper.php");

$app_id     = "756039411215951";
$app_secret_encoded = "MzNjZmYyOGZkMjQyODBjMWM3MmI4MzYzNWUyNDQ0MTA=";  //do not give to user
//echo base64_decode("MzNjZmYyOGZkMjQyODBjMWM3MmI4MzYzNWUyNDQ0MTA");

session_start();

//create the facebook handle using access keys
$fb = new Facebook\Facebook([
  'app_id' => $app_id,
  'app_secret' => base64_decode($app_secret_encoded),
  'default_graph_version' => 'v2.5',
  ]);


$helper = $fb->getPageTabHelper();
$signedRequest = $helper->getSignedRequest();

$app_data;

$isFacebookPortal = false;

//managed to get a signed request from hash
if(!empty($signedRequest)){
	$isFacebookPortal = true;
	$app_data = $signedRequest->getPayload()["app_data"];	
}

//loaded first through Facebook app
if(!empty($_SERVER["HTTP_REFERER"])&&parse_url($_SERVER["HTTP_REFERER"])["host"]=="staticxx.facebook.com"){
	$isFacebookPortal = true;
}

if(!empty($_SESSION['access_method'])&&$_SESSION['access_method']==FACEBOOK_TEMPLATE){
	$isFacebookPortal = true;
}




if(isset($_GET['access_method'])){

	$access_method = $_GET['access_method'];
	if($access_method == FACEBOOK_TEMPLATE && $isFacebookPortal){
		$_SESSION['access_method'] = FACEBOOK_TEMPLATE;
	}
	else{
		$_SESSION['access_method'] = "";
	}

	$url = parse_url($_SERVER['REQUEST_URI']);
	//redirect to self if access method is set and store as session
	header("location:".$url['path']);
}
else{
	//figure out a way to revert users back to desktop mode?
}


?>