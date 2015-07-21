<?php

use SoapClient;
use SoapHeader;

ini_set('display_errors', 'On');

$labId = $_GET['lab_id'];

$cred = array('wsdlURL' => 'http://ilabs.cti.ac.at/iLabServiceBroker/iLabServiceBroker.asmx?wsdl',
	            'SbGuid' => '9954C5B79AEB432A94DE29E6EE44EB69',
                'groupName' => 'Go-Lab',
	            'userName' => 'schulwettbewerb',
	            'authorityKey' => 'gateway4labstestCUAS',
	            'duration' => '-1',
	            'start' => '2014-02-01T18:13:00' );

//List of Labs available



$labs['visir'] = array('name'=> 'VISIR',
		         'couponId' => '718',
	             'passkey' => '159D9284691546C9ACE7472851D22CF6',
		         'clientGuid' => '1CFCEC4FFAC94D168C614CB22E69AC15');

$labs['radiolab'] = array('name'=> 'Radiolab',
	'couponId' => '5319',
	'passkey' => '73BAA965FFDE47DABE25BEF61211B6E3',
	'clientGuid' => 'D3EB17D710EB4A84A8891F4D106C54AF');




$redirectUrl = getURL($labs[$labId], $cred);
header("Location: ".$redirectUrl);

    
    function getURL($lab, $cred){ //call this method "reserve"

      //Set SOAP Headers
         $headerParams = array('coupon' => array(
                                     'couponId'=>$lab['couponId'],
                                     'issuerGuid'=>$cred['SbGuid'], 
                                     'passkey'=>$lab['passkey'])); 
    //Set Body parameters
   	 $params = array(
		'clientGuid' =>$lab['clientGuid'], //provide as parameter for the service
		'groupName'=>$cred['groupName'],
      	        'userName'=>$cred['userName'],
                'authorityKey'=>$cred['authorityKey'],
                'start'=>$cred['start'],
                'duration'=>$cred['duration']);
     
     //Create SOAP Client
   	  $client = new SoapClient($cred['wsdlURL'], array('trace' => 1));
   	  $header = new SOAPHeader('http://ilab.mit.edu/iLabs/type', 'OperationAuthHeader', $headerParams); 
    	  $client->__setSoapHeaders($header);
    
   	  $result = $client->__soapCall('LaunchLabClient',array($params));

   	 return  $result->LaunchLabClientResult->tag;

    }



?>
