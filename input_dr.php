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
        if (isset($_POST['submit'])){
            $query = mysqli_query($con, "SELECT * FROM projectgroup WHERE pgID=$id");
            $result = mysqli_fetch_assoc($query);
            $tin = $_SESSION['tin'];
            $style = $_SESSION['style'];
            $serial = $_POST['drserial'];
            $author = $_POST['drauthor'];
            $customer = $result['pgCustomer'];
            $address = $result['pgAddress'];
            $date = (date("F d, Y"));
            $po = $result['pgPO'];
            mysqli_query($con, "INSERT INTO deliveryreceipt VALUES('', $id, '$serial', '$customer', '$address', '$date', '$tin', '$po', '$style', '$author', '$date', 1, '')");
            $get = mysqli_query($con, "SELECT drID FROM deliveryreceipt WHERE drSerial='$serial'");
            $get1 = mysqli_fetch_assoc($get);
            $drID = $get1['drID'];
            $rows = mysqli_query($con, "SELECT * FROM project WHERE pgID=$id");
            foreach ($rows as $row):
                $qty = $row['projectQty'];
                $unit = $row['projectUnit'];
                $desc = $row['projectDesc'];
                mysqli_query($con, "INSERT INTO drdetails VALUES('', $drID, '$desc', '$qty', '$unit')");
            endforeach;
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: ordernum.php?id=$id");
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
    <h2>Input Delivery Receipt Details <?=$tin?></h2>
    <form method="POST">
        <label> Serial Number: </label>
        <input type="text" name="drserial" minlength="5" maxlength="5" required /><br/>
        <label> Author: </label>
        <input type="text"  name="drauthor" /><br/>
        <button type="submit" name="submit">NEXT</button>  
    </form>
    <form method="GET" action="ordernum.php">
        <input type="hidden" name="id" value=<?=$id?> />
        <button type="submit">NO</button>
    </form>
</body>
</html>
<?php
    }
    else {
        header ("HTTP/1.1 301 Moved Permanently");
header ("Location: login.php");
        exit();
    }
?>