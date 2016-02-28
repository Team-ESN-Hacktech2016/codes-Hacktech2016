<?php
session_start();
include_once("user/config.php");
include_once("user/inc/twitteroauth.php");

if (isset($_REQUEST['oauth_token']) && $_SESSION['token']  !== $_REQUEST['oauth_token']) {

	// if token is old, distroy any session and redirect user to index.php
	session_destroy();
	header('Location: index.php');
	
}
elseif(isset($_REQUEST['oauth_token']) && $_SESSION['token'] == $_REQUEST['oauth_token']) {

	// everything looks good, request access token
	//successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['token'] , $_SESSION['token_secret']);
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	if($connection->http_code=='200')
	{
		//redirect user to twitter
		$_SESSION['status'] = 'verified';
		$_SESSION['request_vars'] = $access_token;
		
		$_SESSION['screenname']=$_SESSION['request_vars']['screen_name'];
		$_SESSION['twitterid']=$_SESSION['request_vars']['user_id'];
		$_SESSION['oauth_token']=$_SESSION['request_vars']['oauth_token'];
		$_SESSION['oauth_token_secret']=$_SESSION['request_vars']['oauth_token_secret'];
		
		
		$screenname=$_SESSION['screenname'];
		$twitterid=$_SESSION['twitterid'];
		
		
		//creating new user entry in db
			$con=mysqli_connect("localhost","<db username>","<db password>","<db name>");
			$query="SELECT * FROM tuser WHERE screenname ='$screenname' and twitterid='$twitterid'";
			$result=mysqli_query($con,$query);
   			$num_rows=mysqli_num_rows($result);
   			if($num_rows==0)
   			{
	   			$query="INSERT INTO tuser VALUES('$screenname','$twitterid')";
   				mysqli_query($con,$query);
           	}
   			mysqli_close($con);
		
	
		
		// unset no longer needed request tokens
		//unset($_SESSION['token']);
		//unset($_SESSION['token_secret']);
		header('Location: index.php');
	}
	else{
		die("error, try again later!");
	}
		
}
else{

	if(isset($_GET["denied"]))
	{
		header('Location: index.php');
		die();
	}

	//fresh authentication
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->getRequestToken(OAUTH_CALLBACK);
	
	//received token info from twitter
	$_SESSION['token'] 			= $request_token['oauth_token'];
	$_SESSION['token_secret'] 	= $request_token['oauth_token_secret'];
	
	// any value other than 200 is failure, so continue only if http code is 200
	if($connection->http_code=='200')
	{
		//redirect user to twitter
		$twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
		header('Location: ' . $twitter_url); 
	}
	else{
		die("error connecting to twitter! try again later!");
	}
}
?>

