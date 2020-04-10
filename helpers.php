<?php
include "dbconn.php";

if(isset($_POST['genssh'])){
	
	$user = genuser($_POST['userid']);

}

function genuser($userid){
global $conn;
	$ssh['user'] = $userid . "001";             
        $ssh['pass'] = $ssh['user'] . "111"; 
	
	if((1024 < $userid) && ($userid < 9000)){
		$ssh['port'] = $userid;
	}elseif($userid < 1024){
		//add a few numbers
		$ssh['port'] = $userid . "1";		
	}elseif($userid > 9000){
		$ssh['port'] = $userid
	}
	if($conn){
		$sql = "UPDATE device set `sshuser`=" . $ssh['user'] . ",`sshpass`=" . $ssh['pass'] . ", `sshport`=" . $ssh['port'] . " WHERE user_id=" . $userid;
	        $query = $conn->query($sql);
		return $ssh;
	}
	
}
?>
