<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Redirect;
use Validator;
use PDF;
use Mail;
use Session;
use Google;
//use Google_Service_PeopleService;
use Google_Service_Indexing;
use Google_Service_Indexing_UrlNotification;


class Google3ContactsController extends Controller{

/*
|--------------------------------------------------------------------------
| View Google Contacts Test
|--------------------------------------------------------------------------
*/
    public function addGoogleContact(Request $Request){
	
	  /*$client = new Google\Client();
	  
	  $configJson = base_path().'/config.json';
	
	  $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
      $client->setHttpClient($guzzleClient);
	  
	  //$client = new Google\Client();
      $client->setApplicationName("App test");
	  $client->setAuthConfig($configJson);
	  $client->setAccessType('offline'); // necessary for getting the refresh token*/
	 $configJson = base_path().'/config.json';
	  function oauth2callback(){
		$configJson = base_path().'/config.json';  
	    $client = new Google\Client();
        $client->setAuthConfigFile($configJson);
        $client->setRedirectUri('http://localhost:8000/CreateContacts');
        $client->addScope(Google\Service\Drive::DRIVE_METADATA_READONLY);

		if (! isset($_GET['code'])) {
		  $auth_url = $client->createAuthUrl();
		  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
		} else {
		  $client->authenticate($_GET['code']);
		  $_SESSION['access_token'] = $client->getAccessToken();
		  $redirect_uri = 'http://localhost:8000/CreateContacts';
		  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
		}
	  }
	  
	session_start();

	$client = new Google\Client();
	$client->setAuthConfig($configJson);
	$client->addScope(Google\Service\Drive::DRIVE_METADATA_READONLY);

	if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
	  $client->setAccessToken($_SESSION['access_token']);
	  $drive = new Google\Service\Drive($client);
	  $files = $drive->files->listFiles(array())->getItems();
	  echo "hi".json_encode($files);
	} else {
	  //$redirect_uri = 'http://localhost:8000/CreateContacts';
	  //header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
	  oauth2callback();
	}
      
	 
	  //echo $auth_url;
	
	}
}
 


 



