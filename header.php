<!DOCTYPE html>
<head>
	<title>Header</title>
<style>

*{
	font-family: Arial;
}

.logo{
	width: 400px;
	margin: 58px;
}

.content{
	width: 100%;
	display: flex;
	justify-content: space-between;
}

.adminlogo{
	padding-top: 10px;
}

.admin{
	margin: -20px 0px 0px 95px;
	padding: 10px 15px 20px 15px;
	border: 3px solid black;
	border-radius: 25px;
	max-width: 20%;
	max-height: 55px;
	display: inline-flex;
}

.txt{
	margin: 20px 0px 0px 20px;
	font-size: 32px;
}

.vl {
  border-left: 8px solid black;
  height: 34px;
  justify-content: right;
  margin: 18px 18px 0px 15px;
}

.records{
	background: rgb(16,46,74);
	color: white;
	border: 3px;
	border-radius: 25px;
	margin-top: -20px;
	width: 35%;
}

.records h1{
	padding-top: 8px;
	text-align: center;
}

</style>
<meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
	<div class="container">
		<img class="logo" src="img/logo.png">
		<div class="content">
			<div class="admin">
				<img class="adminlogo" src="img/admin.png">
				<span class="txt">Administrator</span>
				<div class="vl"></div>
			</div>
			<div class="records">
				<?php 
					$head = "OVERVIEW";
					if (isset($_GET['head'])){
						$head = $_GET['head'];
					}
					?>
					<h1><?=$head?></h1>
	</div>
</body>