<?php
$con = mysqli_connect('127.0.0.1','root','','RAILWAY_CORPORATION');
	if(mysqli_connect_errno()) {
		die('could not connect to database');		
	}
	$date = getdate(time());
    $q = "SELECT * FROM last_update;";
    $res = mysqli_query($con,$q);
    $row = mysqli_fetch_assoc($res);
    $lu = $row['date'];
    $date1 = date_create($date['year']."-".$date['mon']."-".$date['mday']);
    $date2 = date_create($lu);
    $d = date_diff($date2,$date1);
    $d = $d->d;
    $query = "UPDATE `booking` SET day_no=day_no+4 WHERE 1";
	echo $query;
    if(mysqli_query($con,$query)){
        $query = "SELECT * FROM booking;";
        $res = mysqli_query($con,$query);
        while($row = mysqli_fetch_assoc($res)){
            $query = "UPDATE `Availability` SET ".$row['class']."=".$row['class']."-1 WHERE day_no=".$row['day_no']." AND source=".$row['source']." AND destination=".$row['destination']." AND time=".$row['time'].";";
            mysqli_query($con,$query);
        }
    }
    $query = "UPDATE last_update set date='".$date['year']."-".$date['mon']."-".$date['mday']."' WHERE 1;";
    mysqli_query($con,$query);
?>