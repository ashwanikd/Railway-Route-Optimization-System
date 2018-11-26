<?php
    require('Automaton.php');
    $con = mysqli_connect('127.0.0.1','root','','RAILWAY_CORPORATION');
	if(mysqli_connect_errno()) {
		die('could not connect to database');		
	}
    $date = getdate(time());
    $query = "SELECT * FROM train_profile;";
    $res = mysqli_query($con,$query);
    if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)){
            $train_id = $row['train_id'];
            $query = "SELECT * FROM schedule WHERE train_id='$train_id';";
            $res1 = mysqli_query($con,$query);
            if(mysqli_num_rows($res1)>0){
                while($row1 = mysqli_fetch_assoc($res1)){
                    $sunday = explode('#',$row1['sunday']);array_pop($sunday);
                    $monday = explode('#',$row1['monday']);array_pop($monday);
                    $tuesday = explode('#',$row1['tuesday']);array_pop($tuesday);
                    $wednesday = explode('#',$row1['wednesday']);array_pop($wednesday);
                    $thursday = explode('#',$row1['thursday']);array_pop($thursday);
                    $friday = explode('#',$row1['friday']);array_pop($friday);
                    $saturday = explode('#',$row1['saturday']);array_pop($saturday);
                    $d = explode('@',$sunday[0]);$src = $d[0];
                    $merged = array_merge($sunday,$monday,$tuesday,$wednesday,$thursday,$friday,$saturday);
                    $time = 0;
                    $des = "";
                    for($j=1;$j<count($merged)-1;$j++){
                        $d = explode('@',$merged[$j]);
                        $d1 = explode('@',$merged[$j-1]);
                        $d2 = explode('@',$merged[$j+1]);
                        if($d1[0] == $d2[0]){
                            $des = $d[0];
                            break;
                        }
                    }
                    $sequence = array();
                    $temp = 0;
                    for($j=0;$j<count($merged);$j++){
                        $d = explode('@',$merged[$j]);
                        $sequence[$temp++] = $d[0];
                        if($d[0] == $des){
                            break;
                        }
                    }
                    $automaton = new FiniteAutomaton($sequence);
                    //$date = getdate(time());
                    
                    for($i=1;$i<=40;$i++){
                        $date = getdate(time()+(86400*$i));
                        
                        if($date['weekday']=='Sunday'){
                            $offset = 0;
                            for($j=0;$j<count($sunday);$j++){
                                $s = explode('@',$sunday[$j]);
                                $automaton->setCurrentState($s[0]);
                                    $time = $s[1];
                                
                                for($k=$offset+$j+1;$k<count($merged);$k++){
                                    $d = explode('@',$merged[$k]);
                                    $query = "INSERT INTO Availability Values($train_id,".$s[3].",".$d[3].",$i,'".strtolower($date['weekday'])."',$time,".$row['general'].",".$row['sleeper'].",".$row['AC1'].",".$row['AC2'].",".$row['AC3'].");";
                                    echo $query.'<br>';
                                    if(mysqli_query($con,$query)){
                                        echo "$train_id : successful<br>";
                                    }
                                    if(!$automaton->transition($d[0])){
                                        break;
                                    }
                                }
                            }
                        }else if($date['weekday']=='Monday'){
                            $offset = count($sunday);
                            for($j=0;$j<count($monday);$j++){
                                $s = explode('@',$monday[$j]);
                                $automaton->setCurrentState($s[0]);
                                    $time = $s[1];
                                
                                for($k=$offset+$j+1;$k<count($merged);$k++){
                                    $d = explode('@',$merged[$k]);
                                                        
                                    $query = "INSERT INTO Availability Values($train_id,".$s[3].",".$d[3].",$i,'".strtolower($date['weekday'])."',$time,".$row['general'].",".$row['sleeper'].",".$row['AC1'].",".$row['AC2'].",".$row['AC3'].");";
                                    echo $query.'<br>';
                                    if(mysqli_query($con,$query)){
                                        echo "$train_id : successful<br>";
                                    }
                                    if(!$automaton->transition($d[0])){
                                        break;
                                    }
                                }
                            }
                        }else if($date['weekday']=='Tuesday'){
                            $offset = count($sunday)+count($monday);
                            for($j=0;$j<count($tuesday);$j++){
                                $s = explode('@',$tuesday[$j]);
                                $automaton->setCurrentState($s[0]);
                                    $time = $s[1];
                                
                                for($k=$offset+$j+1;$k<count($merged);$k++){
                                    $d = explode('@',$merged[$k]);
                                    
                                                        
                                    $query = "INSERT INTO Availability Values($train_id,".$s[3].",".$d[3].",$i,'".strtolower($date['weekday'])."',$time,".$row['general'].",".$row['sleeper'].",".$row['AC1'].",".$row['AC2'].",".$row['AC3'].");";
                                    echo $query.'<br>';
                                    if(mysqli_query($con,$query)){
                                        echo "$train_id : successful<br>";
                                    }
                                    if(!$automaton->transition($d[0])){
                                        break;
                                    }
                                }
                            }
                        }else if($date['weekday']=='Wednesday'){
                            $offset = count($sunday)+count($monday)+count($tuesday);
                            for($j=0;$j<count($wednesday);$j++){
                                $s = explode('@',$wednesday[$j]);
                                $automaton->setCurrentState($s[0]);
                                    $time = $s[1];
                                
                                for($k=$offset+$j+1;$k<count($merged);$k++){
                                    $d = explode('@',$merged[$k]);
                                                        
                                    $query = "INSERT INTO Availability Values($train_id,".$s[3].",".$d[3].",$i,'".strtolower($date['weekday'])."',$time,".$row['general'].",".$row['sleeper'].",".$row['AC1'].",".$row['AC2'].",".$row['AC3'].");";
                                    echo $query.'<br>';
                                    if(mysqli_query($con,$query)){
                                        echo "$train_id : successful<br>";
                                    }
                                    if(!$automaton->transition($d[0])){
                                        break;
                                    }
                                }
                            }
                        }else if($date['weekday']=='Thursday'){
                            $offset = count($sunday)+count($monday)+count($tuesday)+count($wednesday);
                            for($j=0;$j<count($thursday);$j++){
                                $s = explode('@',$thursday[$j]);
                                $automaton->setCurrentState($s[0]);
                                    $time = $s[1];
                                
                                for($k=$offset+$j+1;$k<count($merged);$k++){
                                    $d = explode('@',$merged[$k]);
                                                        
                                    $query = "INSERT INTO Availability Values($train_id,".$s[3].",".$d[3].",$i,'".strtolower($date['weekday'])."',$time,".$row['general'].",".$row['sleeper'].",".$row['AC1'].",".$row['AC2'].",".$row['AC3'].");";
                                    echo $query.'<br>';
                                    if(mysqli_query($con,$query)){
                                        echo "$train_id : successful<br>";
                                    }
                                    if(!$automaton->transition($d[0])){
                                        break;
                                    }
                                }
                            }
                        }else if($date['weekday']=='Friday'){
                            $offset = count($sunday)+count($monday)+count($tuesday)+count($wednesday)+count($thursday);
                            for($j=0;$j<count($friday);$j++){
                                $s = explode('@',$friday[$j]);
                                $automaton->setCurrentState($s[0]);
                                    $time = $s[1];
                                
                                for($k=$offset+$j+1;$k<count($merged);$k++){
                                    $d = explode('@',$merged[$k]);
                                                       
                                    $query = "INSERT INTO Availability Values($train_id,".$s[3].",".$d[3].",$i,'".strtolower($date['weekday'])."',$time,".$row['general'].",".$row['sleeper'].",".$row['AC1'].",".$row['AC2'].",".$row['AC3'].");";
                                    echo $query.'<br>';
                                    if(mysqli_query($con,$query)){
                                        echo "$train_id : successful<br>";
                                    }
                                    if(!$automaton->transition($d[0])){
                                        break;
                                    }
                                }
                            }
                        }else if($date['weekday']=='Saturday'){
                            $offset = count($sunday)+count($monday)+count($tuesday)+count($wednesday)+count($thursday)+count($friday);
                            for($j=0;$j<count($saturday);$j++){
                                $s = explode('@',$saturday[$j]);
                                $automaton->setCurrentState($s[0]);
                                    $time = $s[1];
                                for($k=$offset+$j+1;$k<count($merged);$k++){
                                    $d = explode('@',$merged[$k]);
                                     
                                    $query = "INSERT INTO Availability Values($train_id,".$s[3].",".$d[3].",$i,'".strtolower($date['weekday'])."',$time,".$row['general'].",".$row['sleeper'].",".$row['AC1'].",".$row['AC2'].",".$row['AC3'].");";
                                    echo $query."<br>";
                                    if(mysqli_query($con,$query)){
                                        echo "$train_id : successful<br>";
                                    }
                                    if(!$automaton->transition($d[0])){
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
?>