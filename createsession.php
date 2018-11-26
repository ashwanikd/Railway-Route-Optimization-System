<?php
echo 'connecting to database....<br>';
	$con = mysqli_connect('127.0.0.1','root','','RAILWAY_CORPORATION');
	if(mysqli_connect_errno()) {
		die('could not connect to database');		
	}
	echo 'database connected....<br>';
    $uname = $_POST['username'];
    $password = $_POST['password'];
    $password = md5($password);
    $q = "SELECT * FROM user WHERE user_name = '$uname' AND password = '$password' ;";
    echo $q;
    $res = mysqli_query($con,$q);
    if(mysqli_num_rows($res)>0){
        session_start();
        $_SESSION['username'] = $uname;
        setcookie('active',true);
        header('Location: userdashboard.php');
    }else {
        echo 'yes';
        setcookie('active',false);
        header('Location: login.php');
    }
?>