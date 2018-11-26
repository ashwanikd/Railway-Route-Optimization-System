<?php
    session_start();
    $name = "";
    $class = '';
    $result = array();
    if(isset($_SESSION['username'])){
        $name = $name = '<h3 style="color: white;font-weight: bold;">'.$_SESSION['username'].'<h3><form method="post" action="logout.php" ><input type="submit" value="Logout" style="padding: 5px;font-size: 16px;"></input></form>';;
        $result = $_SESSION['resultvar'];
        $class = '';
        $index = 0;
        for($i=0;$i<count($result)-1;$i++){
            if(isset($_POST['general#'.$i])){
                $class = 'general';
                $index = $i;
                $result['class'] = $class;
                $result['index'] = $index;                
                $result[$index]['cost'] = $result[$index]['cost']*$result['class_multplicity'][$class];
                $_SESSION['resultvar'] = $result;
                unset($_POST['general#'.$i]);
            }else if(isset($_POST['sleeper#'.$i])){
                $class = 'sleeper';
                $index = $i;
                $result['class'] = $class;
                $result['index'] = $index;
                $result[$index]['cost'] = $result[$index]['cost']*$result['class_multplicity'][$class];
                $_SESSION['resultvar'] = $result;
                unset($_POST['sleeper#'.$i]);
            }else if(isset($_POST['AC1#'.$i])){
                $class = 'AC1';
                $index = $i;
                $result['class'] = $class;
                $result['index'] = $index;
                $result[$index]['cost'] = $result[$index]['cost']*$result['class_multplicity'][$class];
                $_SESSION['resultvar'] = $result;
                unset($_POST['AC1#'.$i]);
            }else if(isset($_POST['AC2#'.$i])){
                $class = 'AC2';
                $index = $i;
                $result['class'] = $class;
                $result['index'] = $index;
                $result[$index]['cost'] = $result[$index]['cost']*$result['class_multplicity'][$class];
                $_SESSION['resultvar'] = $result;
                unset($_POST['AC2#'.$i]);
            }else if(isset($_POST['AC3#'.$i])){
                $class = 'AC3';
                $index = $i;
                $result['class'] = $class;
                $result['index'] = $index;
                $result[$index]['cost'] = $result[$index]['cost']*$result['class_multplicity'][$class];
                $_SESSION['resultvar'] = $result;
                unset($_POST['AC3#'.$i]);
            }
        }
        $class = $result['class'];
        $index = $result['index'];
    }else die('please login first');
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
				<h3 style="color: white;font-weight: bold;">Your Ticket<h3>
                <hr style="border-color: white;border-width: 3px;">
                <?php
                    $d = $result[$index]['src_time'];
                    $hs = floor($d/3600);
                    $ms = floor(($d-($hs*3600))/60);
                    $d = $result[$index]['des_time'];
                    $hd = floor($d/3600);
                    $md = floor(($d-($hd*3600))/60);
                    echo "
                        <table class=\"table\">
                        <tr class=\"success\" style=\"background: rgb(204, 204, 255);\">
                        <td><b>Train-no: </b>".$result[$index]['train_id']."</td>
                        <td><b>Train-name: </b>".$result[$index]['train_name']."</td>
                        <td><b>Journey-date: </b>".$result[$index]['date']."</td>
                        </tr>
                        <tr class=\"success\" style=\"background: rgb(204, 204, 255);\">
                        <td><b>From: </b>".$result[$index]['src_name']."</td>
                        <td><b>To: </b>".$result[$index]['des_name']."</td>
                        <td><b>Class: </b>".$class."</td>
                        </tr>
                        <tr class=\"success\" style=\"background: rgb(204, 204, 255);\">
                        <td><b>Departure: </b>".$hs.":".$ms."</td>
                        <td><b>Arrival: </b>".$hd.":".$md."</td>
                        <td><b>Fare: </b>".$result[$index]['cost']."</td>
                        </tr>
                        </table>
                        ";
                    if(isset($_POST['no_of_passengers'])){
                        $result['no_of_passengers'] = $_POST['no_of_passengers'];
                        $_SESSION['resultvar'] = $result;
                        echo "<form method=\"post\" action=\"confirmbooking.php\">
                            <datalist id=\"gender\">
                            <option value=\"Male\">
							<option value=\"Female\">
    						<option value=\"Transgender\">
                            </datalist>
                            <table class=\"table\">";
                        echo "
                            <tr class=\"active\" style=\"background: gray;\">
                            <th>S.no</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Country</th>
                            </tr>
                            ";
                        $n = $_POST['no_of_passengers'];
                        for($i=1;$i<$n+1;$i++){
                            echo "
                                <tr class=\"success\" style=\"background: rgb(204, 204, 255);\">
                                <td>$i</td>
                                <td><input type=\"text\"  name=\"name#$i\" /></td>
                                <td><input type=\"number\"  name=\"age#$i\" /></td>
                                <td><input list=\"gender\" id=\"liststyle\"  name=\"gender#$i\" /></td>
                                <td><input type=\"text\"  name=\"country#$i\" /></td>
                                </tr>
                                ";
                        }
                        echo "</table>
                        <input type=\"submit\" class=\"btn\" value=\"Submit\" />
                        </form>";
                    }else echo "
                            <form method=\"post\" action=\"bookticket.php\">
                            <span style=\"color: white;\">Number of Passengers: </span><input type=\"number\" class=\"form-control\" name=\"no_of_passengers\" />
                            <input type=\"submit\" class=\"btn\" value=\"Submit\" />
                            </form>
                               ";
                ?>
		    </div>
	</body>
</html>