<?php
 
require "twilio-php-master/Services/Twilio.php";
 
// set your AccountSid and AuthToken from www.twilio.com/user/account
$AccountSid = "<your Twilio account Sid>";
$AuthToken = "<your Twilio account AuthToken>";
 
$client = new Services_Twilio($AccountSid, $AuthToken);
 
/*
$message = $client->account->messages->create(array(
    "From" => "<Your twilio number>",
    "To" => "<Number to send a message to>",
    "Body" => "Test message!",
));
 
// Display a confirmation message on the screen
echo "Sent message {$message->sid}";
*/



foreach ($client->account->messages->getIterator(0, 50) as $message) {
    echo "From: {$message->from}\nTo: {$message->to}\nBody: " . $message->body;
	//$message->redact(); // Erases 'Body' field contents
	//$message->delete(); // Deletes entire message record
}


?>