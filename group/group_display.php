<?php
		session_start();
		$name=$_SESSION['grpname'];
		$con=mysqli_connect("localhost","<db username>","<db password>","<db name>");
		$query="SELECT * FROM  tgroupposts WHERE groupname ='$name' ORDER BY tstamp DESC";				
		$result=mysqli_query($con,$query);
		$num_rows=mysqli_num_rows($result);
		if($num_rows==0)
		{
			echo '<h2 class="title">No posts available in this group.</h2>
				<p class="byline"></p>';
		}			
		else
		{
			echo '<h2 class="title">Recent posts in the group</h2>
				<p class="byline"></p>
				<div class="entry" id="entry">';
			while(($row=mysqli_fetch_array($result)))
			{
				//DISPLAYING THE POST.
				echo '<div class="boxlist">
					<div class="ngo_list">
					<div>
					<h2>@'.$row['userscreenname'].'</h2>
					</div>
					<div>
						<span>'.$row['tstamp'].'</span>
					</div>
					<div>
						<span>'.$row['message'].'</span>
					</div>
					</div>
					</div>';
			}
			echo '</div>';	
		}
		mysqli_close($con);
?>