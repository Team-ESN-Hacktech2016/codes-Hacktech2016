<?php
ini_set('display_errors', 1);
	require_once('TwitterAPIExchange.php');
	$settings = array(
    'oauth_access_token' => "<your twitter dev app oauth access token>",
    'oauth_access_token_secret' => "<your twitter dev app oauth access token secret>",
    'consumer_key' => "<your twitter dev app consumer key>",
    'consumer_secret' => "<your twitter dev app consumer secret>"
	);

	
require "twilio-php-master/Services/Twilio.php";
 

try{

$AccountSid = "<your twilio account Sid>";
$AuthToken = "<your twilio account AuthToken>";
 
$client = new Services_Twilio($AccountSid, $AuthToken);




foreach ($client->account->messages->getIterator(0, 50, array(
    'To' => '<your twilio number>'
)) as $message) {
    echo "From: {$message->from}\nTo: {$message->to}\nBody: " . $message->body;
    
    $from=$message->from;
    $str=$message->body;
    $str=trim($str);
    $returnMessage="";
    if(strlen($str)>=13 && substr_count($str, ' ')==1 && substr($str,0,10)=="@sentiment" && substr_count($str, '#')==1)
    {
	    $tag=substr($str,11);
	    $tag=strtolower($tag);
	    
	    
	    
	    $url = 'https://api.twitter.com/1.1/search/tweets.json';
		$getfield = '?lang=en&result_type=recent&count=20&q=';
		$requestMethod = 'GET';
		$twitter = new TwitterAPIExchange($settings);
		$getfield=$getfield.$tag;
		$res=$twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
		$result=json_decode($res);
		$num_rows=count($result->statuses);
	    if($num_rows==0)
		{
			$returnMessage="No posts available for the tag: ".$tag;
		}			
		else
		{
			$mysqli = new mysqli("localhost","<db username>","<db password>","<db name>");

   			
			$posCount=0;
			$negCount=0;
				
			for ($i=0; $i<count($result->statuses); $i++)
			{
				$ttext=$result->statuses[$i]->text;
				$str=$ttext;
				$probP=0;
				$probN=0;
				$chunks=preg_split('/[\s, .!\'\"]+/', $str);
				foreach ($chunks as $word)
				{
						//echo $word."<br/>";
						$query="SELECT * FROM ttrain WHERE tword ='$word'";
						
						if ($dbresult = $mysqli->query($query)) {

							/* determine number of rows result set */
							$row_cnt = $dbresult->num_rows;

							//echo "Rows=".$row_cnt." ";
							if($row_cnt>0)
							{
								$row1=mysqli_fetch_array($dbresult);
							//echo " ".$row1['tword']." ".floatval($row1['tpos'])." ".floatval($row1['tneg'])."<br>";
								$probP=$probP+(floatval($row1['tpos']));
								$probN=$probN+(floatval($row1['tneg']));
							}
						
							/* close result set */
							$dbresult->close();
						}
				}
					
					
				$chunks=preg_split('/[\s, .]+/', $str);
				foreach ($chunks as $word)
				{
						if($word==":)")
							$probP=$probP+2;
						else if($word==";)")
							$probP=$probP+1;
						else if($word==":-)")
							$probP=$probP+2;
						else if($word==":D")
							$probP=$probP+2;
						else if($word==":-D")
							$probP=$probP+2;
						else if($word==";)")
							$probP=$probP+1;
						else if($word==":(")
							$probN=$probN+2;
						else if($word==":-(")
							$probN=$probN+2;
						else if($word=="(re)")
							$probN=$probN+2;
						else if($word==":'(")
							$probN=$probN+2;
						else if($word==":@")
							$probN=$probN+2;
						else if($word=="<3")
							$probP=$probP+2;
						else if($word==":*")
							$probP=$probP+2;
						else if($word==":-*")
							$probP=$probP+2;
						else if($word==":/")
							$probN=$probN+2;
						else if($word==":-/")
							$probN=$probN+2;
						else if($word==":clap:")
							$probP=$probP+2;
						else if($word==":+1:")
							$probP=$probP+2;
				}
					
				if($probP>$probN)
				{
						$posCount=$posCount+1;
				}
				else					
				{
						$negCount=$negCount+1;
				}
			}
			$mysqli->close();
			$returnMessage="Analysis of the tag: ".$tag."\nNumber of positive tweets: ".$posCount."\nNumber of negative tweets: ".$negCount;
	    }
    }
    else
    {
	    $returnMessage="Syntax error! Correct syntax is:@sentiment #<tag to be analyzed>";
    }
    
    
    $sendMessage = $client->account->messages->create(array(
    "From" => "<your twilio number>",
    "To" => $from,
    "Body" => $returnMessage
	));
 
	// Display a confirmation message on the screen
	echo $returnMessage." <br/>Sent message {$sendMessage->sid}";
    
	$message->redact(); // Erases 'Body' field contents
	$message->delete(); // Deletes entire message record
}
		
}	
catch(Services_Twilio_RestException $e) {
            echo "ERROR: ".$e;
        }
?>