<?php
    $con = mysqli_connect('127.0.0.1','root','','RAILWAY_CORPORATION');
	if(mysqli_connect_errno()) {
		die('could not connect to database');		
	}
    session_start();
    if(!isset($_SESSION['resultvar'])){
        die("please choose trains first");
    }
    if(!isset($_SESSION['username'])){
        die("please login first");
    }
    $result = $_SESSION['resultvar'];
    $passengers = $result['passenger_data'];
    if(count($passengers)<=0){
        die("Select passengers first");
    }
    //getting day number
    $query = "SELECT * FROM last_update;";
    $res = mysqli_query($con,$query);
    $row = mysqli_fetch_assoc($res);
    $current_date = date_create($row['date']);
    $date = date_create($result['date']);
    $day_no = date_diff($date,$current_date);
    $day_no = $day_no->d;
    $status = "Confirm";
    $seat_no = array();
    for($i=0;$i<$result['no_of_passengers'];$i++){
        $seat_no[$i] = 1;
    }
    $pnr = array();
    for($i=0;$i<$result['no_of_passengers'];$i++){
        $pnr[$i] = 0;
    }
    
    //updating Availability table
    $query = "SELECT ".$result['class']." FROM Availability WHERE source=".$result['src_order']." AND destination=".$result['des_order']." AND day_no=".$day_no." AND time=".$result['src_time'].";";
    $res = mysqli_query($con,$query);
    $row = mysqli_fetch_assoc($res);
    if($row[$result['class']]>=$result['no_of_passengers']){
        // booking is confirmed
        for($i=0;$i<$result['no_of_passengers'];$i++){
            $query = "UPDATE Availability SET ".$result['class']."=".$result['class']."-1 WHERE day_no=".$day_no." AND source>=".$result['src_order']." AND destination<=".$result['des_order']." AND time>=".$result['src_time'].";";
            //generating pnr
            $timestamp = time();
            $p = $timestamp.$result['train_id'].$result['passenger_data'][$i]['name'].$result['date'];
            $pnr[$i] = md5($p);
            
            $seats = array();
            $query = "SELECT seat_no FROM booking WHERE source>=".$result['src_order']." AND destination<=".$result['des_order']." AND day_no=".$day_no." AND time>=".$result['src_time'].";";
            $res = mysqli_query($query);
            if(mysqli_num_rows($res)>0){
                $t = 0;
                while($row = mysqli_fetch_assoc($res)){
                    $seats[$t++] = $row['seat_no']; 
                }
                sort($seats);
                $sn=0;
                for($j=1;$j<count($seats)+1;$j++){
                    if($seats[$j-1]!=$j){
                        $sn=$j;
                        break;
                    }
                    $sn=$j+1;
                }
                $seat_no[$i] = $sn;
            }
            $query = "INSERT INTO booking VALUES (".$result['train_id'].",'".$pnr[$i]."',".$day_no.",".$result['src_order'].",".$result['des_order'].",".$result['time'].",'".$result['class']."',".$seat_no[$i].");";
            if(!mysqli_query($query)){
                die("unable to book ticket");
            }
            $query = "UPDATE Availability SET ".$result['class']."=".$result['class']."-1 WHERE day_no=".$day_no." AND source>=".$result['src_order']." AND destination<=".$result['des_order']." AND time>=".$result['src_time'].";";
            if(!mysqli_query($query)){
                die("unable to book ticket...");
            }
        }
    }else {
        //booking status is waiting
        $status = "waiting";
    }
    
    //writing data to xml files
    $myfile = fopen('./users/'.$_SESSION['username'].'/BookingHistory.xml','r');
	$data = fread($myfile,filesize('./users/'.$_SESSION['username'].'/BookingHistory.xml'));
    fclose($myfile);
    $xml = simplexml_load_string($data);
    
?>