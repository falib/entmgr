<?php
include "dbconn.php";
include "manage.php";

if(!$_SESSION['user']){
    if($_SERVER['PHP_SELF'] !== "/index.php"){
        header("Location: index.php");
    }
}else{

if(isset($_POST['submit-device'])){
	if(isset($_POST['user']) && isset($_POST['points_quota'])){
		$values1['userid'] = $_POST['user'];
		$values1['points_quota'] = $_POST['points_quota'];
		if($conn){
			$insert1 = "INSERT INTO device (user_id,points_quota) VALUES(:userid,:points_quota)";
			$add1 = $conn->prepare($insert1);
			if($add1->execute($values1)==TRUE){
				echo "Device for " . $values['userid'] . " added successfully";
			}
		}
	}	
}

if(isset($_POST['userid']) && isset($_POST['password'])){
	$values['userid'] = $_POST['userid'];
	$values['password'] = md5($_POST['password']);
	if($conn){
		$insert = "INSERT INTO users (user_id,password) VALUES(:userid,:password)";
		$add = $conn->prepare($insert);
		if($add->execute($values)==TRUE){
			echo $values['userid'] . " added successfully";
		}
	}
}
echo "<h2> Welcome Admin " . $_SESSION['user'] .  "</h2>";

$options = "";
if($conn){
	$sql = "SELECT user_id from users";
	$query = $conn->query($sql);
	$results = $query->fetchAll(PDO::FETCH_ASSOC);
}else
	echo "Some Error has occured";
foreach($results as $result){
	$options .= "<option value='" . $result['user_id'] . "'>" . $result['user_id'] . "</option>";
}

?>
<body>
<div id="new_user" style="border: black solid 2px; width: 60%;padding: 2%">
<h3> New User </h3><br>
<form id='add_user' method='POST' action='manage.php'>
	<label for="userid"> UserID </label>
	<input type="text" name="userid" id="userid" />
	<label for="password"> Password </label>
	<input type="password" name="password" id="password" />
	<input type="Submit" name="submit-user" id="Submit" value="Add User" />
</form>
</div>
<br><br>
<div id="new_device" style="border: black solid 2px; width: 60%;padding: 2%">
<h3> New Device </h3><br>
<form id='add_device' method='POST' action='manage.php'>
        <label for="user"> UserID </label>
        <select id="user" name="user">
	<?php echo $options; ?>
	</select>
        <label for="points_quota"> Points Quota </label>
        <input type="text" name="points_quota" id="points_quota" />
        <input type="Submit" name="submit-device" id="Submit" value="Add Device" />
</form>
</div>

</body>
</html>

<?php } ?>
