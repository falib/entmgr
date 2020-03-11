<?php
include "dbconn.php";

if(isset($_POST['genssh'])){
	$user = genuser($_POST['userid']);
	$script = shell_exec("sudo ./provision.sh " . $user['user'] . " " . $user['pass']);
//	echo "hello";
//	echo json_encode("hello");
//	echo $script . " 1";
	if($script){

		echo $script;
	}

}

function genuser($userid){
global $conn;
	$ssh['user'] = $userid . "001";             
        $ssh['pass'] = $ssh['user'] . "111"; 
	
	if((1024 < $userid) && ($userid < 9000)){
		$ssh['port'] = $userid;
	}elseif($userid < 1024){
		//add a few numbers
		$ssh['port'] = $userid;		
	}
	if($conn){
		$sql = "UPDATE device set `sshuser`=" . $ssh['user'] . ",`sshpass`=" . $ssh['pass'] . " WHERE user_id=" . $userid;
	        $query = $conn->query($sql);
		return $ssh;
	}
	
}
?>
