<?php
/**
* library iCal
*
* It's class for sending .ics on email using Amazon API
* Need config array with values: key, secret, fromEmail
*
* @author ???
* 
*/


/**
* autoload.php was generated with composer for amazon aws sdk
* details: https://aws.amazon.com/sdkforphp/
*/
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'aws/aws-autoloader.php';

use Aws\Common\Aws;
use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException as SesException;

class iCal{

	/**
	* @var iCal
	*/
	private $_client;
	/**
	* @var string
	*
	* multiline text
	*/
	private $_body = '';
	/**
	* Storage for errors. Can be get with getError() function
	*
	* @var string
	*
	* string 
	*/
	private $_error = '';
	/**
	* Region. Can be set with setRegion() function
	*
	* @var string
	*/
	private $_region = 'us-west-2';
	/**
	*/
	private $_from;


	/**
	* this function was started when iCal object was created;
	* Ses Client was created in it
	*/
	public function __construct($config){

		if(!empty($config['region'])){
			$this->_region = $config['region'];
		}

		if(empty($config['key'])){
			$this->_error = 'Key required';
		}

		if(empty($config['secret'])){
			$this->_error = 'Secret key required';
		}

		if(!empty($config['fromEmail'])){

			$this->_from = $config['fromEmail'];	

		}else{

			$this->_error = 'From email required';

		}
		
		//ses client creating	
		$this->_client = SesClient::factory(array(
		    'key' 		=> $config['key'],
		    'secret'    => $config['secret'],
		    'region'    => $this->_region,
		));

	}


	/**
	* Function for getting error;
	* If there was no error - function return empty string
	*/
	public function getError(){

		return $this->_error;

	}


	/**
	* Function for setting region;
	* If function was not used - region stay default;
	* Default region is us-west-2;
	*/
	public function setRegion($region){

		$this->_region = $region;

	}



	/**
	*	Function for sending message;
	* 	It check required configs, build .ics and send it;
	*
	*	Require config $icsParams;
	*	Required values: $icsParams['toEmail'], $icsParams['startTime'], $icsParams['location'];
	*
	*	Possible values list:
	*		'toEmail'		  -  destination email
	*		'organizerName'	  -  organizer name
	*		'organizerEmail'  -  organizer email
	*		'startTime'		  -  event start time in UNIX Timestamp format
	*		'endTime'		  -  event end time in UNIX Timestamp format
	*		'description'	  -  event description
	*		'location'		  -  event location
	*		'timezone'		  -  event timezone in PHP Timezone format. Full list on https://php.net/manual/en/timezones.php
	*		'subjectEmail'	  -  email subject
	*		'subjectEvent'	  -  event subject
	*	
	*/
	public function send($icsParams){

		if(!empty($this->_error)){
			return false;
		}

		$this->_prepareBody($icsParams);

		if(!empty($this->_error)){
			return false;
		}

		$emailConfig = array(
			    'Source'        => $this->_from,
			    'Destinations'  => array($icsParams['toEmail']),
			    'RawMessage'    => array(
			        'Data' => base64_encode($this->_body)
			    )
			); 

		return $this->_send($emailConfig);

	}



	/**
	* Function for preparing text message and building .ics
	*/
	private function _prepareBody($icsParams){

		if(empty($icsParams['toEmail'])){

			$this->_error = 'To Email required';
			return false;

		}

		if(empty($icsParams['startTime'])){

			$this->_error = 'Event start time required';
			return false;

		}

		if(empty($icsParams['location'])){

			$this->_error = 'Event location required';
			return false;

		}

		//there are ses headers
		$this->addToBody('From:'.$this->_from);
		$this->addToBody('Subject:'.$icsParams['subjectEmail']);
		$this->addToBody('Content-Type:text/calendar; charset="UTF-8"; method="REQUEST";');
		
		//empty string adding for amazon automatic headers
		$this->addToBody('');

		// building ics body
        $this->addToBody('BEGIN:VCALENDAR');
        $this->addToBody('VERSION:2.0');
        $this->addToBody('X-LOTUS-CHARSET:ISO-8859-1');
        $this->addToBody('PRODID:-//'.$icsParams['organizerName'].'//'.$icsParams['productName'].'//'.$icsParams['language']);
        $this->addToBody('METHOD:REQUEST');
        $this->addToBody('BEGIN:VTIMEZONE');
        $this->addToBody('TZID:'.$icsParams['timezone']);
        $this->addToBody('END:VTIMEZONE');
        $this->addToBody('BEGIN:VEVENT');
        $this->addToBody('UID:'.$icsParams['toEmail']);
        $this->addToBody('CLASS:PUBLIC');
        $this->addToBody('SUMMARY:'.$icsParams['subjectEvent']);
        $this->addToBody('DTSTART;TZID='.$icsParams['timezone'].':'.date('Ymd',$icsParams['startTime']).'T'.date('His',$icsParams['startTime']));
        $this->addToBody('DTEND;TZID='.$icsParams['timezone'].':'.date('Ymd',$icsParams['endTime'])  .'T'.date('His',$icsParams['endTime']));
        $this->addToBody('DTSTAMP:'.date('Ymd').'T'.date('His'));
        $this->addToBody('DESCRIPTION:'.$icsParams['description']);
        $this->addToBody('LOCATION:'.$icsParams['location']);
        $this->addToBody('ORGANIZER;CN="'.$icsParams['organizerName'].'":MAILTO:'.$icsParams['organizerEmail']);
        $this->addToBody('END:VEVENT');
        $this->addToBody('END:VCALENDAR',false);

	}



	/**
	* function for adding new line to message
	*/
	private function addToBody($str, $addEndLine = true){

		$this->_body .= $addEndLine ? $str."\r\n" : $str;  

	}



	/**
	* function which sends builded message with Ses Client (uses amazon API)
	*/
	private function _send($emailConfig){

		try {

		    $this->_client->sendRawEmail($emailConfig);

		    return true;

		} catch(SesException $e) {

		    $this->_error = 'Got exception: ' . $e->getMessage();

		    return false;

		}

	}

}
