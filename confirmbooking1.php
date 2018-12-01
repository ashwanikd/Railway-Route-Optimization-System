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
    $status = "confirm";
    $seat_no = array();
    for($i=0;$i<$result['no_of_passengers'];$i++){
        $seat_no[$i] = 1;
    }
    $pnr = array();
    for($i=0;$i<$result['no_of_passengers'];$i++){
        $pnr[$i] = 0;
    }
    
    //updating tables
    $query = "SELECT ".$result['class']." FROM Availability WHERE source=".$result['src_order']." AND destination=".$result['des_order']." AND day_no=".$day_no." AND time=".$result['src_time'].";";
    $res = mysqli_query($con,$query);
    $row = mysqli_fetch_assoc($res);
    if($row[$result['class']]>=$result['no_of_passengers']){
        // booking is confirmed
        for($i=0;$i<$result['no_of_passengers'];$i++){
            //generating pnr
            $timestamp = time();
            $p = $timestamp.$result['train_id'].$result['passenger_data'][$i]['name'].$result['date'];
            $pnr[$i] = md5($p);
            
            $seats = array();
            $query = "SELECT seat_no FROM booking WHERE source>=".$result['src_order']." AND destination<=".$result['des_order']." AND day_no=".$day_no." AND time>=".$result['src_time'].";";
            $res = mysqli_query($con,$query);
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
            // updating booking table
            $query = "INSERT INTO booking VALUES (".$result['train_id'].",'".$pnr[$i]."',".$day_no.",".$result['src_order'].",".$result['des_order'].",".$result['time'].",'".$result['class']."',".$seat_no[$i].");";
            
            if(!mysqli_query($con,$query)){
                die("unable to book ticket");
            }
            // updating availabiliy table
            $query = "UPDATE Availability SET ".$result['class']."=".$result['class']."-1 WHERE day_no=".$day_no." AND source>=".$result['src_order']." AND destination<=".$result['des_order']." AND time>=".$result['src_time'].";";
            if(!mysqli_query($con,$query)){
                die("unable to book ticket..");
            }
            // updating PassengerNameRecord table
            $query = "INSERT INTO PassengerNameRecord VALUES ('".$pnr[$i]."',".$result['train_id'].",'".$result['date']."',".$result['src_time'].",'".$result['passenger_data'][$i]['name']."','".$result['class']."','".$status."')";
            echo $query.'<br>';
            if(!mysqli_query($con,$query)){
                echo mysqli_error($con);
                die("unable to book ticket...");
            }
        }
    }else {
        //booking status is waiting
        $status = "waiting";
        for($i=0;$i<$result['no_of_passengers'];$i++){
            //generating pnr
            $timestamp = time();
            $p = $timestamp.$result['train_id'].$result['passenger_data'][$i]['name'].$result['date'];
            $pnr[$i] = md5($p);
            
            $seats = array();
            $query = "SELECT seat_no FROM booking WHERE source>=".$result['src_order']." AND destination<=".$result['des_order']." AND day_no=".$day_no." AND time>=".$result['src_time'].";";
            $res = mysqli_query($con,$query);
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
            // updating booking table
            $query = "INSERT INTO waiting_queue VALUES ('".$pnr[$i]."',".$_SESSION['username'].",".$result['train_id'].",".$result['src_order'].",".$result['des_order'].",".$day_no.",".$result['time'].",'".$result['class']."');";
            if(!mysqli_query($con,$query)){
                die("unable to book ticket");
            }
            // updating availabiliy table
            $query = "UPDATE Availability SET ".$result['class']."=".$result['class']."-1 WHERE day_no=".$day_no." AND source>=".$result['src_order']." AND destination<=".$result['des_order']." AND time>=".$result['src_time'].";";
            if(!mysqli_query($con,$query)){
                die("unable to book ticket..");
            }
            // updating PassengerNameRecord table
            $query = "INSERT INTO PassengerNameRecord VALUES ('".$pnr[$i]."','".$result['train_id']."',".$result['date'].",".$result['src_time'].",'".$result['passenger_data'][$i]['name']."','".$result['class']."','".$status."')";
            if(!mysqli_query($con,$query)){
                echo mysqli_error();
                die("unable to book ticket...");
            }
        }
    }
    
    //writing data to xml files
    $dom = new DOMDocument();
    $dom->load('users/'.$_SESSION['username'].'/BookingHistory.xml');
    $root = $dom->documentElement;
    for($i=0;$i<$result['no_of_passengers'];$i++){
        $PNR = $dom->createElement('pnr',$pnr[$i]);
        $train = $dom->createElement('train_id',$result['train_id']);
        $trainname = $dom->createElement('train_name',$result['train_name']);
        $date = $dom->createElement('date',$result['date']);
        $src = $dom->createElement('source',$result['src_name']);
        $destination = $dom->createElement('destination',$result['des_name']);
        $departure = $dom->createElement('departure',$result['src_time']);
        $arrival = $dom->createElement('arrival',$result['des_time']);
        $duration = $dom->createElement('duration',$result['time']);
        $class = $dom->createElement('class',$result['class']);
        echo $status;
        $s = $dom->createElement('status',$status);
        $name = $dom->createElement('name',$result['passenger_data'][$i]['name']);
        $gender = $dom->createElement('gender',$result['passenger_data'][$i]['gender']);
        $age = $dom->createElement('age',$result['passenger_data'][$i]['age']);
        $country = $dom->createElement('country',$result['passenger_data'][$i]['country']);
        $ticket = $dom->createElement('ticket');
        $ticket->appendChild($PNR);
        $ticket->appendChild($train);
        $ticket->appendChild($trainname);
        $ticket->appendChild($date);
        $ticket->appendChild($class);
        $ticket->appendChild($name);
        $ticket->appendChild($gender);
        $ticket->appendChild($age);
        $ticket->appendChild($country);
        $ticket->appendChild($s);
        $ticket->appendChild($src);
        $ticket->appendChild($destination);
        $ticket->appendChild($departure);
        $ticket->appendChild($arrival);
        $ticket->appendChild($duration);
        if($status=='confirm'){
            $seatno = $dom->createElement('seat_no',$seat_no[$i]);
            $ticket->appendChild($seatno);
        }
        $root->appendChild($ticket);
    }
    $dom->save('users/'.$_SESSION['username'].'/BookingHistory.xml');
    echo "Successfully Booked";
    $result['pnr'] = $pnr;
    $result['seat_no'] = $seat_no;
    $_SESSION['resultvar'] = $result;
    header('Location: booked.php');
?>