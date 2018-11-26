<?php
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
			<div><a href="adminlogin.php"><img class="rounded" src="images/admin1.png" alt="admin logo" style="height: 12%;"></a></div>		
		</div>
		<div class="row" style="margin-left: 2px;">
			<div class="col-3" id="form_division">
				<form method="post" class="form" action="">
						<p style="color: white;font-size: 23px;">From:<br><input type="text" name="source" style="color: gray;"/><br>
						To:<br><input type="text" name="destination" style="color: gray;"/><br>
						date:<br><input type="date" name="date"/ style="color: gray;"><br>
						<input class="btn" type="submit" value="Find Trains" style="color: white;font-size: 23px;"/>		
						</p>
				</form>		
			</div>
			<div class="col-9" id="form_division" >
				<br><br>
					<center><div id="form_division" style=" width: 50%;">
						<form method="post" class="form" action="" style="text-align: left;">
							<p style="color: white;font-size: 23px;">Username:<br><input type="text" name="source" style="color: gray;"/><br>
							Password:<br><input type="password" name="destination" style="color: gray;"/><br>
							<input class="btn" type="submit" value="Login" style="color: white;font-size: 23px;"/>		
							</p>
				</form>
			</div></center>
		</div>
	</body>
</html>
