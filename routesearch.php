<?php
	echo 'connecting to database....<br>';
	$con = mysqli_connect('127.0.0.1','root','','RAILWAY_CORPORATION');
	if(mysqli_connect_errno()) {
		die('could not connect to database');		
	}
	echo 'database connected....<br>';
	//result table
	$result = array();
	$r1 = 0;
	$source = $_POST['source'];
	$src = '';
	$destination = $_POST['destination'];
	$date = $_POST['date'];
	echo ''.$source.' to '.$destination.'<br>';
	
	//getting the day from date entered
	$ts = strtotime($date);
	$day = strtolower(date('l',$ts));
	
	echo $day."<br>";
	
	//getting station ids of source and destination
	$q1 = "SELECT station_id FROM stations WHERE station_name = '$source';";
	$q2 = "SELECT station_id FROM stations WHERE station_name = '$destination';";
	$res = mysqli_query($con,$q1);
	if(mysqli_num_rows($res)>0){
		while($row = mysqli_fetch_assoc($res)){
			$src = $row['station_id'];
		}
	}else die("incorrect name of source");
	$res = mysqli_query($con,$q2);
	if(mysqli_num_rows($res)>0){
		while($row = mysqli_fetch_assoc($res)){
			$des = $row['station_id'];
		}
	}else die("incorrect name of destination");
	
	echo $src."  ".$des."<br>";
	
	//getting routes containing both source and destination
	$q1 = "SELECT DISTINCT(route_id) FROM route WHERE station = '$src';";
	$q2 = "SELECT DISTINCT(route_id) FROM route WHERE station = '$des';";
	$srcdata = array();
	$desdata = array();
	///routes containing source in srcdata
	$i = 0;
	$res = mysqli_query($con,$q1);
	if(mysqli_num_rows($res)>0){
		while($row = mysqli_fetch_assoc($res)){
			$srcdata[$i++] = $row['route_id'];
		}
	}else echo "query unsuccessful";
	//routes containing destination in desdata
	$j = 0;
	$res = mysqli_query($con,$q2);
	if(mysqli_num_rows($res)>0){
		while($row = mysqli_fetch_assoc($res)){
			$desdata[$j++] = $row['route_id'];
		}
	}else echo "query unsuccessful";
	$routes = array();
	$t = 0;
	//srcdata inersection desdata = routes
	for($k=0;$k<$i;$k++){
		$check = 0;
		for($l=0;$l<$j;$l++){
			if($srcdata[$k] == $desdata[$l]){
				$check = 1;
				break;
			}
		}
		if($check == 1){
			$routes[$t++] = $srcdata[$k];
		}
	}
	sort($routes);
	
	$route = array();
	$t = 0;
	$sequenceid = array();//store the direction of sequence 1 if route_id is even o if odd
	$sequence = array();// store the sequence of stations
	//find the routes where actual sequence exist
	for($i = 0;$i < count($routes);$i++){
		$q = "SELECT * FROM route WHERE route_id = $routes[$i] ORDER BY station_order;";
		$res = mysqli_query($con,$q);
		$srcorder = 0;$desorder = 0;//to get orders of coming of source and destination
		$check = 0;//checks if the source station has gone
		$temp = 0;//index of array $s
		$s = array();//to get the sequence
		while($row = mysqli_fetch_assoc($res)){
			if($row['station'] == $src){
				$srcorder = $row['station_order'];
				$check = 1;
			}
			if($row['station'] == $des){
				$desorder = $row['station_order'];
				$s[$temp++] = $row['station_order'];
				$check = 0;
			}
			if($check == 1){
				$s[$temp++] = $row['station_order'];
			}
		}
		if($srcorder<$desorder){
			if($routes[$i]%2==0){
				$sequenceid[$t] = 1;
				$sequence[$t] = $s;
			}else {
				$sequenceid[$t] = 0;
				$sequence[$t] = $s;
			}
			$t++;
		}				
	}
	
	// storing routes in manner for querying
	$t = 0;
	for($i = 0;$i < count($routes);$i+=2){
		$route[$t++] = $routes[$i].",".$routes[$i+1];
	}
	echo "<br>";
	if(count($route) == 0){
		echo "no trains between these stations";
		session_start();
		$_SESSION['trains_found'] = false;
		$_SESSION['message'] = "no trains between these stations";
		$_SESSION['source'] = $src;
		$_SESSION['destination'] = $des;
		header("Location: routesearchresult1.php");
	}
	//get all the trains on the founded routes
	$query = "SELECT * FROM trains WHERE route = '$route[0]' ";
	for($i = 1;$i < count($route);$i++){
		$query = $query."OR route = '$route[$i]' ";
	}
	$query = $query.";";
	$trains = array();//stores the information of trains in array in which ith index stores ith train
	$t = 0;
	$res = mysqli_query($con,$query);
	if(mysqli_num_rows($res)>0){
		while($row = mysqli_fetch_assoc($res)){
			$trains[$t] = array();
			$trains[$t]['train_id'] = $row['train_id'];
			$trains[$t]['train_name'] = $row['train_name'];
			$trains[$t]['train_type'] = $row['train_type'];
			$trains[$t]['train_speed'] = $row['speed'];
			$t++;
		}
	}else die("no direct trains found");
	echo "<br>";
	
	//getting the information of costs of trains of different types
	$q = "SELECT * FROM cost;";
	$res = mysqli_query($con,$q);
	$cost = array();
	$i = 0;
	while($row = mysqli_fetch_assoc($res)){
		$cost[$row['train_type']] = $row['cost/km(Rs)'];
		$i++;
	}
	
	//getting the result
	for($i=0;$i<count($trains);$i++){
		$query = "SELECT * FROM schedule WHERE train_id =  ".$trains[$i]['train_id'].";";
		//variables for storing schedule of ith train
		$sunday = "";
		$monday = "";
		$tuesday = "";
		$wednesday = "";
		$thursday = "";
		$friday = "";
		$satday = "";
		$res = mysqli_query($con,$query);
		while($row = mysqli_fetch_assoc($res)){
			//times are seperated by # in database
			$sunday = explode('#',$row['sunday']);array_pop($sunday);
			$monday = explode('#',$row['monday']);array_pop($monday);
			$tuesday = explode('#',$row['tuesday']);array_pop($tuesday);
			$wednesday = explode('#',$row['wednesday']);array_pop($wednesday);
			$thursday = explode('#',$row['thursday']);array_pop($thursday);
			$friday = explode('#',$row['friday']);array_pop($friday);
			$saturday = explode('#',$row['saturday']);array_pop($saturday);
		}
		$offset = 0;
		$r2 = 0;
		$merged = array_merge($sunday,$monday,$tuesday,$wednesday,$thursday,$friday,$saturday,$sunday);
		if($day == 'sunday'){
			$offset = 0;
			$check = 0;
			for($k=0;$k<count($sunday);$k++){
				$d = explode('@',$sunday[$k]);
				if($d[0] == $src){
					if($d[2] == $sequenceid[$i]){
						for($l = $offset+$k+1;$l<count($merged);$l++){
							$e = explode('@',$merged[$l]);
							if($e[0] == $des){
								$result[$r1] = array();
								$result[$r1]['date'] = $date;
								$result[$r1]['train_id'] = $trains[$i]['train_id'];
								$result[$r1]['train_name'] = $trains[$i]['train_name'];
								$result[$r1]['src_name'] = $src;
								$result[$r1]['src_time'] = $d[1];
								$result[$r1]['src_order'] = $d[3];
								$result[$r1]['des_name'] = $des;
								$result[$r1]['des_time'] = $e[1];
								$result[$r1]['des_order'] = $e[3];
								if($l>=$offset+count($sunday)){
									$result[$r1]['time'] = (86400-$d[1])+$e[1];
								}else{
									$result[$r1]['time'] = $e[1]-$d[1];
								}
								$result[$r1]['cost'] = round($result[$r1]['time']/3600*$trains[$i]['train_speed']*$cost[$trains[$i]['train_type']]);
								$r1++;
								$k = $l - $offset;
								break;
							}
						}
					}
				}
			}
		}else if($day == 'monday'){
			$offset = count($sunday);
			$check = 0;
			for($k=0;$k<count($monday);$k++){
				$d = explode('@',$monday[$k]);
				if($d[0] == $src){
					if($d[2] == $sequenceid[$i]){
						for($l = $offset+$k+1;$l<count($merged);$l++){
							$e = explode('@',$merged[$l]);
							if($e[0] == $des){
								$result[$r1] = array();
								$result[$r1]['date'] = $date;
								$result[$r1]['train_id'] = $trains[$i]['train_id'];
								$result[$r1]['train_name'] = $trains[$i]['train_name'];
								$result[$r1]['src_name'] = $src;
								$result[$r1]['src_time'] = $d[1];
								$result[$r1]['src_order'] = $d[3];
								$result[$r1]['des_name'] = $des;
								$result[$r1]['des_time'] = $e[1];
								$result[$r1]['des_order'] = $e[3];
								if($l>=$offset+count($monday)){
									$result[$r1]['time'] = (86400-$d[1])+$e[1];
								}else{
									$result[$r1]['time'] = $e[1]-$d[1];
								}
								$result[$r1]['cost'] = round($result[$r1]['time']/3600*$trains[$i]['train_speed']*$cost[$trains[$i]['train_type']]);
								$r1++;
								$k = $l - $offset;
								break;
							}
						}
					}
				}
			}
			
		}else if($day == 'tuesday'){
			$offset = count($sunday)+count($monday);
			$check = 0;
			for($k=0;$k<count($tuesday);$k++){
				$d = explode('@',$tuesday[$k]);
				if($d[0] == $src){
					if($d[2] == $sequenceid[$i]){
						for($l = $offset+$k+1;$l<count($merged);$l++){
							$e = explode('@',$merged[$l]);
							if($e[0] == $des){
								$result[$r1] = array();
								$result[$r1]['date'] = $date;
								$result[$r1]['train_id'] = $trains[$i]['train_id'];
								$result[$r1]['train_name'] = $trains[$i]['train_name'];
								$result[$r1]['src_name'] = $src;
								$result[$r1]['src_time'] = $d[1];
								$result[$r1]['src_order'] = $d[3];
								$result[$r1]['des_name'] = $des;
								$result[$r1]['des_time'] = $e[1];
								$result[$r1]['des_order'] = $e[3];
								if($l>=$offset+count($tuesday)){
									$result[$r1]['time'] = (86400-$d[1])+$e[1];
								}else{
									$result[$r1]['time'] = $e[1]-$d[1];
								}
								$result[$r1]['cost'] = round($result[$r1]['time']/3600*$trains[$i]['train_speed']*$cost[$trains[$i]['train_type']]);
								$r1++;
								$k = $l - $offset;
								break;
							}
						}
					}
				}
			}
		}else if($day == 'wednesday'){
			$offset = count($sunday)+count($monday)+count($tuesday);
			$check = 0;
			for($k=0;$k<count($wednesday);$k++){
				$d = explode('@',$wednesday[$k]);
				if($d[0] == $src){
					if($d[2] == $sequenceid[$i]){
						for($l = $offset+$k+1;$l<count($merged);$l++){
							$e = explode('@',$merged[$l]);
							if($e[0] == $des){
								$result[$r1] = array();
								$result[$r1]['date'] = $date;
								$result[$r1]['train_id'] = $trains[$i]['train_id'];
								$result[$r1]['train_name'] = $trains[$i]['train_name'];
								$result[$r1]['src_name'] = $src;
								$result[$r1]['src_time'] = $d[1];
								$result[$r1]['src_order'] = $d[3];
								$result[$r1]['des_name'] = $des;
								$result[$r1]['des_time'] = $e[1];
								$result[$r1]['des_order'] = $e[3];
								if($l>=$offset+count($wednesday)){
									$result[$r1]['time'] = (86400-$d[1])+$e[1];
								}else{
									$result[$r1]['time'] = $e[1]-$d[1];
								}
								$result[$r1]['cost'] = round($result[$r1]['time']/3600*$trains[$i]['train_speed']*$cost[$trains[$i]['train_type']]);
								$r1++;
								$k = $l - $offset;
								break;
							}
						}
					}
				}
			}
		}else if($day == 'thursday'){
			$offset = count($sunday)+count($monday)+count($tuesday)+count($wednesday);
			$check = 0;
			for($k=0;$k<count($thursday);$k++){
				$d = explode('@',$thursday[$k]);
				if($d[0] == $src){
					if($d[2] == $sequenceid[$i]){
						for($l = $offset+$k+1;$l<count($merged);$l++){
							$e = explode('@',$merged[$l]);
							if($e[0] == $des){
								$result[$r1]['date'] = $date;
								$result[$r1]['train_id'] = $trains[$i]['train_id'];
								$result[$r1]['train_name'] = $trains[$i]['train_name'];
								$result[$r1]['src_name'] = $src;
								$result[$r1]['src_time'] = $d[1];
								$result[$r1]['src_order'] = $d[3];
								$result[$r1]['des_name'] = $des;
								$result[$r1]['des_time'] = $e[1];
								$result[$r1]['des_order'] = $e[3];
								if($l>=$offset+count($thursday)){
									$result[$r1]['time'] = (86400-$d[1])+$e[1];
								}else{
									$result[$r1]['time'] = $e[1]-$d[1];
								}
								$result[$r1]['cost'] = round($result[$r1]['time']/3600*$trains[$i]['train_speed']*$cost[$trains[$i]['train_type']]);
								$r1++;
								$k = $l - $offset;
								break;
							}
						}
					}
				}
			}
		}else if($day == 'friday'){
			$offset = count($sunday)+count($monday)+count($tuesday)+count($wednesday)+count($thursday);
			$check = 0;
			for($k=0;$k<count($friday);$k++){
				$d = explode('@',$friday[$k]);
				if($d[0] == $src){
					if($d[2] == $sequenceid[$i]){
						for($l = $offset+$k+1;$l<count($merged);$l++){
							$e = explode('@',$merged[$l]);
							if($e[0] == $des){
								$result[$r1] = array();
								$result[$r1]['date'] = $date;
								$result[$r1]['train_id'] = $trains[$i]['train_id'];
								$result[$r1]['train_name'] = $trains[$i]['train_name'];
								$result[$r1]['src_name'] = $src;
								$result[$r1]['src_time'] = $d[1];
								$result[$r1]['src_order'] = $d[3];
								$result[$r1]['des_name'] = $des;
								$result[$r1]['des_time'] = $e[1];
								$result[$r1]['des_order'] = $e[3];
								if($l>=$offset+count($friday)){
									$result[$r1]['time'] = (86400-$d[1])+$e[1];
								}else{
									$result[$r1]['time'] = $e[1]-$d[1];
								}
								$result[$r1]['cost'] = round($result[$r1]['time']/3600*$trains[$i]['train_speed']*$cost[$trains[$i]['train_type']]);
								$r1++;
								$k = $l - $offset;
								break;
							}
						}
					}
				}
			}
		}else if($day == 'saturday'){
			$offset = count($sunday)+count($monday)+count($tuesday)+count($wednesday)+count($thursday)+count($friday);
			$check = 0;
			for($k=0;$k<count($saturday);$k++){
				$d = explode('@',$saturday[$k]);
				if($d[0] == $src){
					if($d[2] == $sequenceid[$i]){
						for($l = $offset+$k+1;$l<count($merged);$l++){
							$e = explode('@',$merged[$l]);
							if($e[0] == $des){
								$result[$r1] = array();
								$result[$r1]['date'] = $date;
								$result[$r1]['train_id'] = $trains[$i]['train_id'];
								$result[$r1]['train_name'] = $trains[$i]['train_name'];
								$result[$r1]['src_name'] = $src;
								$result[$r1]['src_time'] = $d[1];
								$result[$r1]['src_order'] = $d[3];
								$result[$r1]['des_name'] = $des;
								$result[$r1]['des_time'] = $e[1];
								$result[$r1]['des_order'] = $e[3];
								if($l>=$offset+count($saturday)){
									$result[$r1]['time'] = (86400-$d[1])+$e[1];
								}else{
									$result[$r1]['time'] = $e[1]-$d[1];
								}
								$result[$r1]['cost'] = round($result[$r1]['time']/3600*$trains[$i]['train_speed']*$cost[$trains[$i]['train_type']]);
								$r1++;
								$k = $l - $offset;
								break;
							}
						}
					}
				}
			}
		}
	}
	if(count($result) == 0){
		echo "no trains run on this day";
		session_start();
		$_SESSION['trains_found'] = false;
		$_SESSION['message'] = "no trains run on this day";
		$_SESSION['source'] = $src;
		$_SESSION['destination'] = $des;
		header("Location: routesearchresult1.php");
	}
	else{
		print_r($result);
		session_start();
		$_SESSION['trains_found'] = true;
		$_SESSION['resultvar'] = $result;
		header("Location: routesearchresult.php");
	}
?>