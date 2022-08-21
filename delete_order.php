<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            header ("Location: staff/staff.php");
        }
        $id = $_GET['id'];
        $query = pg_query($con, "SELECT * FROM projectgroup WHERE pgid=$id");
        $result1 = pg_fetch_assoc($query);
        $num = $result1['pglink'];
        if (isset($_POST['submit'])){
            $date= (date("F d, Y"));
            pg_query($con, "UPDATE projectgroup SET pg_isactive=0, pg_date_of_inactive='$date', pghandler='None' WHERE pgid=$id");
            pg_query($con, "UPDATE revenue SET r_isactive=0, r_date_of_inactive='$date' WHERE pgid=$id");
            header ("Location: orders.php");
        }
?>
<!DOCTYPE php>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>On-going Orders</title>
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
    <div class="container-content-header">
        <b>ON-GOING ORDERS</b>
    </div>
    <div class="container-orderinfo-details-complete">
        ORDER #<?=$num?><br>
    </div>
    <div class="container-orderinfo-details-complete2">
            Delete (Order #<?=$num?>)
            <br><br><br>
        <form method="POST">
            <button class="button-orderinfo-confirmation-no" type="submit" formaction="ordernum.php?id=<?=$id?>">NO</button>
            <button class="button-orderinfo-confirmation-yes2" type="submit" name="submit">YES</button>
        </form>
    </div>
</body>
</html>
<?php
    }
    else {
        header("Location: login.php");
        exit();
    }
?>