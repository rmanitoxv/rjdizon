<?php 
	$num = $_GET['num'];
?>
<!DOCTYPE html>
<head>
	<title>Order Information Header</title>
	<link rel="stylesheet" href="orderinfoheader.css">
</head>
<body>
	<div class="container">
		<img class="logo" src="../img/logo.png">
		<div class="content">
			<div class="admin">
				<img class="adminlogo" src="../img/admin.png">
				<span class="txt">Staff</span>
				<div class="vl"></div>
			</div>
			<div class="order">
				<h1>ORDERS</h1>
				<div class="ordernumber">
					<a href="#" onclick="history.go(-1)" style="text-decoration:None"><h2>ORDER#<?=$num?></h2></a>
				</div>
			</div>
	</div>
</body>