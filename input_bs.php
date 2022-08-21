<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = mysqli_query($con, "SELECT * FROM user WHERE userID = '$id'");
        $result =  mysqli_fetch_assoc($online);
        if ($result['isStaff'] == 1){
            header ("Location: staff.php");
        }
        $id = $_GET['id'];
        if (isset($_POST['submit'])){
            $query = mysqli_query($con, "SELECT * FROM projectgroup WHERE pgID=$id");
            $result = mysqli_fetch_assoc($query);
            $serial = $_POST['bsserial'];
            $customer = $result['pgCustomer'];
            $date = (date("F d, Y"));
            $style = $_POST['bstyle'];
            $tin = $_POST['tin'];
            $_SESSION['tin'] = $tin;
            $_SESSION['style'] = $style;
            $total = $result['pgTotal'];
            $cashier = $_POST['bscashier'];
            mysqli_query($con, "INSERT INTO billingstatement VALUES('', $id, '$serial', '$customer', '$date', '$style', '$tin', '$total', '$cashier', 1, '')");
            $get = mysqli_query($con, "SELECT bsID FROM billingstatement WHERE bsSerial='$serial'");
            $get1 = mysqli_fetch_assoc($get);
            $bsID = $get1['bsID'];
            $rows = mysqli_query($con, "SELECT * FROM project WHERE pgID=$id");
            foreach ($rows as $row):
                $qty = $row['projectQty'];
                $unit = $row['projectUnit'];
                $desc = $row['projectDesc'];
                $price = $row['projectPrice'];
                mysqli_query($con, "INSERT INTO bsdetails VALUES('', $bsID, '$qty', '$unit', '$desc', '$price')");
            endforeach;
            header ("Location: input_dr.php?id=$id");
        }
?>
<!DOCTYPE php>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On-going Orders</title>
</head>
<body>
    <h1>Complete Order</h1>
    <h2>Input Billing Statement Details</h2>
    <form method="POST">
        <label> Serial Number: </label>
        <input type="text"  name="bsserial" minlength="5" maxlength="5" required /><br/>
        <label> TIN: </label>
        <input type="text"  name="tin" maxlength="12" pattern="\d{3}[\-]\d{3}[\-]\d{3}[\-]\d{3}" /><br/>
        <label> Business Style: </label>
        <input type="text"  name="bstyle" /><br/>
        <label> Cashier: </label>
        <input type="text"  name="bscashier" /><br/>
        <button type="submit" name="submit">NEXT</button> 
    </form>
    <form method="POST" action="ordernum.php">
        <input type="hidden" name="id" value=<?=$id?> />
        <button type="submit">NO</button>
    </form>
</body>
</html>
<?php
    }
    else {
        header("Location: login.php");
        exit();
    }
?>