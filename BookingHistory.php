<?php
    session_start();
    $xml = simplexml_load_file('users/'.$_SESSION['username'].'/BookingHistory.xml');
?>
<?php
	if(isset($_SESSION['username'])){
		$name = '<h3 style="color: white;font-weight: bold;">'.$_SESSION['username'].'<h3><form method="post" action="logout.php" ><input type="submit" value="Logout" style="padding: 5px;font-size: 16px;"></input></form>';
	}else $name = '<a href="login.php"><h3 class="display-5" style="color: white;font-weight: bold;text-decoration: underline;">USER LOGIN<h3></a>';
?>

<html>
	<head>
		<link rel="stylesheet" href="homepage.css"/>
		<link rel="stylesheet" href="css/bootstrap.min.css"/>
		<script type="text/javascript" src="js/bootstrap.js"></script>
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
			<div class="col-9" id="form_division" >
                <?php
                    echo "<table class=\"table\">";
                    echo "<tr class=\"active\" style=\"background: gray;\">";
                    echo "<th>PNR</th>";
                    echo "<th>Date</th>";
                    echo "<th>Train_No</th>";
                    echo "<th>Departs</th>";
                    echo "<th>Arrives</th>";
                    echo "<th>Duration</th>";
                    echo "<th>Class</th>";
                    echo "<th>Seat_no</th>";
                    echo "<th>status</th>";
                    echo "</tr>";
                    $a = $xml->ticket;
                    foreach($a as $ticket){
                        echo "<tr class=\"success\" style=\"background: rgb(204, 204, 255);\">";
                        echo "<td>".$ticket->pnr."</td>";
                        echo "<td>".$ticket->date."</td>";
                        echo "<td>".$ticket->train_id."</td>";
                        $d = $ticket->departure;
                        $h = floor($d/3600);
                        $m = floor(($d-($h*3600))/60);
                        echo "<td>".$h.":".$m."</td>";
                        $d = $ticket->arrival;
                        $h = floor($d/3600);
                        $m = floor(($d-($h*3600))/60);
                        echo "<td>".$h.":".$m."</td>";
                        $d = $ticket->duration;
                        $h = floor($d/3600);
                        $m = floor(($d-($h*3600))/60);
                        echo "<td>".$h.":".$m."</td>";
                        echo "<td>".$ticket->class."</td>";
                        echo "<td>".$ticket->seat_no."</td>";
                        echo "<td>".$ticket->status."</td>";
                        echo "</tr>";                                   
                    }
                    echo "</table>";
                    echo "</table>
                            <form action=\"userdashboard.php\" method=\"post\">
                            <input type=\"submit\" class=\"btn\" value=\"Back\" />
                            </form>";
                ?>
			</div>	
		</div>
	</body>
</html>
