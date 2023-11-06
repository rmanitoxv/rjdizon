<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            header ("Location: staff.php");
        }
        if(!isset($_GET['ids'])){
            header ("Location: revenue.php");
        }
        $all_id = $_GET['ids'];
        $ids = implode(', ', $all_id);
        $count = count($all_id);
        if(isset($_POST['submit'])){
            for($i=0;$i<$count;$i++){
                $id = $all_id[$i];
                $date = $_POST['date'.$i];
                $income = $_POST['income'.$i];
                $expense = $_POST['expense'.$i];
                $query = "UPDATE revenue SET rdate='$date', rincome='$income', rexpense='$expense' WHERE rid='$id' ";
                pg_query($con, $query);
            }
            header ("Location: revenue.php");
        }
?>
<!DOCTYPE html>
<head>
	<title>Sales Revenue</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
    <div class="container-content-header">
        <b>SALES REVENUE</b>
    </div>
    <div class="container-inventory-add-header">
        EDIT SALES REVENUE<br>
    </div>

    <div class="container-inventory-add2">
        <div class="container-inventory-add">
            <form method="POST">
            <?php 
                for($i=0;$i<$count;$i++){
                    $id = $all_id[$i];
                    $query = pg_query($con, "SELECT * FROM revenue WHERE rid='$id'");
                    while ($row = pg_fetch_object($query)){
            ?>
            <h2 style="color:white;font-size:3rem;"> Official Receipt # <?=$i+1?></h2>
            <table width="100%">
                <tr>
                    <td class="cell-inventory-add-label">Date:</td>
                    <td style="text-align: left;"><input type="text" class="cell-inventory-add-input" name="date<?=$i?>" value="<?=$row->rdate?>"/></td>
                </tr>
                <tr>
                    <td class="cell-inventory-add-label">Income:</td>
                    <td style="text-align: left;"><input type="number" min="0" class="cell-inventory-add-input" min="0" name="income<?=$i?>" value="<?=$row->rincome?>" /></td>
                </tr>
                <tr>
                    <td class="cell-inventory-add-label">Expense:</td>
                    <td style="text-align: left;"><input type="number" min="0" class="cell-inventory-add-input" name="expense<?=$i?>" value="<?=$row->rexpense?>" style="width: 10rem;" /></td>
                </tr>
            </table>
            <hr>
            <?php 
                }
            }
            ?>
                <div class="row">
                    <a href="revenue.php"><button type="button" class="button-inventory-add-material-submit">BACK</button></a>
                    <button name="submit" class="button-inventory-add-material-submit">EDIT</button>
                </div>
            </form>
        </div>
    </div>
</body>
<?php
    }
    else {
        header("Location: login.php");
        exit();
    }
?>