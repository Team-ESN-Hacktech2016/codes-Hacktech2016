<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>STATS</title>
<link href="default.css" rel="stylesheet" type="text/css" media="screen" />
<link href="login.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="d3.min.js" charset="utf-8"></script>
</head>



<?php
$pageid=6;
session_start();

if(isset($_SESSION['status']) && $_SESSION['status']=='verified') 
{
	header("Location: user/user_home.php");
}

ini_set('display_errors', 1);
	require_once('TwitterAPIExchange.php');
	$settings = array(
    'oauth_access_token' => "<your twitter app oauth access token>",
    'oauth_access_token_secret' => "<your twitter app access token secret>",
    'consumer_key' => "<your twitter app consumer key>",
    'consumer_secret' => "<your twitter app consumer secret>"
	);

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
			<li><a  href="index.php">Home</a></li>
			<li class="current_page_item"><a>Sentiment Analysis</a></li>
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
				<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
				<label for="tag">ENTER # TAG</label>
				<input class="tag" name="tag" type="text" autofocus><br/>
				<input value="SEARCH" type="submit">
				<input value="RESET" type="reset">
				</form>
			</div>
			
			
<?php			
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['tag']))
	{
		
$url = 'https://api.twitter.com/1.1/search/tweets.json';
$getfield = '?lang=en&result_type=recent&count=20&q=';
$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);


		$tag=trim($_REQUEST['tag']);
		
		
		if(empty($tag))
		{
			echo '<div class="post">
				<h1 class="title">Please enter a tag to search</h1>
				<p class="byline"></p>
				<div class="entry" id="entry">';
		}
		else{

		
		$tag=strtolower($tag);
		if(stripos($tag,"#")!==0)
				$tag="#".$tag;
		

		$getfield=$getfield.$tag;
		$res=$twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
		$result=json_decode($res);
		$num_rows=count($result->statuses);
		
		if($num_rows==0)
		{
			echo '<div class="post">
				<h1 class="title">No posts available for the tag: '.$tag.'</h1>
				<p class="byline"></p>
				<div class="entry" id="entry">';
		}			
		else
		{
			echo '<div class="post">
				<h1 class="title">Search Results For Tag: '.$tag.'</h1>
				<p class="byline"></p>
				<div class="entry" id="entry">
				<div id="positiveTweet">
				<h3><u>POSITIVE TWEETS</u></h3>
				</div>
				<br/><br/><br/><br/>
				<div id="negativeTweet">
				<h3><u>NEGATIVE TWEETS</u></h3>
				</div>';
				$pstr='';
				$nstr='';
				$mysqli = new mysqli("localhost","<db username>","<db password>","<db name>");
				
   			
				$posCount=0;
				$negCount=0;
				
				for ($i=0; $i<count($result->statuses); $i++)
				{
					$tun=$result->statuses[$i]->user->name;
					$tusn=$result->statuses[$i]->user->screen_name;
					$tca=$result->statuses[$i]->created_at;
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
					
					
					$ttun=str_replace(array("\r","\n","'"),"",$tun);
					$ttusn=str_replace(array("\r","\n","'"),"",$tusn);
					$ttca=str_replace(array("\r","\n","'"),"",$tca);
					$tttext=str_replace(array("\r","\n","'"),"",$ttext);
										
					if($probP>$probN)
					{
						$posCount=$posCount+1;
						$pstr='<div class="boxlist"><div class="ngo_list"><div><h2> '.$ttun.' <span><small> @'.$ttusn.' </small></span></h2></div><div><span> '.$ttca.' </span></div><div><span> '.$tttext.' </span></div></div></div><br/>';
						?>
						<script>document.getElementById('positiveTweet').innerHTML+='<?php echo $pstr;?>';</script>
					<?php
					}
					else
					{
						/*
$ttun=str_replace(array("\r","\n","'"),"",$tun);
					$ttusn=str_replace(array("\r","\n","'"),"",$tusn);
					$ttca=str_replace(array("\r","\n","'"),"",$tca);
					$tttext=str_replace(array("\r","\n","'"),"",$ttext);
*/
						
						$negCount=$negCount+1;
						$nstr='<div class="boxlist"><div class="ngo_list"><div><h2> '.$ttun.' <span><small> @'.$ttusn.' </small></span></h2></div><div><span> '.$ttca.' </span></div><div><span> '.$tttext.' </span></div></div></div><br/>';
						?>
						<script>document.getElementById('negativeTweet').innerHTML+='<?php echo $nstr;?>';</script>
					<?php
					}

				}
				$mysqli->close();
				echo "Number of positive tweets: ".$posCount."<br/>Number of negative tweets: ".$negCount;
				if($posCount==0)
				{
					?>
						<script>document.getElementById('positiveTweet').innerHTML+="No positive tweets";</script>
					<?php
				}
				if($negCount==0)
				{
					?>
						<script>document.getElementById('negativeTweet').innerHTML+="No negative tweets";</script>
					<?php
				}
			}
		}
}			
else
{
	echo '<div class="post">
				<div class="entry" id="entry">';
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
