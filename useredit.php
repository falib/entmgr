<?php
include "dbconn.php";
include "globals.php";

if(isset($_POST['submit'])){
	if($conn){
		if(isset($_GET['userid'])){
			$pw_sql = "UPDATE users SET password = ? WHERE user_id = " . $_GET['userid'];
			$pq_query = $conn->prepare($pw_sql);
			if($pq_query->execute(md5($_POST['password']))){
				echo $_POST['userid'] . " was updated successfully";
			}
		}else
			echo "Something went wrong or no ID selected";
	}else
		echo "Something went wrong with the database connection";
}else
	echo "<h6 class='bg-success text-center'> Please enter the new password and click the Apply button </h6>";

if(isset($_GET['userid'])){
	if($conn){
		$sql = "SELECT * from users WHERE user_id=" . $_GET['userid'];
		$query = $conn->query($sql);
		$results = $query->fetch(PDO::FETCH_ASSOC);
			
	}else
		echo "Something went wrong with the database connection";
}else
	echo "There are no Users selected";



?>

<form id="useredit" action="useredit.php?userid=<?php echo $_GET['userid'];?>"  method="POST" style="width: 40%;margin-left: 25%;margin-top: 10%;">
<div class="form-group">
	<label for="userid">User ID </label>
	<input id="userid" type="text" name="userid" class="form-control" value="<?php echo $results['user_id']; ?>" disabled/>
</div>
<div class="form-group">
        <label for="Password">Password </label>
        <input id="password" type="text" name="password" class="form-control" />
</div>
<div class="text-center">
	<input name="submit" value="Apply" type="Submit" class="btn btn-dark"/>
</div>
</form>
