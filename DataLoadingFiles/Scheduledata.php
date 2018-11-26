<?php
    echo 'connecting to database....<br>';
	$con = mysqli_connect('127.0.0.1','root','','RAILWAY_CORPORATION');
	if(mysqli_connect_errno()) {
		die('could not connect to database');		
	}
	echo 'database connected....<br>';
    $query = "SELECT * FROM schedule";
    $train_no = array();
    $sunday = array();
    $monday = array();
    $tuesday = array();
    $wednesday = array();
    $thursday = array();
    $friday = array();
    $satday = array();
    $i = 0;
    $res = mysqli_query($con,$query);
    while($row = mysqli_fetch_assoc($res)){
        $train_no[$i] = $row['train_id'];
        $sunday[$i] = $row['sunday'];
        $monday[$i] = $row['monday'];
        $tuesday[$i] = $row['tuesday'];
        $wednesday[$i] = $row['wednesday'];
        $thursday[$i] = $row['thursday'];
        $friday[$i] = $row['friday'];
        $saturday[$i] = $row['saturday'];
        $i++;
    }
    for($i=0;$i<count($train_no);$i++){
        echo 'train = '.$train_no[$i].'<br>';
        echo 'sunday = '.$sunday[$i].'<br>';
        echo 'monday = '.$monday[$i].'<br>';
        echo 'tuesday = '.$tuesday[$i].'<br>';
        echo 'wednesday = '.$wednesday[$i].'<br>';
        echo 'thursday = '.$thursday[$i].'<br>';
        echo 'friday = '.$friday[$i].'<br>';
        echo 'saturday = '.$saturday[$i].'<br><br>';
    }
    
?>