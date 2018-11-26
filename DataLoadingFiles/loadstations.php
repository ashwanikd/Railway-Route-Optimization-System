<?php
	echo 'connecting to database....<br>';
	$con = mysqli_connect('127.0.0.1','root','','RAILWAY_CORPORATION');
	if(mysqli_connect_errno()) {
		die('could not connect to database');		
	}
	echo 'database connected....<br>';
	$myfile = fopen('stationdata.txt','r');
	$data = fread($myfile,filesize('stationdata.txt'));
	$a = explode(PHP_EOL, $data);
	$n = count($a);
	echo 'loading data....<br>';
	for($i=0;$i<$n;$i++) {
		$temp = explode(' ',$a[$i]);
		$query = "INSERT INTO stations VALUES('".$temp[0]."','".$temp[1]."','".$temp[2]."');";
		echo "entry number ".$i." executing....<br>";		
		if(mysqli_query($con,$query)){
			echo "entry number ".$i." successful<br>";
		}else echo "entry number ".$i." unsuccessful<br>";
	}
	mysqli_close($con);
	echo '<br><p style="color: green;">successfully loaded the data to stations database</p>';
	fclose($myfile);
?>