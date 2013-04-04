<?php
$starttime = microtime();
$startarray = explode(" ", $starttime);
$starttime = $startarray[1] + $startarray[0];


// *********************************************************************
//
//      MYOB Login sample using OAUTH & Company File authentication
//         
//           Sample Written by Keran McKenzie
//           Date: Feb 2013
//
//      Base HTML & CSS using Twitter Bootstrap Framework
//           http://getbootstrap.com
//
//      PHP scripts, classes etc live in the includes folder
//
//
// ********************************************************************

// WHAT ARE WE DOING HERE?
// 
// 1) set up the variables
// 2) use a switch and URL variables to determine what we are doing
// 3) if we are in localhost or network mode - don't use OAUTH
// 4) if we are using https://api.myob.com/accountright/ then use OAUTH

// TODO:
//
// This is intended as a sample only and not for production, it's to give
// an example workflow to get you up and running with accesing an MYOB users
// company files either using http://localhost, Networkmode or the web API
//
// NOTE: for this example we store the access_token and refresh_token in 
// session variables - you really should store them (esp the refresh_token)
// in a database or more permanent data store.


// *********************************************************************
//
//    Setup the variables - customise this using your details
//        for this example we're using constants - you don't have too
//
// *********************************************************************

define('api_key',		'[API KEY HERE]'); // enter your MYOB Developer Key
define('api_secret',	'[API SECRET HERE]'); // enter your MYOB Developer Secret
define('redirect_url',	'http://localhost/login_sample'); // enter the Redirect URL
// NOTE: this MUST match the url you used when registering for a key
//       you can login to my.myob.com.au anytime and change the redirect url for testing/production
define('api_url',		'https://api.myob.com/accountright/'); // NOTE: api is https not http
// API URL accepts: https://api.myob.com/accountright/, http://localhost:8080/accountright (note: port can change), http://xxx.xxx.xxx.xxx/accountright/
//                  where xxx = ip address of network accessable Accountright install with API running
define('api_scope',		'CompanyFile'); // You shouldn't need to change this

define('base_url',		'http://localhost/login_sample');

// Because we are using sessions to manage our tokens, lets start the session engine
session_start();

// WHAT ARE WE DOING HERE? use $page_to_show for switch
$page_to_show = null;
// Do we have an access token?
// 	  YES) has it expired?
// 			YES) do we have a refresh token?
//				 YES) use refresh token to get new access token
// 				  NO) okay new user - show home page
// 	   		 NO) Use the access token
// 
// SPECIAL CASE, does $_GET['code'] exist? Yes - then we have a redirect from OAUTH


// check for code first
if( isset($_GET['code']) ) {
	// ideally you want to check more, eg: did the user really come from secure.myob.com?
	define('api_access_code', $_GET['code']);
	// lets get the access token now
	// include our oauth class
	include_once('includes/class.myob_oauth.php');
	$oauth = new myob_api_oauth();
	// getAccessToken would return false if there was an error
	$oauth_tokens = $oauth->getAccessToken(api_key, api_secret, redirect_url, api_access_code, api_scope);
	//var_dump($oauth_tokens);
	if( $oauth_tokens ) {
		// okay we've got the tokens in a json object
		// lets SAVE them (ahem - save them somewhere safe)
		$_SESSION['access_token'] = $oauth_tokens->access_token;
		$_SESSION['access_token_expires'] = time() + $oauth_tokens->expires_in; // this sets the time for expiry (Currently 20 mins)
		$_SESSION['refresh_token'] = $oauth_tokens->refresh_token;

		// and becuase this is a session lets refresh the page to clear the URL etc
		header('Location: '.base_url);

		//$page_to_show = 'company_file_list';
	} else {
		// there was an error
		// TODO: add error checking
		$page_to_show = '404';
	}
} else {
	
	// okay $_GET['code'] isn't present lets check for the tokens
	if( isset($_SESSION['access_token']) ) {
		//echo 'token: '.$_SESSION['access_token'];
		// lets check the token hasn't expired
		$expiry_time = time(); // + 600; // note I ad 600 seconds so we get a refresh token before our token expires
		if( $expiry_time > $_SESSION['access_token_expires'] ) {
			// expired so lets get a new token
			// include our oauth class
			include_once('includes/class.myob_oauth.php');
			$oauth = new myob_api_oauth();
			$oauth_tokens = $oauth->refreshAccessToken(api_key, api_secret, $_SESSION['refresh_token']);

			if( $oauth_tokens ) {
				// okay we've got the tokens in a json object
				// lets SAVE them (ahem - save them somewhere safe)
				$_SESSION['access_token'] = $oauth_tokens->access_token;
				$_SESSION['access_token_expires'] = time() + $oauth_tokens->expires_in; // this sets the time for expiry (Currently 20 mins)
				$_SESSION['refresh_token'] = $oauth_tokens->refresh_token;

				// and becuase this is a session lets refresh the page to clear the URL etc
				header('Location: '.base_url);
				//die();
				//$page_to_show = 'company_file_list';
			} else {
				// there was an error
				// TODO: add error checking
				$page_to_show = '404';
			}
		} else {
			// okay we can now make authenticated requests

			if( !isset($_GET['page']) ) {
				$page_to_show = 'company_file_list';
			} else {
				$page_to_show = $_GET['page'];
			}
			
		}
	} else {
		// no access token, this is a new user, show the home page
		$page_to_show = null;
	}
	
} // end if $_GET['code']



// use a switch to tell us what we are doing & page content to load
switch ($page_to_show) {
	case 'company_file_list':
		// show a list of the company files we now have access too
		$page_title = 'Awesome Company Files';
		include_once('includes/header.inc.php');
		include_once('includes/cf_list.inc.php');
		include_once('includes/footer.inc.php');
		break;
	case 'accounts':
		// show a list of the company files we now have access too
		$page_title = 'Awesome Account List';
		include_once('includes/header.inc.php');
		include_once('includes/account_list.inc.php');
		include_once('includes/footer.inc.php');
		break;
	case '404':
		// show a list of the company files we now have access too
		$page_title = 'Awesome Error';
		include_once('includes/header.inc.php');
		include_once('includes/404.inc.php');
		include_once('includes/footer.inc.php');
		break;
				
	default:
		// this is the default page - it should set up your app
		// the page should include a link/button for the user to click to go to myob to login
		$page_title = 'App of Awesome';
		include_once('includes/header.inc.php');//include_once('includes/cf_list.inc.php');
		include_once('includes/home.inc.php');
		include_once('includes/footer.inc.php');
		break;
}





