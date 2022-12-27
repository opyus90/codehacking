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
use App\Models\User;
use Google_Service_Indexing;
use Google_Service_Indexing_UrlNotification;
use Google_Service_PeopleService_Person;


class GoogleContactsController extends Controller{

/*
|--------------------------------------------------------------------------
| Add Google Contacts 
|--------------------------------------------------------------------------
*/  
    public function GoogleContact(Request $Request){
		
		return view('CreateGoogleContacts');
	}


    public function addGoogleContact(Request $Request){
	  
	    session_start();
	    $configJson = base_path().'/config.json';

		$client = new Google\Client();
		$client->setAuthConfig($configJson);
		//$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        //$client->setHttpClient($guzzleClient);
		
		$client->setApplicationName("App test");
		$client->setAccessType('offline');
		$client->setApprovalPrompt ('force'); // necessary for getting the refresh token
		$client->setLoginHint('aureliu.mocanu@gmail.com');

		$client->addScope(Google\Service\PeopleService::CONTACTS_READONLY);
		$client->addScope(Google\Service\PeopleService::CONTACTS);
		
		$client->setIncludeGrantedScopes(true); //new added
		
		$client->setRedirectUri('http://localhost:8000/CreateContacts2');
		$redirect_uri = 'http://localhost:8000/CreateContacts2';

		$auth_url = $client->createAuthUrl();
		$user = User::where('id', '=', 1)->first();
		$accessTokenJson = stripslashes($user->google_access_token_json);

		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		    $client->setAccessToken($_SESSION['access_token']);
		    $user->google_access_token_json = json_encode($_SESSION['access_token']);
		    $user->save();

		    $people_service = new Google\Service\PeopleService($client);
		  
		    $person = new Google\Service\PeopleService\Person();
            
			$name = new Google\Service\PeopleService\Name();
			$name->setGivenName($Request->firstname);
			$name->setFamilyName($Request->lastname);
			$person->setNames($name);
			
			$phone = new Google\Service\PeopleService\PhoneNumber();
			$phone->setValue($Request->phone);
			//$phone->setCanonicalForm("+447552974514");
			$person->setPhoneNumbers($phone);
			
			if ( isset($Request->email) ) {
			    $email = new Google\Service\PeopleService\EmailAddress();
			    $email->setValue($Request->email);
			    $person->setEmailAddresses($email);
            }
			
            //return redirect(filter_var($auth_url, FILTER_SANITIZE_URL));
			$exe = $people_service->people->createContact($person);

			print_r($exe);
		} else if ( isset($accessTokenJson) && $client->isAccessTokenExpired() ){
			
			$client->setAccessToken($accessTokenJson);
			$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $client->setAccessToken($client->getAccessToken());

            // save new access token
            $user->google_access_token_json = json_encode($client->getAccessToken());
            $user->save();
			
			$people_service = new Google\Service\PeopleService($client);
			
			$person = new Google\Service\PeopleService\Person();

			$name = new Google\Service\PeopleService\Name();
			$name->setGivenName($Request->firstname);
			$name->setFamilyName($Request->lastname);
			$person->setNames($name);
			
			$phone = new Google\Service\PeopleService\PhoneNumber();
			$phone->setValue($Request->phone);
			$person->setPhoneNumbers($phone);
			
			if ( isset($Request->email) ) {
			    $email = new Google\Service\PeopleService\EmailAddress();
			    $email->setValue($Request->email);
			    $person->setEmailAddresses($email);
            }

			$exe = $people_service->people->createContact($person);

			print_r($exe);
			
		} else {
		  
		    return redirect (filter_var($auth_url, FILTER_SANITIZE_URL));

		}
      
	
	}
	
	
	public function addGoogleContact2(Request $Request){
	    session_start();
	  
	    $configJson = base_path().'/config.json';
	  
	    $client = new Google\Client();
		//$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        //$client->setHttpClient($guzzleClient);
		
		$client->setApplicationName("App test");
		$client->setAccessType('offline');
		$client->setApprovalPrompt ('force'); // necessary for getting the refresh token
		$client->setLoginHint('aureliu.mocanu@gmail.com');

		$client->addScope(Google\Service\PeopleService::CONTACTS_READONLY);
		$client->addScope(Google\Service\PeopleService::CONTACTS);
		
		$client->setIncludeGrantedScopes(true); //new added
		
		if (! isset($_GET['code'])) {
		  $auth_url = $client->createAuthUrl();
		  return redirect (filter_var($auth_url, FILTER_SANITIZE_URL));

		} else {
		  $client->authenticate($_GET['code']);
		  $_SESSION['access_token'] = $client->getAccessToken();
		  $redirect_uri = 'http://localhost:8000/CreateContacts';

		  return redirect (filter_var($redirect_uri, FILTER_SANITIZE_URL));
		}
	  
	}
}
 


 



/*$people = $people_service->people_connections->listPeopleConnections(
          'people/me', array('personFields' => 'names,emailAddresses,phoneNumbers'));
		  
		   foreach ($people->getConnections() as $person) {
             $emails = $person->getEmailAddresses();
			
			 $names = $person->getNames();
			 $phones = $person->getPhoneNumbers();
			 
			 foreach ($names as $name){
				//echo "</br>";
				//print_r($name);
				//foreach($name as $n){
			     //echo "</br>";
				 //print_r($n);	
				 echo $name['displayName']."</br>";
				//}
				//echo $name;
				
			 }
			 foreach ($emails as $email){
			   echo $email['value']."</br>"; 
			 }
			 
			 foreach ($phones as $phone){
			   echo $phone['value']."</br>"; 
			   //print_r($phone);
			 }
			 
			
		   }*/