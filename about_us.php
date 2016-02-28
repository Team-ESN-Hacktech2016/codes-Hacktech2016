<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>ABOUT US</title>
<link href="default.css" rel="stylesheet" type="text/css" media="screen" />
</head>


<?php
$pageid=3;
session_start();
if(isset($_SESSION['status']) && $_SESSION['status']=='verified') 
{
	header("Location: user/user_home.php");
}
?>




<body>
	
<!-- start header -->
<div id="header">
	<div id="logo">
		<h1><a href="index.php"><span>Enterprise Social Network</span></a></h1>
		
		<div class="signin" align="left">
			<div class="credential">
				<a href="loginProcess.php"><img src="images/sign-in-with-twitter-l.png" width="151" height="24" border="0" /></a>
				</div>
		</div>
		
	</div>
	<div id="menu">
		<ul id="main">
			<li><a href="index.php">Home</a></li>
			<li><a href="getStat.php">Sentiment Analysis</a></li>
			<li><a href="search_post.php">Search Posts</a></li>
			<li class="current_page_item"><a>About</a></li>
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
				<h1 class="title">Hi! This application is created by me to use the Twitter v1.1 REST API along with PHP and MySQL. This application is also integrated with Twilio such as when you send a text message to your Twilio number with the text: @sentiment #'tag to be searched'(without quotes)</h1>
				<p class="byline"></p>
				<div class="entry">
					
					
				</div>
			</div>
		</div>
			
			
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end page -->
</div>





<div id="footer">
	<p class="copyright">Created by Harsh Fatepuria</p>
</div>


</body>
</html>
