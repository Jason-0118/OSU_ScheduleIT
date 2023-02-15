<?php
session_start();

ob_start();
$header_path = $footer_path = $_SERVER['DOCUMENT_ROOT'];
$header_path .= "/OSU_ScheduleIT/header.php";
$footer_path .= "/OSU_ScheduleIT/footer.php";
include_once($header_path);
?>

<!DOCTYPE html>
<html>
<head>
	<title>My Profile Page</title>
	<style>
		body {
			background-color: #f2f2f2;
		}
		.profile {
			background-color: white;
			border-radius: 10px;
			padding: 20px;
			box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.2);
			max-width: 600px;
			margin: 40px auto;
			text-align: center;
		}
		.profile h1 {
			color: #ff6600;
			font-size: 36px;
			margin-bottom: 20px;
		}
		.profile img {
			border-radius: 50%;
			width: 200px;
			height: 200px;
			margin-bottom: 20px;
		}
		.profile p {
			font-size: 18px;
			line-height: 1.5;
			margin-bottom: 20px;
		}
		.dashboard {
			background-color: white;
			border-radius: 10px;
			padding: 20px;
			box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.2);
			max-width: 600px;
			margin: 20px auto 0 auto;
			text-align: center;
		}
		.dashboard h2 {
			color: #ff6600;
			font-size: 24px;
			margin-bottom: 20px;
		}
		.dashboard label {
			font-size: 18px;
			margin-right: 20px;
		}
		.dashboard input[type="radio"] {
			margin-right: 10px;
		}
	</style>
</head>
<body>
	<div class="profile">
		<img src="img/profile_pic.jpg" alt="Profile Picture">
		<h1>John Doe</h1>
        <p>ONID: johndoe</p>
		<p>Student ID: 974-293-8993</p>
		<p>Address: 123 Main St, Anytown USA</p>
		<p>Phone: 555-1234</p>
		<p>Mobile: 555-5678</p>
		<p>Email: jdoe@oregonstate.edu</p>
		<p>Campus Affiliation: E-Campus</p>
	</div>

	<div class="dashboard">
		<h2>Dashboard Settings</h2>
		<label for="theme-light">
			<input type="radio" id="theme-light" name="theme" value="light" checked>
			Light Theme
		</label>
		<label for="theme-dark">
			<input type="radio" id="theme-dark" name="theme" value="dark">
			Dark Theme
		</label>
	</div>

    

</body>
</html>

<?php include_once($footer_path); ?>