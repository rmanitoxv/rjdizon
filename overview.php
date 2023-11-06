<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userID = $id");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            echo '<meta http-equiv="refresh" content="0;url=staff.php">';
        }
?>
<!DOCTYPE html>
<head>
    <title>Overview</title>
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-content-header">
            <b>OVERVIEW</b>
        </div>
        <div class="container-overview-section1">
            <div class="container-overview-section1-stocks">
                Stocks<br>
                <table width="100%" class="table-overview" cellspacing="0">
                <?php 
                $query = pg_query($con, "SELECT * FROM inventory WHERE item_isactive=1");
                $i = 1;
                $j = "blue";
                while ($row = pg_fetch_object($query)){

                    if ($row->itemquantity<$row->itemthreshold){
                        if ($row->itemquantity == 0){
                ?>
                        <tr class="row-container-overview-stocks-<?=$j?>">
                            <td class="row-overview-section1-stocks-name"><?=$row->itemname?></td>
                            <td class="row-overview-section1-stocks-status-empty">Empty</td>
                        </tr>
                <?php
                        }
                        else{ 
                ?>
                        <tr class="row-container-overview-stocks-<?=$j?>">
                            <td class="row-overview-section1-stocks-name"><?=$row->itemname?></td>
                            <td class="row-overview-section1-stocks-status-low">Low Stocks</td>
                        </tr>
                <?php } 
                }
                if ($j == "blue"){
                    $j = "grey";
                }
                else{
                    $j = "blue";
                }
            }
                ?>
                </table>
            </div>
            <div class="container-overview-section1-taskactivities">
                Task Activities
                <table width="100%" class="table-overview" cellspacing="0">
                <?php 
                $query = pg_query($con, "SELECT * FROM notifications ORDER BY nid DESC LIMIT 10 ");
                $i = 1;
                $j = "blue";
                while ($row = pg_fetch_object($query)){
                ?>
                    <tr class="row-container-overview-taskactivities-<?=$j?>">
                        <td class="row-overview-section1-taskactivities" style="width:60%"><?=$row->notif?></td>
                        <td class="row-overview-section1-taskactivities"><?=$row->ndate?>&nbsp;</td>
                        <td class="row-overview-section1-taskactivities"><?=$row->ntime?>&nbsp;</td>
                    </tr>
                <?php 
                if ($j == "blue"){
                    $j = "grey";
                }
                else{
                    $j = "blue";
                }
                }
                ?>
                </table>
            </div>
        </div>
        <div class="container-overview-section2">
            <div class="container-overview-section2-recentorders">
                Recent Orders
                <table width="100%" class="table-overview" cellspacing="0">
                <?php 
                $query = pg_query($con, "SELECT * FROM projectgroup WHERE pg_isactive=1 ORDER BY pgID DESC LIMIT 10 ");
                $i = 1;
                $j = "blue";
                while ($row = pg_fetch_object($query)){
                ?>
                    <tr class="row-container-overview-recentorders-<?=$j?>">
                        <td class="row-overview-section1-recentorders-customername"><?=$row->pgcustomer?></td>
                        <td class="row-overview-section1-recentorders-trackingnumber"><?=$row->pglink?></td>
                        <td class="row-overview-section1-recentorders-time"><?=$row->pgdate?></td>
                    </tr>
                <?php 
                if ($j == "blue"){
                    $j = "grey";
                }
                else{
                    $j = "blue";
                }
                }
                ?>
                </table>
            </div>
        </div>
</body>
<?php
    }
    else {
        echo '<meta http-equiv="refresh" content="0;url=login.php">';
        exit();
    }
?>