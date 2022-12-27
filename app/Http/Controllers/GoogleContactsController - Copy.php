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


class GoogleContactsController extends Controller{

/*
|--------------------------------------------------------------------------
| View Google Contacts Test
|--------------------------------------------------------------------------
*/
    public function addGoogleContact(Request $Request){
	  session_start();
	
	  $client = new Google\Client();
	  
	  $configJson = base_path().'/config.json';
	
	  $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
      $client->setHttpClient($guzzleClient);
	  
	  //$client = new Google\Client();
      $client->setApplicationName("App test");
	  $client->setAuthConfig($configJson);
	  $client->setAccessType('offline'); // necessary for getting the refresh token
      //$client->setApprovalPrompt ('force'); // necessary for getting the refresh token
	  $client->addScope(Google\Service\Drive::DRIVE);
	  // Your redirect URI can be any registered URI, but in this example
      // we redirect back to this same page
      $client->setRedirectUri('http://localhost:8000/CreateContacts');
	  $auth_url = $client->createAuthUrl();
	  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
	  //var_dump($_GET);
	  ///$client->authenticate($_GET['code']);
	  ///$access_token = $client->getAccessToken();
	  /*$client->authenticate($_GET['code']);
	  $access_token = $client->getAccessToken();
	  $client->setAccessToken($access_token);
	  $drive = new Google\Service\Drive($client);
	  $files = $drive->files->listFiles(array())->getItems();*/
	  //$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
	  //$client->setAccessToken($token);
	  /*
	  
           $token = $client->getAccessToken();
		   $client->setAccessToken($token);*/
      
	  
        // scopes determine what google endpoints we can access. keep it simple for now.
      /*$client->setScopes(
            [
                \Google\Service\Oauth2::USERINFO_PROFILE,
                \Google\Service\Oauth2::USERINFO_EMAIL,
                \Google\Service\Oauth2::OPENID,
                \Google\Service\Drive::DRIVE_METADATA_READONLY // allows reading of google drive metadata
            ]
       );*/
       //$client->setIncludeGrantedScopes(true);
      //$client->setDeveloperKey("AIzaSyDFXnkEIyfCoLLPI73Amh3G2YAZZ_lf6Wo");
	  
	  /*$service = new Google\Service\Books($client);
      $query = 'Henry David Thoreau';
      $optParams = [
            'filter' => 'free-ebooks',
      ];
      $results = $service->volumes->listVolumes($query, $optParams);

      foreach ($results->getItems() as $item) {
         echo $item['volumeInfo']['title'], "<br /> \n";
      }*/
	  
	  /*$people_service = new Google\Service\PeopleService($client);
	  
	  
	  
	  $people = $people_service->people_connections->listPeopleConnections(
          'people/me', array('personFields' => 'names,emailAddresses'));*/
	  
	  
	  
	  //echo "hello ".$Request->firstname;
	  //echo $auth_url;
	
	}
	
	
	public function addGoogleContact2(Request $Request){
	  session_start();
	
	  $client = new Google\Client();
	  
	  $configJson = base_path().'/config.json';
	  
	  echo "HElo";
	  
	}
}
 


 



