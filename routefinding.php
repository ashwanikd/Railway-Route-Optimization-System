<?php
    class Route{
        static function routeExist($src,$des){
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
                return false;
            }else{
                return true;
            }
        }
    }
?>