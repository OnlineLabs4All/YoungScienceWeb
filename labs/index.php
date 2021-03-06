<?php

USE SoapClient;
USE SoapHeader;

ini_set('display_errors', 'On');

$labId = $_GET['lab_id'];

$cred = array(
		'wsdlURL' => 'http://ilabs.cti.ac.at/iLabServiceBroker/iLabServiceBroker.asmx?wsdl',
		'SbGuid' => '9954C5B79AEB432A94DE29E6EE44EB69',
		'groupName' => 'Young_Citizen_Science_Award',
		'userName' => 'schulwettbewerb',
		'authorityKey' => 'YoungCitizenScienceAward',
		'duration' => '-1',
		'start' => '2014-02-01T18:13:00');


//List of available Labs

$labs['visir'] = array(
					'name'=> 'VISIR',
					'couponId' => '9774',
					'passkey' => 'D6A4DA5685C34382B9BC0F98A40686E8',
					'clientGuid' => '42CC51C112654E05A172A773BC8522FD');

$labs['radiolab'] = array(
					'name'=> 'Radiolab',
					'couponId' => '9775',
					'passkey' => '1AE15340ADE14C76BBB0346B70741EC9',
					'clientGuid' => '3803EB8152FC453BB04AC83B13642DF');

$labs['blackbody'] = array(
					'name'=> 'Blackbody Radiation Lab',
					'couponId' => '9778',
					'passkey' => '5029C04106FE407DBA2F651CE26ABF77',
					'clientGuid' => 'AEE31FF4E456425387DCA52354784772');

$labs['elvis'] = array(
					'name'=> 'ELVIS Lab',
					'couponId' => '11642',
					'passkey' => 'F8FCB85772FE4F83A9451B9501AF51F4',
					'clientGuid' => 'A09F2C36-C569-4CFE-9692-ABFD3B990552');

$labs['tscheby'] = array(
					'name'=> 'Tschebyscheff-Filter Lab',
					'couponId' => '12565',
					'passkey' => 'A3ABF05CDFB94532BEC7FAA5304276A0',
					'clientGuid' => '905FFFBF-5457-4980-BBBB-4E7856369008');


//Check if Lab exists for safety
if(!isset($labs[$labId])) {
	echo 'Unknown labId';
	exit;
}

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

	return $result->LaunchLabClientResult->tag;

}


?>
