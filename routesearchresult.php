<?php
    $con = mysqli_connect('127.0.0.1','root','','RAILWAY_CORPORATION');
	if(mysqli_connect_errno()) {
		die('could not connect to database');		
	}
    session_start();
?>
<?php
    $query = "SELECT * FROM class_multiplicity;";
    $res = mysqli_query($con,$query);
    $cm = array();
    if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)){
            $cm[$row['class_type']] = $row['multiple'];
        }
    }
	if(isset($_SESSION['username'])){
		$name = '<h3 style="color: white;font-weight: bold;">'.$_SESSION['username'].'<h3><form method="post" action="logout.php" ><input type="submit" value="Logout" style="padding: 5px;font-size: 16px;"></input></form>';
	}else $name = '<a href="login.php"><h3 class="display-5" style="color: white;font-weight: bold;text-decoration: underline;">USER LOGIN<h3></a>';
?>

<html>
	<head>
		<link rel="stylesheet" href="homepage.css"/>
		<link rel="stylesheet" href="css/bootstrap.min.css"/>
        <script src="Jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="navbar" id="upper" style="border-radius: 3px;">
			<div class="navbar-brand"><a href="index.php"><img class="rounded-circle" src="images/logo.png" alt="railway logo" style="height: 12%;"></a></div>
			<div class="navbar-brand"><h1 class="display-5" style="color: white;font-weight: bold;font-style: italic;text-decoration: underline;">INDIAN RAILWAY CORPORATION<h1></div>
			<div class="navbar-brand"><?php echo $name;?></div>		
		</div>
		<div class="row" style="margin-left: 2px;">
			<div class="col-3" id="form_division">
				<form method="post" class="form" action="routesearch.php">
						<p style="color: white;font-size: 23px;">From:<br><input list="stations" id="liststyle" name="source" style="color: gray; width: 100%;padding: 3px 15px;margin: 10px 5px;display: inline-block;border: 1px solid #ccc;border-radius: 4px;box-sizing: border-box;"><datalist id="stations">
    																						<option value="agartala">
																							<option value="aizawl">
    																						<option value="bengaluru">
    																						<option value="bhopal">
    																						<option value="bhubneshwar">
    																						<option value="chandigar">
    																						<option value="chennai">
    																						<option value="dehradoon">
    																						<option value="delhi">
    																						<option value="dispur">
    																						<option value="gandhinagar">
    																						<option value="gangtok">
    																						<option value="goa">
    																						<option value="hyderabad">
    																						<option value="itanagar">
    																						<option value="jaipur">
    																						<option value="kohima">
    																						<option value="kolkata">
    																						<option value="lucknow">
    																						<option value="mumbai">
    																						<option value="patna">
    																						<option value="raipur">
    																						<option value="ranchi">
    																						<option value="shilong">
    																						<option value="shimla">
    																						<option value="srinagar">
    																						<option value="thiruvanathapuram">
  																							</datalist><br>
						To:<br><input id="liststyle" list="stations" name="destination" style="color: gray; width: 100%;padding: 3px 15px;margin: 10px 5px;display: inline-block;border: 1px solid #ccc;border-radius: 4px;box-sizing: border-box;"><br>
						date:<br><input type="date" name="date" style="color: gray;"><br>
						<input class="btn" type="submit" value="Find Trains" style="color: white;font-size: 23px;"/>		
						</p>
				</form>		
			</div>
			<div class="col-9 container" id="form_division" >
				<?php
                    if(isset($_SESSION['trains_found'])){
                        if($_SESSION['trains_found']){
                            echo "<table class=\"table\">";
                                $result = $_SESSION['resultvar'];
                                $result['class_multplicity'] = $cm;
                                $_SESSION['resultvar'] = $result;
                                echo "<tr class=\"active\" style=\"background: gray;\">";
                                        echo "<th>Train_no</th>";
                                        echo "<th>Train_Name</th>";
                                        echo "<th>Departs</th>";
                                        echo "<th>Arrives</th>";
                                        echo "<th>Duration</th>";
                                        echo "<th>Check Availability</th>";
                                    echo "</tr>";
                                for($i=0;$i<count($result)-1;$i++){
                                    echo "<tr class=\"success\" style=\"background: rgb(204, 204, 255);\">";
                                        echo "<td>".$result[$i]['train_id']."</td>";
                                        echo "<td>".$result[$i]['train_name']."</td>";
                                        $d = $result[$i]['src_time'];
                                        $h = floor($d/3600);
                                        $m = floor(($d-($h*3600))/60);
                                        echo "<td>".$h.":".$m."</td>";
                                        $d = $result[$i]['des_time'];
                                        $h = floor($d/3600);
                                        $m = floor(($d-($h*3600))/60);
                                        echo "<td>".$h.":".$m."</td>";
                                        $d = $result[$i]['time'];
                                        $h = floor($d/3600);
                                        $m = floor(($d-($h*3600))/60);                                            
                                        echo "<td>".$h.":".$m."</td>";
                                        //button
                                        echo "<td><input class=\"btn\" style=\"padding: 0px;margin: 0px;\" type=\"button\" value=\"Check Availability\" data-toggle=\"modal\" data-target=\"#myModal$i\"></input></td>";
                                        echo "</tr>";                                   
                                }
                                echo "</table>";
                                $result = $_SESSION['resultvar'];
                                for($i=0;$i<count($result)-1;$i++){
                                        // searcing for availabilty
                                        $query = "SELECT * FROM last_update;";
                                        $res = mysqli_query($con,$query);
                                        $row = mysqli_fetch_assoc($res);
                                        $current_date = date_create($row['date']);
                                        $date = date_create($result[$i]['date']);
                                        $day_no = date_diff($date,$current_date);
                                        $day_no = $day_no->d;
                                        $query = "SELECT * FROM Availability WHERE train_id=".$result[$i]['train_id']." AND source=".$result[$i]['src_order']." AND destination=".$result[$i]['des_order']."
                                                    AND time=".$result[$i]['src_time']." AND day_no=".$day_no.";";
                                        //echo $query;
                                        $res = mysqli_query($con,$query);
                                        $row = mysqli_fetch_assoc($res);
                                            $general = 0;$sleeper = 0;$AC1 = 0;$AC2 = 0;$AC3 = 0;
                                            if($row['general']>0){
                                                $general = $row['general'];
                                            }else {
                                                $general = 'waiting';
                                            }
                                            if($row['sleeper']>0){
                                                $sleeper = $row['sleeper'];
                                            }else {
                                                $sleeper = 'waiting';
                                            }
                                            if($row['AC1']>0){
                                                $AC1 = $row['AC1'];
                                            }else {
                                                $AC1 = 'waiting';
                                            }
                                            if($row['AC2']>0){
                                                $AC2 = $row['AC2'];
                                            }else {
                                                $AC2 = 'waiting';
                                            }
                                            if($row['AC3']>0){
                                                $AC3 = $row['AC3'];
                                            }else {
                                                $AC3 = 'waiting';
                                            }
                                        //modal
                                        echo "<div id=\"myModal$i\" class=\"modal fade\" role=\"dialog\">
                                                <div class=\"modal-dialog\">
                                              
                                                    <!-- Modal content-->
                                                    <div class=\"modal-content\">
                                                        <div class=\"modal-header\">
                                                            <h4 class=\"modal-title\">".$result[$i]['train_id']." : ".$result[$i]['train_name']."</h4>
                                                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                                                        </div>
                                                        <div class=\"modal-body\">
                                                        <form action=\"bookticket.php\" method=\"post\">
                                                           <table class=\"table\">
                                                                <tr class=\"active\" style=\"background: gray;\">
                                                                    <th>General</th>
                                                                    <th>Sleeper</th>
                                                                    <th>AC1</th>
                                                                    <th>AC2</th>
                                                                    <th>AC3</th>
                                                                </tr>
                                                                <tr class=\"success\" style=\"background: rgb(204, 204, 255);\">
                                                                    <td>Available<br>$general</td>
                                                                    <td>Available<br>$sleeper</td>
                                                                    <td>Available<br>$AC1</td>
                                                                    <td>Available<br>$AC2</td>
                                                                    <td>Available<br>$AC3</td>
                                                                </tr>
                                                                <tr class=\"success\" style=\"background: rgb(204, 204, 255);\">
                                                                    <td>Rs ".round($cm['general']*$result[$i]['cost'])."</td>
                                                                    <td>Rs ".round($cm['sleeper']*$result[$i]['cost'])."</td>
                                                                    <td>Rs ".round($cm['AC1']*$result[$i]['cost'])."</td>
                                                                    <td>Rs ".round($cm['AC2']*$result[$i]['cost'])."</td>
                                                                    <td>Rs ".round($cm['AC3']*$result[$i]['cost'])."</td>
                                                                </tr>
                                                                <tr class=\"success\" style=\"background: rgb(204, 204, 255);\">
                                                                    <td><input class=\"btn\" style=\"padding: 0px;margin: 0px;\" type=\"submit\" name=\"general#$i\" value=\"Book\" ></input></td>
                                                                    <td><input class=\"btn\" style=\"padding: 0px;margin: 0px;\" type=\"submit\" name=\"sleeper#$i\" value=\"Book\" ></input></td>
                                                                    <td><input class=\"btn\" style=\"padding: 0px;margin: 0px;\" type=\"submit\" name=\"AC1#$i\" value=\"Book\" ></input></td>
                                                                    <td><input class=\"btn\" style=\"padding: 0px;margin: 0px;\" type=\"submit\" name=\"AC2#$i\" value=\"Book\" ></input></td>
                                                                    <td><input class=\"btn\" style=\"padding: 0px;margin: 0px;\" type=\"submit\" name=\"AC3#$i\" value=\"Book\" ></input></td>
                                                                </tr>
                                                           </table>
                                                           </form>
                                                        </div>
                                                        <div class=\"modal-footer\">
                                                            <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>";
                                    }
                        }else{
                            echo "<br><br><br><br><h1 style=\"color: white;font-weight: bold;\">".$_SESSION['message']."</h1><br>";
                        }
                    }
                ?>
		    </div>
	</body>
</html>
