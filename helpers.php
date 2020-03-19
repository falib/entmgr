<?php
include "dbconn.php";

if(isset($_POST['genssh'])){
//	echo phpinfo();
	$user = genuser($_POST['userid']);
	$script = system("sudo ./provision.sh " . $user['user'] . " " . $user['pass'],$script,$retvar);
//	echo "hello";
//	echo json_encode("hello");
//	echo $script . " 1";
//	echo $retvar;
	if($retvar == 0){

		echo "Something " . $script;
	}else{
		echo "Error: " .$retvar;
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
		$sql = "UPDATE device set `sshuser`=" . $ssh['user'] . ",`sshpass`=" . $ssh['pass'] . ", `sshport`=" . $ssh['port'] . " WHERE user_id=" . $userid;
	        $query = $conn->query($sql);
		return $ssh;
	}
	
}
?>
