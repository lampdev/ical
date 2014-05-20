<?php

//Thats example code which used iCal library
require_once('iCal.lib.php');

//config for new iCal object
$configForObject = array(
    // note that this IAM user identified by this key pair MUST be allowed to do the following API calls: sendEmail , sendRawEmail. This is done in AWS IAM.
    'key'		=> 'xxxxxxxxxxxxxxxxx',
    'secret'	=> 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
    // this is a verified at SES From: email address. You MUST verify it before sending any emails. This is done in AWS SES -> Email Addresses -> Verify a New Email Address. Or via an API call
    'fromEmail'	=> '<orders@lamp-dev.com>'
);

//new iCal object with previously created config
$iCalObject = new iCal($configForObject);

//config for .ics
$icsConfig = array(
    'toEmail'		 => 'orders@lamp-dev.com',
    'organizerName'	 => 'Example organization name',
    'productName'	 => 'Example organization product',
    'language'       => 'EN',
    'organizerEmail' => 'orders@lamp-dev.com',
    'startTime'		 => (new \DateTime('2014-11-21 12:00:00'))->getTimestamp(),
    'endTime'		 => (new \DateTime('2014-11-21 14:00:00'))->getTimestamp(),
    'description'	 => 'Event description',
    'location'		 => 'Event location',
    'timezone'		 => 'Europe/Amsterdam',  //example timezone from https://php.net/manual/en/timezones.php
    'subjectEmail'	 => 'Email subject',
    'subjectEvent'	 => 'Event subject',
    'organizerName'  => 'John Doe',
    'organizerEmail' => 'john.doe@gmail.com'
);

//set region
$iCalObject->setRegion('us-west-2');

//email sending
$ret = $iCalObject->send($icsConfig);

//Checking sending error
if(!$ret) {
    echo $iCalObject->getError() . PHP_EOL;
}

