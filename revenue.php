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
        $query="SELECT * FROM revenue WHERE r_isactive=1";
        if (isset($_GET['search1'])){
            $search=$_GET['search'];
            $query = "SELECT * FROM revenue WHERE r_isactive=1 AND (rincome::text LIKE '%$search%' OR rexpense::text LIKE '%$search%')";
        }
?>
<!DOCTYPE html>
<head>
	<title>Revenue</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
    <div class="container-content-header">
        <b>RECORDS</b>
    </div>
    <div class="container-records-logs-header">
        SALES REVENUE<br>
    </div>
    <div class="container-records-logs-body">
        <form method="GET">
                <input type="search" id="site-search" name="search" placeholder="" class="input-records-logs-search"><br>
                <button name="search1" class="button-records-logs-search">SEARCH</button>
            <div class="container-records-logs-body-buttons">
                <br>
                <button name="remove" formaction="remove_revenue.php" class="button-records-logs-remove">DELETE</button>
                <button name="edit" formaction="download_sr.php" class="button-records-logs-edit">SAVE</button>
            </div>

            <div class="container-table">
                <div class="row-records-logs-header">
                    <div class="cell-records-logs-header-bsserial">Tracking Number</div>
                    <div class="cell-records-logs-header-customer">Date</div>
                    <div class="cell-records-logs-header-bsserial">Income</div>
                    <div class="cell-records-logs-header-ponumber">Expense</div>
                    <div class="cell-records-logs-header-total">Revenue</div>
                </div>
            </div>
            <div class="container-table">
                <?php
                    $i = 1;
                    $j = "blue";
                    $rows = pg_query($con, $query);
                    while ($row = pg_fetch_object($rows)){
                        $pgid = $row->pgid;
                        $query = pg_query($con, "SELECT * FROM projectgroup WHERE pgid=$pgid");
                        $result = pg_fetch_assoc($query);
                ?>
                <input type="checkbox" class="cbx" id="cbx<?=$i?>" name="ids[]" value=<?=$row->rid?> />
                <label class="logs-cbx-<?=$j?>" for="cbx<?=$i?>">
        </form>
                <div>
                    <div class="cell-records-logs-data-customer"><?=$result['pglink']?>&nbsp;</div>
                    <div class="cell-records-logs-data-customer"><?=$row->rdate?>&nbsp;</div>
                    <div class="cell-records-logs-data-bsserial"><?=$row->rincome?> Pesos&nbsp;</div>
                    <div class="cell-records-logs-data-ponumber"><?=$row->rexpense?> Pesos&nbsp;</div>
                    <?php
                        $revenue = (int)$row->rincome - (int)$row->rexpense;
                    ?>
                    <div class="cell-records-logs-data-total"><?=$revenue?> Pesos&nbsp;</div>
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
</body>
<?php
    }
    else {
        echo '<meta http-equiv="refresh" content="0;url=login.php">';
        exit();
    }
?>