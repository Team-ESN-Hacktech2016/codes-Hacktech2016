<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>ADD USERS TO GROUP</title>
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
	
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['grpname']) && isset($_REQUEST['uscreenname']))
{
	
	if(strcmp($_REQUEST['grpname']," ")==0 || strcmp($_REQUEST['uscreenname']," ")==0)
	{
		echo '<script>alert("Please select options from both fields.");</script>';
	}
	else{
	$selectedscreenname=strtok($_REQUEST['uscreenname']," ");
	$selectedtwitterid=strtok(" ");
	$selectedgroup=$_REQUEST['grpname'];
	
	$con=mysqli_connect("localhost","<db username>","<db password>","<db name>");
	$query="SELECT * FROM tgrouplist WHERE userscreenname ='$selectedscreenname' and usertwitterid='$selectedtwitterid' and groupname='$selectedgroup'";				
	$result=mysqli_query($con,$query);
	$num_rows=mysqli_num_rows($result);
   	if($num_rows==0)
   	{
	   			$isadmin="n";
	   			$query="INSERT INTO tgrouplist VALUES('$selectedgroup','$selectedscreenname','$selectedtwitterid','$isadmin')";
   				mysqli_query($con,$query);
   				echo '<script>alert("User successfully added to the group.");</script>';
    }
    else{
	           	echo '<script>alert("User already present in the group.");</script>';
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
						<li><a href="group_create.php">Create Group</a></li>
						<li class="current_page_item"><a>Add Users To Group</a></li>
					</ul>
				</li>
				<!-- can add more <li> like above to create different sections here-->
			</ul>
		</div>




		<!-- start content -->
		<div id="content">
			
			<div class="post">
				<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
				<label for="grpname">GROUP(s) YOU CAN ADD USERS TO:</label>
				<select name="grpname">
					<option value=" "></option>

					<?php
						$isadmin="y";
						$con=mysqli_connect("localhost","<db username>","<db password>","<db name>");
						$query="SELECT * FROM tgrouplist WHERE userscreenname ='$screenname' and usertwitterid='$twitterid' and admin='$isadmin'";				
						$result=mysqli_query($con,$query);
						while(($row=mysqli_fetch_array($result)))
						{
							echo '<option value="'.$row['groupname'].'">'.$row['groupname'].'</option>';
						}
						?>
				</select>
				<br/>
				
				<label for="uscreenname">LIST OF REGISTERED USERS:</label>
				<select name="uscreenname">
					<option value=" "></option>

					<?php
						$query="SELECT * FROM tuser";				
						$result=mysqli_query($con,$query);
						while(($row=mysqli_fetch_array($result)))
						{
							echo '<option value="'.$row['screenname'].' '.$row['twitterid'].'">'.$row['screenname'].'</option>';
						}
						mysqli_close($con);
						?>
				</select>
				<input value="ADD" type="submit">
				<input value="RESET" type="reset">
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