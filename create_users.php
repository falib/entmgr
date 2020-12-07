<?php
include "/opt/bitnami/apache2/htdocs/super/dbconn.php"; 

$filepath = "/home/bitnami/scripts/provision.sh";

if($conn){
	$sql = "Select * from device where createssh = FALSE";
	$dev_sql = $conn->query($sql);
	$results = $dev_sql->fetchAll(PDO::FETCH_ASSOC);

//	var_dump($results);exit();	
	foreach($results as $result){
		$output = exec("sudo $filepath " . $result['sshuser'] . "  " . $result['sshpass']);
		syslog(LOG_INFO,$output);
		$update = explode(",",$output);
		if($update == TRUE){
			$sql2 = "UPDATE device SET `createssh` = 1 WHERE `sshuser`=" . $result['sshuser'];
			$update_sql = $conn->prepare($sql2);
			$update_sql->execute();
			
		}
	}
}else
	echo "There was a problem connecting to the DB";

?>
