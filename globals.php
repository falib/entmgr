<?php
if(!$_SESSION['user']){
	header("Location: index.php");	
}
?>
<html>
<head>
<link rel='stylesheet' href='bootstrap/css/bootstrap.min.css'>
<script src='bootstrap/js/bootstrap.min.js'></script>

</head>
<body>
<div class="">
	<div class="navbar">
	<nav class="">
	<a href="manage.php">Manage</a>
	<a href="logout.php">Logout</a>
	</nav>
	</div>
</div>
