iCal library
============
<hr>
###This is a PHP library to send out [.ics](http://en.wikipedia.org/wiki/ICalendar) calendar invites through Amazon SES API (aws.amazon.com).
###Official [AWS PHP SDK](https://aws.amazon.com/sdkforphp/) is required [Amazon SES API](http://aws.amazon.com/ses/), see composer.js.
<hr>
The library supports timezones in [PHP Timezone](https://php.net/manual/en/timezones.php) format.<br>
Event time should specify in [UNIX Timestamp](http://www.unixtimestamp.com/) format.
<br>

###How to use:
1. Create an object of iCal class and pass the required options to its constructor. The required options are: SES API key and secret and a verified SES From: email.
2. You can set the region of AWS by calling ::setRegion() method (not a required step)
3. Setup .ics configuration, it should be an array - see iCal.lib.example.php. 
```
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
```
The required options here are: toEmail, event start time, organizer name and location.
4. Call object function send() with the array from p.3 as the only parameter.
5. ::send() returns true or false dependnig on the result.

