iCal library
============
<hr>
###This library make it easy to generate and send [.ics](http://en.wikipedia.org/wiki/ICalendar) in email.
####For using required [Amazon SES API](http://aws.amazon.com/ses/), connected with [composer](https://getcomposer.org).<br>
[how to install libraries via composer](http://docs.aws.amazon.com/aws-sdk-php/guide/latest/installation.html)
<hr>
iCal support timezones in [PHP Timezone](https://php.net/manual/en/timezones.php) format.<br>
Event time should specify in [UNIX Timestamp](http://www.unixtimestamp.com/) format.
<br>

###The principle of using:
1. Creating object, for creating required config which contains key, secret key, end sender email
2. Setting AWS region (not required step)
3. Creating config for .ics. It must contain toEmail, event start time, organizer name and location
4. Call object function send() with config as parametr
5. If send() was return "false" - call object function getError() for getting error description

