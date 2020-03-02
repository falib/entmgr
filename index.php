<?php
include "dbconn.php";

if($conn){
	if(isset($_POST['username'],$_POST['password'])){
		$user = $_POST['username'];
		$pass = $_POST['password'];
		$query = $conn->query("SELECT * from users WHERE user_id ='$user' and password=md5('$pass') LIMIT 1");
		$auth = $query->fetch();
		if($auth){
			echo "hi $user";	
			$_SESSION['user'] = $user;
			header("Location: manage.php");
			
		}else
			$message = "Please check your username or password and try again";

	}else{
		$message = "Please Login";
		if($_SESSION['user']){
			header("Location: manage.php");
		}
	}
}else{
	$message = "Could not connect to the database";
}

?>
<body>
<h3> <?php echo $message; ?></h3>
<form id='login' method='POST' action='index.php'>
	<label for='username'>Username</label>
	<input id='username' name='username' type='text'/>
	<label for='password'>Password</label>
	<input id='password' name='password' type='password' />
	<input id='Submit' name='Submit' type='Submit' value='Login' />
</body>
</html>
