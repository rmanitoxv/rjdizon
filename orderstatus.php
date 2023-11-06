<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = mysqli_query($con, "SELECT * FROM user WHERE userID = '$id'");
        $result =  mysqli_fetch_assoc($online);
        if ($result['isStaff'] == 1){
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: staff.php");
        }
        $id = $_GET['id'];
        $num = $_GET['num'];
        $query = mysqli_query($con, "SELECT * FROM projectgroup WHERE pgID=$id");
        $result = mysqli_fetch_assoc($query);
        $i2 = $i3 = $i4 = $i5 = "";
        if ($result['pgStatus'] == "Proofing"){
            $i2 = "disabled";
        }
        else if ($result['pgStatus'] == "Mass Production"){
            $i2 = $i3 = "disabled";
        }
        else if ($result['pgStatus'] == "Delivery"){
            $i2 = $i3 = $i4 = "disabled" ;
        }
        if (isset($_POST['ctp'])){
            mysqli_query($con, "UPDATE projectgroup SET pgStatus='CTP Processing', pgHandler='Operator' WHERE pgID=$id");
            $prog = "CTP Processing";
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: orderprogress.php?id=$id&num=$num&prog=$prog");
        }   
        if (isset($_POST['proof'])){
            mysqli_query($con, "UPDATE projectgroup SET pgStatus='Proofing', pgHandler='Operator' WHERE pgID=$id");
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: orderprogress.php?id=$id&num=$num&prog=Proofing");
        }
        if (isset($_POST['mp'])){
            mysqli_query($con, "UPDATE projectgroup SET pgStatus='Mass Production', pgHandler='Operator' WHERE pgID=$id");
            $prog="Mass Production";
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: orderprogress.php?id=$id&num=$num&prog=$prog");
        }
        if (isset($_POST['deliver'])){
            mysqli_query($con, "UPDATE projectgroup SET pgStatus='Delivery', pgHandler='General Manager' WHERE pgID=$id");
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: billingstatement.php?id=$id&num=$num");
        }
?>
<!DOCTYPE html>
<head>
	<title>Edit Progress</title>
	<link rel="stylesheet" href="editprogress.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
	<div class="container">
		<div class="content">
			<b>Order Progress</b><br>
			<div class="content1">
				<b>Choose Current Progress</b>
			</div>
			<form method="POST" style="background:rgb(16,46,74);">
			<div class="processes" >
				<ul>
					<li><button style="border: none; background: none;" type="submit" name="pd" disabled><img src="img/progress.png" style="height:8.5rem" ><br>Product<br>Design</button></li>
					<li><button style="border: none; background: none;" type="submit" name="ctp" <?=$i2?>><img src="img/ctp.png" style="height:8.5rem" ><br>CTP<br>Processing</button></li>
					<li><button style="border: none; background: none;" type="submit" name="proof" <?=$i3?>><img src="img/proof.png" style="height:8.5rem" ><br>Proofing</button></li>
					<li><button style="border: none; background: none;" type="submit" name="mp" <?=$i4?>><img src="img/mass.png" style="height:8.5rem" ><br>Mass<br>Production</button></li>
					<li><button style="border: none; background: none;" type="submit" name="deliver" <?=$i5?>><img src="img/delivery2.png" style="height:8.5rem" ><br>Delivery</button></li>
				</ul>
			</div>
			</form>
		</div>
	</div>
</body>
<?php
    }
    else {
        header ("HTTP/1.1 301 Moved Permanently");
header ("Location: login.php");
        exit();
    }
?>