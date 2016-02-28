


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

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

	require_once('../TwitterAPIExchange.php');
	ini_set('display_errors', 1);
	$settings = array(
    'oauth_access_token' => $oauth_token,
    'oauth_access_token_secret' => $oauth_token_secret,
    'consumer_key' => "wLxPVem5Mbi9gjDODoVOK5oPs",
    'consumer_secret' => "UR5XeaEYkBZajQoiS8fNHEmYKkXNbQk960NvjffluSWfWWXK4n"
	);




?>


<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>ROAD RUNNER</title>
<link href="defaultgame.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../login.css" rel="stylesheet" type="text/css" media="screen" />
	    
	    
	    
        <style>
        #d{
            float:right; 
            width:23%; 
            display:box;
            }
        table{
            width:50%;
            border-spacing: 0px;
            border-collapse: collapse;
        }
        td{
            padding:0px;
        }
        tr{
            padding:0px;
        }    
            
            #headernew {
    background-color:#8C0209;
    color:white;
    text-align:center;
    padding:1px;
    border-radius:10px;
}

#section {
    
    
    padding:3px;	 	 
}            
            
            
            
        </style>
        
        
    <script src="jquery-1.11.3.js"></script>
    <script src="rendergraphics.js"></script>
<script type="text/javascript">
   var lines = [];
    var ci=0;
    var cj=0;
    var p=0;
    var win=0;
    var oldi=0;
    var oldj=0;
    var oldesti=0;
    var oldestj=0;
    var timeCount=-1;
    $(document).ready(function() {
       
    $.ajax({
        type: "GET",
        url: "samplelvl_new.txt",
        dataType: "text",
        success: function(data) {processData(data);
                                }
     });
        
        document.onkeydown = makeMove;
       
});

function do_nothing()
    {
        return;
    }   
    
    function resetgame()
    {
        alert("Time out! Please restart");
        location.reload();
    }


    
function processData(allText) {
    var allTextLines = allText.split(/\r\n|\n/);
    for (var i=0; i<allTextLines.length; i++) {
        var data = allTextLines[i].split(',');
           var tarr = [];
            for (var j=0; j<data.length; j++) {
                tarr.push(data[j]);
                var temp=data[j].split('/');
                if(temp[0]=="S")
                {
                    ci=i;
                    cj=j;
                    p=parseInt(temp[1]);
                }
            }
            lines.push(tarr);
    }
    start_game();
}
    
    
    
    
    
    
  function dis_check()
    {
        document.getElementById('d').innerHTML="";
        for(var i=0; i<lines.length; i++)
        {
            for(var j=0; j<lines[i].length; j++)
                document.getElementById('d').innerHTML+=lines[i][j]+"&#09";
            document.getElementById('d').innerHTML+="<br>";
        }
        document.getElementById('d').innerHTML+=ci+"<br>"+cj+"<br>"+p+"<br>";
    }
    

    
    
    function start_game()
    {
        //dis_check();
        rendergraphics_full(lines);
        document.getElementById('status').innerHTML="You are starting with "+p+" people. Start traversing towards the end.";

    }
    
    
    
    
    
    
    
    function update_people(i, j)
    {
        var t=lines[i][j].split('/');
        if(parseInt(t[1])>0)
        {
            p+=parseInt(t[1]);
            lines[i][j]="P/0";
        }
    }
    function update_cop(i,j)
    {
        play_sound("cop.mp3");
        
        var t=lines[i][j].split('/');
        if(parseFloat(t[1])<1)
        {
            p=parseFloat(p);
            p*=parseFloat(t[1]);
            p=parseInt(p);
        }
        //lines[i][j]="C/1";
    }
    function update_distraction(i,j)
    {
        if(lines[i][j].charAt(1)=='2')
        {
            play_sound("football.mp3");
        }
        else
        {
            play_sound("icecream.mp3");
        }
        var t=lines[i][j].split('/');
        if(parseFloat(t[1])<1)
        {
            p=parseFloat(p);
            p*=parseFloat(t[1]);
            p=parseInt(p);
        }
        //lines[i][j]="D/1";
    }
    
    
    
    
    
    function end_game()
    {
        alert("You have reached your destination. You brought "+p+" people with you.");
                location.reload();
    }
    
    function play_sound(s)
    {
        var snd = new Audio("sounds/"+s); // buffers automatically when created
        snd.play();
        
    }
    
    
    
    function makeMove(e)
    {
            if(timeCount==-1){
	            
	var seconds_left = 20;
var interval = setInterval(function() {
    document.getElementById('timer').innerHTML = --seconds_left;
}, 1000);
	            
	            
                timeCount=setTimeout(resetgame,20000);
                }
        e = e || window.event;
        
        oldesti=oldi;
        oldestj=oldj;
        oldi=ci;
        oldj=cj;
        
        if (e.keyCode == '38') {
        //alert("up arrow");
            
            
            if(ci-1<0 || (lines[ci-1][cj]!="R" && lines[ci-1][cj]!="T" && lines[ci-1][cj]!="S" && lines[ci-1][cj]!="E"))
            {
                //alert("SORRY! You cannot go up from here!");
            }
            else
            {
                ci--;
                
                if(lines[ci][cj]=="E")
                    end_game();
                else
                {
                lines[oldi][oldj]="T";
                lines[ci][cj]="S";
                }
            }
        
    }
    else if (e.keyCode == '40') {
        //alert("down arrow");
        
        if(ci+1>=lines.length || (lines[ci+1][cj]!="R" && lines[ci+1][cj]!="T" && lines[ci+1][cj]!="S" && lines[ci+1][cj]!="E"))
            {
                //alert("SORRY! You cannot go down from here!");
            }
        else
            {
                ci++;
                
                if(lines[ci][cj]=="E")
                    end_game();
                else
                {
                lines[oldi][oldj]="T";
                lines[ci][cj]="S";
                }
            }
    }
    else if (e.keyCode == '37') {
       //alert("left arrow");
        
        if((cj-1)<0 || (lines[ci][cj-1]!="R" && lines[ci][cj-1]!="T" && lines[ci][cj-1]!="S" && lines[ci][cj-1]!="E"))
            {
                //alert("SORRY! You cannot go left from here!");
            }
        else
            {
                cj--;
                
                if(lines[ci][cj]=="E")
                    end_game();
                else
                {
                lines[oldi][oldj]="T";
                lines[ci][cj]="S";
                }
            }
    }
    else if (e.keyCode == '39') {
       //alert("right arrow");
        
        if(cj+1>=lines.length || (lines[ci][cj+1]!="R" && lines[ci][cj+1]!="T" && lines[ci][cj+1]!="S" && lines[ci][cj+1]!="E"))
            {
                //alert("SORRY! You cannot go right from here!");
            }
        else
            {
                cj++;
                if(lines[ci][cj]=="E")
                    end_game();
                else
                {
                lines[oldi][oldj]="T";
                lines[ci][cj]="S";
                }
            }
        
    }
        
        
        
            if(ci>0)
        {
            if(lines[ci-1][cj].charAt(0)=='P')
                update_people(ci-1,cj);
            else if(lines[ci-1][cj].charAt(0)=='D')
                update_distraction(ci-1,cj);
            else if(lines[ci-1][cj].charAt(0)=='C')
                update_cop(ci-1,cj);
        }
        if(ci<(lines.length-1))
        {
            if(lines[ci+1][cj].charAt(0)=='P')
                update_people(ci+1,cj);
            else if(lines[ci+1][cj].charAt(0)=='D')
                update_distraction(ci+1,cj);
            else if(lines[ci+1][cj].charAt(0)=='C')
                update_cop(ci+1,cj);
        }
        if(cj>0)
        {
            if(lines[ci][cj-1].charAt(0)=='P')
                update_people(ci,cj-1);
            else if(lines[ci][cj-1].charAt(0)=='D')
                update_distraction(ci,cj-1);
            else if(lines[ci][cj-1].charAt(0)=='C')
                update_cop(ci,cj-1);
        }
        if(cj<(lines[0].length-1))
        {
            if(lines[ci][cj+1].charAt(0)=='P')
                update_people(ci,cj+1);
            else if(lines[ci][cj+1].charAt(0)=='D')
                update_distraction(ci,cj+1);
            else if(lines[ci][cj+1].charAt(0)=='C')
                update_cop(ci,cj+1);
        }
        //dis_check();   
        rendergraphics_partial(lines, ci, cj, oldi, oldj,oldesti,oldestj);
        document.getElementById('status').innerHTML="You have gathered "+p+" people";
    }
    
    
    
  

    
    
  
    
    
</script>
    </head>
    
    
    
    
    
<body>
    <audio src="sounds/background.wav" autoplay="true" loop="true" preload="auto"></audio >
    
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
			<li><a  href="user_home.php">Home</a></li>
			<li><a href="search_post_byUser.php">Search Posts</a></li>
			<li><a href="../group/group_create.php">Group</a></li>
			<li class="current_page_item"><a>Game</a></li>
		</ul>
	</div>
</div>
<!-- end header -->

    

<div id="wrapper">
	<!-- start page -->
	<div id="page">

		<!-- start content -->
		<div id="content">
			

    
    
    
    
    
    <div id="d">
        <div id="headernew">
<h1>Road Runner</h1>
</div>


<div id="section">
<h2>Status</h2>
<p id="status">
You are starting with 10 people. Start traversing towards the end.
</p>

<span>Time left:&nbsp</span> 
<span id="timer">
20
</span><span>seconds</span>



</div>
        
<div id="section">
<h2>Rules</h2>
<p>

    <li>You have to take the parade to the end <img src="images/finish.jpg"> with the maximum number of people you can gather.</li>
<li>You have 20 seconds to finish the game. The timer will start on your first move.</li>
    <li>Your current location is represented by <img src="images/user.jpg"></li>
    <li>There are houses <img src="images/house.jpg"> containing 10 people and buildings <img src="images/house3.jpg"> containing 20 people. When you pass by a house or a building, all people living there will join the parade.</li>
    <li>There are 3 types of obstacles or distractions along the way.Every time you pass by them, some people will leave the parade.
    
    
    <li> Cops <img src="images/cop.jpg"> : They send 10% of the people away. </li>
    <li> Ice Cream Stand <img src="images/icecream.jpg"> : 5% people leave the parade to eat ice-cream. </li>
    <li> Football Game <img src="images/football.jpg"> : 25% people leave the parade to watch the game. </li>
    
     </li>

</p>
</div>

    
    
    
    </div>
    
		</div>
    <div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end page -->
</div>


</body>
</html>