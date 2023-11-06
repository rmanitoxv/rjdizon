<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            echo '<meta http-equiv="refresh" content="0;url=staff.php">';
        }
        $query="SELECT * FROM deliveryreceipt WHERE dr_isactive=1";
        if (isset($_GET['search1'])){
            $search=$_GET['search'];
            $query = "SELECT * FROM deliveryreceipt WHERE dr_isactive=1 AND (drcustomer LIKE '%$search%' OR drserial::text LIKE '%$search%' OR drdate LIKE '%$search%' OR drdatereceived LIKE '%$search%')";
        }
?>
<!DOCTYPE html>
<head>
	<title>Delivery Reciepts</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
<div class="container-content-header">
            <b>RECORDS</b>
        </div>
        <div class="container-records-logs-header">
            DELIVERY RECEIPT<br>
        </div>
        <div class="container-records-logs-body">
            <form method="GET">
                <input type="search" id="site-search" name="search" placeholder="" class="input-records-logs-search"><br>
                <button name="search1" class="button-records-logs-search">SEARCH</button>
            <div class="container-records-logs-body-buttons">
                <br>
                <button name="remove" formaction="remove_dr.php" class="button-records-logs-remove">DELETE</button>
                <button name="edit" formaction="edit_dr.php" class="button-records-logs-edit">EDIT</button>
            </div>
            <div class="container-table">
                <div class="row-records-logs-header">
                    <div class="cell-records-logs-header-customer">Serial No.</div>
                    <div class="cell-records-logs-header-bsserial">Customer</div>
                    <div class="cell-records-logs-header-ponumber">Date</div>
                    <div class="cell-records-logs-header-total">Date Received</div>
                    <div class="cell-records-logs-header-date">&nbsp;</div>
                </div>
            </div>
            <div class="container-table">
                <?php
                    $i = 1;
                    $j = "blue";
                    $rows = pg_query($con, $query);
                    while ($row = pg_fetch_object($rows)){
                ?>
                <input type="checkbox" class="cbx" id="cbx<?=$i?>" name="ids[]" value=<?=$row->drid?> />
                <label class="logs-cbx-<?=$j?>" for="cbx<?=$i?>">
                    <div>
                        <div><?=$row->drserial?></div>
                        <div><?=$row->drcustomer?></div>
                        <div><?=$row->drdate?></div>
                        <div><?=$row->drdatereceived?></div>
                        <div class="viewbutton" ><a href="view_dr.php?id=<?=$row->drid?>"><button type="button" class="button-records-bs-view">VIEW</button></a></div>
                    </div>
                </label>
            <?php 
                if ($j == "blue"){
                    $j = "grey";
                }
                else{
                    $j = "blue";
                }
                $i++;
            }
            ?>
        </div>
    </div>
    </form>
    </div>
</body>
<?php
    }
    else {
        echo '<meta http-equiv="refresh" content="0;url=login.php">';
        exit();
    }
?>