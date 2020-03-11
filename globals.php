<?php
if(!$_SESSION['user']){
	header("Location: index.php");	
}
?>
<html>
<head>
<link rel='stylesheet' href='bootstrap/css/bootstrap.min.css'>
<script src='assets/jquery-3.4.1.min.js'></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src='bootstrap/js/bootstrap.min.js'></script>

</head>
<body>
<div class="">
	<div class="navbar bg-warning">
	<h6 class="float-right text-white"> EntManager Admin </h6>
	<nav class="float-left">
	<a href="manage.php" class="text-white px-3 mr-5 text-decoration-none">Manage</a>
	<button class="btn btn-outline-primary"> <a href="logout.php" class="text-white px-3 text-decoration-none">Logout</a></button>
	</nav>
	</div>
</div>
