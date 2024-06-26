<?php
include "dbconn.php";
include "globals.php";

if(isset($_POST['submit'])){
        if($conn){
                if(isset($_GET['device_id'])){
                        $dev_sql = "UPDATE device SET `user_id` = :user_id,`points_quota` = :points_quota, `period` = :period,`status` = :status WHERE `id` = :id";
                        $dev_query = $conn->prepare($dev_sql);
                        $values['user_id'] = $_POST['userid'];
			$values['points_quota'] = $_POST['points_quota'];
			$values['period'] = $_POST['period'];
			$values['status'] = ($_POST['status'] == "on") ? 1 : 0;
                        $values['id'] = $_GET['device_id'];
                        if($dev_query->execute($values)){
                                echo $_GET['device_id'] . " was updated successfully";
                        }else
                                echo "Error";
                }else
                        echo "Something went wrong or no ID selected";
        }else
                echo "Something went wrong with the database connection";
}else
        echo "<div class='bg-info text-center jumbotron'> <h5> Please enter your device changes and click the Apply button </h5></div>";

if(isset($_GET['device_id'])){
        if($conn){
                $sql = "SELECT * from device WHERE id=" . $_GET['device_id'];
                $query = $conn->query($sql);
                $results = $query->fetch(PDO::FETCH_ASSOC);

        }else
                echo "Something went wrong with the database connection";
}else
        echo "There are no Users selected";



?>
<div class="text-center mx-auto">
	<button id="genssh" class="btn btn-outline-success mr-5"> Generate SSH Credentials </button> 
	<button id="resetssh" class="btn btn-outline-danger mr-5"> Reset SSH Password </button>

</div>
<form id="deviceedit" action="deviceedit.php?device_id=<?php echo $_GET['device_id'];?>"  method="POST" style="width: 40%; margin-top: 5%;" class="mx-auto">
<div class="form-group">
        <label for="id">ID </label>
        <input id="id" type="text" name="id" class="form-control" value="<?php echo $results['id']; ?>" disabled/>
</div>
<div class="form-group">
        <label for="userid">User ID </label>
        <input id="userid" type="text" name="userid" class="form-control" value="<?php echo $results['user_id']; ?>" />
</div>
<div class="form-group">
        <label for="points_quota">Points Quota </label>
        <input id="points_quota" type="text" name="points_quota" class="form-control" value="<?php echo $results['points_quota']; ?>" />
</div>
<div class="form-group">
        <label for="period">Period </label>
        <input id="period" type="date" name="period" class="form-control" value="<?php echo $results['period']; ?>" />
</div>
<div class="form-group">
        <label for="sshport">SSH Port </label>
        <input id="sshport" type="text" name="sshport" class="form-control" value="<?php echo $results['sshport']; ?>" disabled />
</div>
<div class="form-group">
        <label for="sshuser">SSH User </label>
        <input id="sshuser" type="text" name="sshuser" class="form-control" value="<?php echo $results['sshuser']; ?>" disabled />
</div>
<div class="form-group">
        <label for="sshpass">SSH Pasword </label>
        <input id="sshpass" type="text" name="sshpass" class="form-control" value="<?php echo $results['sshpass']; ?>" disabled />
</div>

<div class="form-group form-check">
	<input type="checkbox" name="status" id="status" class="form-check-input" <?php echo ($results['status'] == 1) ? "checked" : ""; ?>>
        <label for="status" class="form-check-label"> Enabled </label>
</div>


<div class="text-center">
        <input name="submit" value="Apply" type="Submit" class="btn btn-dark"/>
</div>
</form>
<script>
$(document).ready(function() {
	var userid = $('#userid').val();
	$('#genssh').click(function(){
		$.ajax({
		url: "helpers.php", 
		type:"POST",
		data: {userid:userid,genssh:"genssh"},
		success: function(result){
			console.log(result)
   			 alert(result)
  			},
		error: function (request, status, error) {
       		alert(request.responseText);
    		}})
	});	
})

</script>
