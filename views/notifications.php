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
	<title>Notifications</title>
	<style>
		body {
			background-color: #f2f2f2;
		}
		.notifications {
			background-color: white;
			border-radius: 10px;
			padding: 20px;
			box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.2);
			max-width: 600px;
			margin: 20px auto;
			text-align: center;
		}
		.notifications h1 {
			color: #ff6600;
			font-size: 36px;
			margin-bottom: 20px;
		}
		.notifications h2 {
			font-size: 24px;
			margin-bottom: 20px;
		}
		.notifications ul {
			list-style-type: none;
			padding: 0;
			margin: 0;
			text-align: left;
			margin-bottom: 20px;
		}
		.notifications li {
			display: flex;
			align-items: center;
			padding: 10px;
			border-radius: 5px;
			margin-bottom: 10px;
		}
		.notifications li .icon {
			margin-right: 10px;
			width: 24px;
			height: 24px;
		}
		.notifications li .icon-1 {
			background-color: #ff6600;
			border-radius: 50%;
		}
		.notifications li .icon-2 {
			background-color: #0099cc;
			border-radius: 50%;
		}
		.notifications li .icon-3 {
			background-color: #666666;
			border-radius: 50%;
		}
		.notifications li .message {
			flex-grow: 1;
			font-size: 18px;
			line-height: 1.5;
			color: #333333;
		}
		.notifications li .date {
			font-size: 14px;
			color: #666666;
		}
	</style>
</head>
<body>
	<div class="notifications">
		<h1>Notifications</h1>

		<h2>Most Important</h2>
		<ul>
			<li>
				<div class="icon icon-1"></div>
				<div class="message">Please update your timezone preferences.</div>
				<div class="date">2 days ago</div>
			</li>
			<li>
				<div class="icon icon-1"></div>
				<div class="message">Your meeting with a project partner is coming up soon!</div>
				<div class="date">1 week ago</div>
			</li>
		</ul>

		<h2>This Week</h2>
		<ul>
			<li>
				<div class="icon icon-2"></div>
				<div class="message">Scheduled your TA meeting for next Wednesday.</div>
				<div class="date">2 days ago</div>
			</li>
			<li>
				<div class="icon icon-2"></div>
				<div class="message">Your appointment is scheduled for Friday at 2pm.</div>
				<div class="date">4 days ago</div>
			</li>
		</ul>

        <h2>Past Notifications</h2>
        <ul>
            <li>
                <div class="icon icon-3"></div>
                <div class="message">You have signed up for TA meeting slots for the next three weeks.</div>
                <div class="date">2 weeks ago</div>
            </li>
            <li>
                <div class="icon icon-3"></div>
                <div class="message">Created your Schedule-It Account.</div>
                <div class="date">3 weeks ago</div>
            </li>
        </ul>
    </div> 
</body>

</html>


<?php include_once($footer_path); ?>