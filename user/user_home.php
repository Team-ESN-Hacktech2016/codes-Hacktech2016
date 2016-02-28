<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>USER</title>
<link href="../default.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../login.css" rel="stylesheet" type="text/css" media="screen" />
</head>


<?php
$pageid=4;
session_start();

include_once("config.php");
include_once("inc/twitteroauth.php");

if(isset($_SESSION['status']) && $_SESSION['status']=='verified') 
{
	
	$screenname=$_SESSION['screenname'];
	$twitterid=$_SESSION['twitterid'];
	$oauth_token=$_SESSION['oauth_token'];
	$oauth_token_secret=$_SESSION['oauth_token_secret'];
	
}
else
{
	header("Location: ../index.php");
}
ini_set('display_errors', 1);
	require_once('../TwitterAPIExchange.php');
	$settings = array(
    'oauth_access_token' => $oauth_token,
    'oauth_access_token_secret' => $oauth_token_secret,
    'consumer_key' => "<your Twitter Dev App consumer key>",
    'consumer_secret' => "<your Twitter Dev App consumer secret>"
	);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['postByUser']))
{
$msg=trim($_REQUEST['postByUser']);
$settings1 = array(
    'oauth_access_token' => $oauth_token,
    'oauth_access_token_secret' => $oauth_token_secret,
    'consumer_key' => "<your Twitter Dev App consumer key>",
    'consumer_secret' => "<your Twitter Dev App consumer secret>"
	);

$url = 'https://api.twitter.com/1.1/statuses/update.json';
$requestMethod = 'POST';

$postfields = array(
    'status' => $msg
);

$twitter = new TwitterAPIExchange($settings1);
$twitter->buildOauth($url, $requestMethod)
             ->setPostfields($postfields)
             ->performRequest();
             
}
?>









<body>	
<!-- start header -->
<div id="header">
	<div id="logo">
		<h1><a><span>Enterprise Social Network</span></a></h1>
		
		<div class="register" align="right">
			<span class="welcome">Welcome, <?php echo $screenname;?>!</span><br><br>
			<a href="logout.php">Logout</a>
		</div>
		
	</div>
	<div id="menu">
		<ul id="main">
			<li class="current_page_item"><a>Home</a></li>
			<li><a href="search_post_byUser.php">Search Posts</a></li>
			<li><a href="../group/group_create.php">Group</a></li>
			<li><a href="game.php">Game</a></li>
		</ul>
	</div>
</div>
<!-- end header -->




<div id="wrapper">
	<!-- start page -->
	<div id="page">

		<!-- start content -->
		<div id="content">
			<div class="post">
				<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
				<textarea class="postByUser" name="postByUser" rows="3" cols="50" placeholder="Write your message here." autofocus maxlength="140"></textarea>
				<div>
					<input class="ngo_approval" value="POST" type="submit">
					<input class="ngo_approval" value="RESET" type="reset">
				</div>
				</form>
			</div>
			
			
			
<?php
	
	
	
	
	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	$getfield = '?screen_name=';
	$requestMethod = 'GET';
	$twitter = new TwitterAPIExchange($settings);
	$getfield=$getfield.$screenname;
	$res=$twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
	$result=json_decode($res);
	$num_rows=count($result);


	if($num_rows==0)
	{
		echo '<div class="post">
				<h1 class="title">No posts available.</h1>
				<p class="byline"></p>
				<div class="entry" id="entry">';
	}			
	else
	{
		echo '<div class="post">
				<h1 class="title">Your Recent Posts</h1>
				<p class="byline"></p>
				<div class="entry" id="entry">';
				
				for ($i=0; $i<count($result); $i++)
				{
					echo '<div class="boxlist">
					<div class="ngo_list">
					<div>
						<span>'.$result[$i]->created_at.'</span>
					</div>
					<div>
						<span>'.$result[$i]->text.'</span>
					</div>
					</div>
					</div>
					';

				}
	}
?>							

				</div>
			</div>
		</div>
			
		<!-- end content -->
		
		
		
		
		
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end page -->
</div>





<div id="footer">
	<p class="copyright">Created by Harsh Fatepuria</p>
</div>


</body>
</html>