<?php
session_start();
	if(isset($_SESSION['adminusername'])){
		$name = '<h3 style="color: white;font-weight: bold;">'.$_SESSION['adminusername'].'<h3><form method="post" action="logout.php" ><input type="submit" value="Logout" style="padding: 5px;font-size: 16px;"></input></form>';
	}else $name = '<a href="../login.php"><h3 class="display-5" style="color: white;font-weight: bold;text-decoration: underline;">USER LOGIN<h3></a>';
	$con = mysqli_connect('127.0.0.1','root','','RAILWAY_CORPORATION');
	if(mysqli_connect_errno()) {
		die('could not connect to database');		
	}
	$q = "SELECT MAX(route_id) FROM route;";
    $res = mysqli_query($con,$q);
    $row = mysqli_fetch_assoc($res);
    $max = $row['MAX(route_id)'];
?>

<html>
	<head>
		<link rel="stylesheet" href="../homepage.css"/>
		<link rel="stylesheet" href="../css/bootstrap.min.css"/>
		<script type="text/javascript" src="../js/bootstrap.js"></script>
	</head>
	<body>
		<div class="navbar" id="upper" style="border-radius: 3px;">
			<div class="navbar-brand"><a href="admindashboard.php"><img class="rounded-circle" src="../images/logo.png" alt="railway logo" style="height: 12%;"></a></div>
			<div class="navbar-brand"><h1 class="display-5" style="color: white;font-weight: bold;font-style: italic;text-decoration: underline;">INDIAN RAILWAY CORPORATION<h1></div>
			<div class="navbar-brand"><?php echo $name;?></div>		
		</div>
		<div class="row" style="margin-left: 2px;">
			<div class="col-3" id="form_division">
				<form method="post" class="form" action="../routesearch.php">
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
			<div class="col-9" id="form_division" >
				<table class="table" style="overflow-y:scroll;height:100%;display:block;">
					<tr class="success" style="background: gray;">
                        <th>Route ID</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Route</th>
                    </tr>
                    <?php
                        for($i=1;$i<=$max;$i++){
                            $q = "SELECT * FROM route WHERE route_id=$i ORDER BY station_order;";
                            $res = mysqli_query($con,$q);
                            $route = array();
                            $j=0;
                            while($row = mysqli_fetch_assoc($res)){
                                $route[$j++] = $row['station'];
                            }
                            $src = $route[0];
                            $des = $route[$j-1];
                            $r = "";
                            for($j=0;$j<count($route);$j++){
                                if($j<count($route)-1)
                                    $r = $r."<b>".$route[$j]."</b> -> ";
                                else
                                    $r = $r."<b>".$route[$j]."</b>";
                            }
                            echo "<tr class=\"success\" style=\"background: rgb(204, 204, 255);\">
                                            <td>".$i."</td>
                                            <td>".$src."</td>
                                            <td>".$des."</td>
                                            <td>".$r."</td>
                                            <td></td>
                                    </tr>";
                        }
                    ?>
				</table>
			</div>
		</div>
	</body>
</html>
