<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Home</title>
<link href="default.css" rel="stylesheet" type="text/css" media="screen" />
</head>


<?php
$pageid=1;
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
		<h1><a href="#"><span>Enterprise Social Network</span></a></h1>
		
		<div class="signin" align="left">
				<div class="credential">
				<a href="loginProcess.php"><img src="images/sign-in-with-twitter-l.png" width="151" height="24" border="0" /></a>
				</div>
		</div>
		
	</div>
	<div id="menu">
		<ul id="main">
			<li class="current_page_item"><a>Home</a></li>
			<li><a href="getStat.php">Sentiment Analysis</a></li>
			<li><a href="search_post.php">Search Posts</a></li>
			<li><a href="about_us.php">About</a></li>
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
				<h1 class="title">Welcome to Enterprise Social Network!</h1>
				<p class="byline"></p>
				<div class="entry">
					
					<p>It is a new twitter integrated network in which you can login and post your views, create groups, fetch posts based on the # tag.</p>
					<p>You can see the sentiment analysis of tweets based on a particular # tag.</p>
					<p>You can write words beginning with # sign to indicate it as a keyword in the post.</p>
					<p>BASIC INSTRUCTION:
						<ul>
							<li>You have to <strong>Sign in</strong> to register in the chat system.</li>
							<li>Only on doing so, other users can add you to groups.</li>
						</ul>
					</p>
					<p>INSTRUCTION FOR CREATING AND USING GROUPS:
						<ul>
							<li>Go to the <strong>Create Group</strong> section in the <strong>Group</strong> tab and create a group there.</li>
							<li>Then go to the <strong>Add Users To Group</strong> section to add registered users in a particular group.</li>
							<li>You have to add users to the group <strong>one at a time</strong>.</li>
							<li>Only the person who has created the group can add users to that group.</li>
						</ul>
					</p>

					<p>This application is also linked to Twilio:
						<ul>
							<li>Send a message to your Twilio number with the text: "@sentiment #tag to be analyzed"(without quotes).</li>
							<li>You will receive the analysis of recent tweets in a text message.</li>
						</ul>
					</p>
					
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
