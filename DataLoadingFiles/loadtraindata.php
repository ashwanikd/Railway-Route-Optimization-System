<?php
	echo 'connecting to database....<br>';
	$con = mysqli_connect('127.0.0.1','root','','RAILWAY_CORPORATION');
	if(mysqli_connect_errno()) {
		die('could not connect to database');		
	}
	echo 'database connected....<br>';
	$myfile = fopen('traindata.txt','r');
	$data = fread($myfile,filesize('traindata.txt'));
	$a = explode(PHP_EOL, $data);
	$n = count($a);
	echo 'loading data....<br>';
	for($i=0;$i<$n;$i++) {
		$temp = explode(' ',$a[$i]);
		$query = "INSERT INTO trains VALUES(".$temp[0].",'".$temp[1]."','".$temp[2]."','".$temp[3]."','".$temp[4]."','".$temp[5]."',".$temp[6].");";
		echo "entry number ".$i." executing....<br>";
		if(mysqli_query($con,$query)){
			echo "entry number ".$i." successful<br>";
		}else echo "entry number ".$i." unsuccessful<br>";
	}
	mysqli_close($con);
	echo '<br><p style="color: green;">successfully loaded the data to route database</p>';
	fclose($myfile);
?>