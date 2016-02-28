<?php
session_start();
if(isset($_SESSION['status']))
{
	unset($_SESSION['status']);
	unset($_SESSION['request_vars']);
	if(isset($_SESSION['token']))
		unset($_SESSION['token']);
	if(isset($_SESSION['token_secret']))
		unset($_SESSION['token_secret']);
	session_destroy();
}
header("Location: ../index.php");
?>