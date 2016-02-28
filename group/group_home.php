<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>GROUP</title>
<link href="../default.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../login.css" rel="stylesheet" type="text/css" media="screen" />
<link href="group.css" rel="stylesheet" type="text/css" media="screen" />
</head>


<?php
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


if(isset($_REQUEST['grp'])){
		$_SESSION['grpname']=$_REQUEST['grp']; 
		$grpname=$_SESSION['grpname'];
		}
else{
$grpname=$_SESSION['grpname'];
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['postByUser']))
{
$msg=trim($_REQUEST['postByUser']);
if(strcmp($msg,"")==0)
{
	echo '<script>alert("Please write something!");</script>';
}
else
{
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
             
$con=mysqli_connect("localhost","<db username>","<db password>","<db name>"); 
$grpname=$_SESSION['grpname'];
$query="INSERT INTO tgroupposts (groupname,userscreenname,usertwitterid,message) VALUES ('$grpname','$screenname','$twitterid','$msg')";          				
$result=mysqli_query($con,$query);                  
mysqli_close($con);             
}                  
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
			<li><a href="../user/user_home.php">Home</a></li>
			<li><a href="../user/search_post_byUser.php">Search Posts</a></li>
			<li class="current_page_item"><a>Group</a></li>
			<li><a href="../user/game.php">Game</a></li>
		</ul>
	</div>
		
	
	
	
		
	
	
	
	
</div>
<!-- end header -->

<div id="wrapper">
	<!-- start page -->
	<div id="page">
		


		<div id="sidebar1" class="sidebar">
			<ul>
				<li>
					<h2>GROUPS</h2>
					<ul>
						<?php
							$con=mysqli_connect("localhost","<db username>","<db password>","<db name>");
							$query="SELECT * FROM tgrouplist WHERE userscreenname ='$screenname' and usertwitterid='$twitterid'";				
							$result=mysqli_query($con,$query);
							while(($row=mysqli_fetch_array($result)))
							{
								
								if(strcmp($row['groupname'],$grpname)==0)
								{
									echo '<li class="current_page_item"><strong><a>'.$row['groupname'].'</a></strong></li>';
								}
								else
								{
									echo '<li><a href="group_home.php?grp='.$row['groupname'].'">'.$row['groupname'].'</a></li>';
								}
							}
							mysqli_close($con);
						?>
					</ul>
				</li>
				
				
				<li>
					<ul>
						<li><a href="group_create.php">Create Group</a></li>
						<li><a href="group_manage.php">Add Users To Group</a></li>
					</ul>
				</li>
				<!-- can add more <li> like above to create different sections here-->
			</ul>
		</div>



		<!-- start content -->
		<div id="content">
			<div class="post">
				<h2>GROUP: <?php echo $grpname;?></h2>
				<p>MEMBERS:&nbsp&nbsp
				<?php
					$con=mysqli_connect("localhost","<db username>","<db password>","<db name>");
							$query="SELECT * FROM tgrouplist WHERE groupname ='$grpname'";				
							$result=mysqli_query($con,$query);
							while(($row=mysqli_fetch_array($result)))
							{
								echo "@".$row['userscreenname']."&nbsp&nbsp&nbsp";
							}
							mysqli_close($con);
				?>
				<p>
			</div>
			
			
			<div class="post">
				<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
				<textarea class="postByUser" name="postByUser" rows="3" cols="50" placeholder="Write your message here." autofocus maxlength="140"></textarea>
				<div>
					<input class="ngo_approval" value="POST" type="submit">
					<input class="ngo_approval" value="RESET" type="reset">
				</div>
				</form>
			</div>
			
			
			
			<div class="post" id="ref">
				
				
			</div>
			
<script src="../jquery.js"></script>
<script>
window.onload=function()
{

	$('#ref').load('group_display.php');
	setInterval(function()
	{
		$('#ref').load('group_display.php');
	},2000);
}
</script>		
			
			
			
			
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