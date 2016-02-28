<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>CREATE GROUP</title>
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

			
		if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_REQUEST['name']))
		{	
			$grpname=trim($_REQUEST['name']);
			if(strcmp($grpname,"")==0)
				echo '<script>alert("Please enter a proper name.");</script>';
			else{
			$con=mysqli_connect("localhost","<db username>","<db password>","<db name>");
			$query="SELECT * FROM tgrouplist WHERE groupname ='$grpname'";
			$result=mysqli_query($con,$query);
   			$num_rows=mysqli_num_rows($result);
   			if($num_rows==0)
   			{
	   			$isadmin="y";
	   			$query="INSERT INTO tgrouplist VALUES('$grpname','$screenname','$twitterid','$isadmin')";
   				mysqli_query($con,$query);
   				echo '<script>alert("Group successfully created.");</script>';
           	}
           	else{
	           	echo '<script>alert("Group name already taken. Please try another name.");</script>';
	           	}
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
								echo '<li><a href="group_home.php?grp='.$row['groupname'].'">'.$row['groupname'].'</a></li>';
							}
							mysqli_close($con);
						?>
					</ul>
				</li>
				
				
				<li>
					<ul>
						<li class="current_page_item"><a>Create Group</a></li>
						<li><a href="group_manage.php">Add Users To Group</a></li>
					</ul>
				</li>
				<!-- can add more <li> like above to create different sections here-->
			</ul>
		</div>
		



		<!-- start content -->
		<div id="content">
			
			<div class="post">
				<h2>ENTER GROUP NAME:</h2>
				
					<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
					<input type="text" class="name" name="name" autofocus maxlength="20">
					<div>
						<input value="CREATE" type="submit">
						<input value="RESET" type="reset">
					</div>
					</form>
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