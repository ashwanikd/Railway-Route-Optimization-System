<?php
	$con = mysqli_connect('127.0.0.1','root','','RAILWAY_CORPORATION');
	if(mysqli_connect_errno()) {
		die('could not connect to database');		
	}
	$stations = array();
	$graph = array();
	$query = 'SELECT * FROM stations;';
	$res = mysqli_query($con,$query);
	$i = 0;
	if(mysqli_num_rows($res)>0){
		while($row = mysqli_fetch_assoc($res)) {
			$stations[$i++] = $row['station_id']; 
		}
	}
	for($i=0;$i<sizeof($stations);$i++) {
		$graph[$stations[$i]] = new Node($stations[$i]);
	}
	$query = 'SELECT * FROM distances;';
	$res  = mysqli_query($con,$query);
	if(mysqli_num_rows($res)>0){
		while($row = mysqli_fetch_assoc($res)) {
			$graph[$row['location1']]->connection[$row['location2']] = new listNode($row['location2'],$row['distance']);
			$graph[$row['location2']]->connection[$row['location1']] = new listNode($row['location1'],$row['distance']);
		}
	}
	$station_id = array();
	$query = 'SELECT * FROM stations;';
	$res = mysqli_query($con,$query);
	if(mysqli_num_rows($res)>0){
		while($row = mysqli_fetch_assoc($res)) {
			$station_id[$row['station_name']] = $row['station_id']; 
		}
	}
?>