<?php
include "dbconn.php";
include "globals.php";

if(!$_SESSION['user']){
    if($_SERVER['PHP_SELF'] !== "/index.php"){
        header("Location: index.php");
    }
}else{

if(isset($_POST['submit-device'])){
	if(isset($_POST['user']) && isset($_POST['points_quota'])){
		$values1['userid'] = $_POST['user'];
		$values1['points_quota'] = $_POST['points_quota'];
		$values1['period'] = $_POST['period'];
		$values1['status'] = ($_POST['status'] == "on") ? 1 : 0;
		$values1['createssh'] = 0; //defaults to false, set true by polling script in cron
		if($conn){
			$insert1 = "INSERT INTO device (user_id,points_quota,period,status,createssh) VALUES(:userid,:points_quota,:period,:status,:createssh)";
			$add1 = $conn->prepare($insert1);
			if($add1->execute($values1)==TRUE){
				echo "Device for " . $values['userid'] . " added successfully";
			}
		}
	}	
}

if(isset($_POST['userid']) && isset($_POST['password'])){
	$values['userid'] = $_POST['userid'];
	$values['password'] = password_hash($_POST['password'],PASSWORD_DEFAULT);
	if($conn){
		$insert = "INSERT INTO users (user_id,password) VALUES(:userid,:password)";
		$add = $conn->prepare($insert);
		if($add->execute($values)==TRUE){
			echo $values['userid'] . " added successfully";
		}
	}
}
echo "<div class='jumbotron text-center bg-info'><h4> Welcome <span class='text-uppercase'>" . $_SESSION['user'] .  "</span></h4></div>";

$options = "";
if($conn){
//	$sql = "SELECT * from users";
	$sql = "SELECT user_id from users where user_id not in (select user_id from device)";
	$query = $conn->query($sql);
	$results = $query->fetchAll(PDO::FETCH_ASSOC);
}else
	echo "Some Error has occured";
foreach($results as $result){
	$options .= "<option value='" . $result['user_id'] . "'>" . $result['user_id'] . "</option>";
}

?>
<div class="row mt-2">
<div id="show_devices" style="padding:2%" class="col-md-7 card">
<h3 class="card-header"> Devices </h3>
<table class="table card-body">
<thead><tr><th> Device ID </th><th>UserID</th><th>Points Quota</th><th> Mac Address</th><th> Period </th><th>Status</th><th>SSH Port</th><th>SSH User</th><th>SSH Password</th><th> Edit</th></tr></thead>
<?php
if($conn){
        $dev_sql = "SELECT * from device";
        $dev_query = $conn->query($dev_sql);
        $devices = $dev_query->fetchAll(PDO::FETCH_ASSOC);
        foreach($devices as $device){
                echo "<tr><td>" . $device['id'] . "</td>" .
		"<td>" . $device['user_id'] . "</td>" .
		"<td> " . $device['points_quota'] . "</td>" .
		"<td>" . $device['mac_address'] . "</td>" .
		"<td> " . $device['period'] . "</td>" .
		"<td> " . $device['status'] . "</td>" .
		"<td> " . $device['sshport'] . "</td>" .
		"<td> " . $device['sshuser'] . "</td>" .
		"<td> " . $device['sshpass'] . "</td>" .
		"<td><button id='" . $device['id'] . "' class='btn btn-light'> <a href='deviceedit.php?device_id=" . $device['id'] . "'>Edit</a> </button></td>" .
		"</tr>";
        }
}
?>
</table>
</div>

<div id="show_user" style="padding:2%" class="offset-md-1 col-md-3 card mr-1">
<h3 class="card-header"> Users </h3>
<table class="table card-body">
<thead><tr><th>UserID</th><th>Edit</th></tr></thead>
<?php
if($conn){
	$usr_sql = "SELECT * from users";
	$usr_query = $conn->query($usr_sql);
	$users = $usr_query->fetchAll(PDO::FETCH_ASSOC);
	foreach($users as $user){
		echo "<tr><td>" . $user['user_id'] . "</td><td><button id='" . $user['user_id'] . "' class='btn btn-light'> <a href='useredit.php?userid=" . $user['user_id'] . "'>Edit</a> </button></td></tr>";
	}
}
?>
</table>
</div>
</div><!-- end row-->
<div class="row mt-5">
<div id="new_user" style="padding: 2%" class="card col-md-5">
<h3 class="card-header"> New User </h3><br>
<form id='add_user' method='POST' action='manage.php' class="card-body">
	<div class="form-group">
		<label for="userid"> UserID </label>
		<input type="text" name="userid" id="userid" class="form-control" />
	</div>
	<div class="form-group">
		<label for="password"> Password </label>
		<input type="password" name="password" id="password" class="form-control"/>
	</div>
	<div class="text-center">
		<input type="Submit" name="submit-user" id="Submit" value="Add User" class="btn btn-primary"/>
	</div>
</form>
</div>
<br><br>
<div id="new_device" style="padding: 2%" class="offset-md-1 card col-md-5">
<h3 class="card-header"> New Device </h3><br>
<form id='add_device' method='POST' action='manage.php' class='card-body'>
        <div class="form-group">
		<label for="user"> UserID </label>
        	<select id="user" name="user" class="form-control">
		<?php echo $options; ?>
		</select>
	</div>
	<div class="form-group">
        	<label for="points_quota"> Points Quota </label>
        	<input type="text" name="points_quota" id="points_quota" class="form-control"/>
	</div>
        <div class="form-group">
		<label for="period"> Period </label>
        	<input type="date" name="period" id="period" class="form-control" />
        </div>
	<div class="form-group form-check">
		<input type="checkbox" name="status" id="status" class="form-check-input">
		<label for="status" class="form-check-label"> Enabled </label>
	</div>
	<div class="text-center">
		<input type="Submit" name="submit-device" id="Submit" value="Add Device" class="btn btn-primary" />
	</div>
</form>
</div>
</div><!-- end row -->
</body>
</html>

<?php } ?>
