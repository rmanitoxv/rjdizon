<?php
    session_start();
    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result =  pg_fetch_assoc($online);
        if ($result['isstaff'] == 1){
            header ("HTTP/1.1 301 Moved Permanently");
header ("Location: staff.php");
        }
        $query="SELECT * FROM logs WHERE logs_isactive=1";
        if (isset($_GET['search1'])){
            $search=$_GET['search'];
            $query = "SELECT * FROM logs WHERE logs_isactive=1 AND (logscustomer LIKE '%$search%' OR logsbsserial::text LIKE '%$search%' OR logspo LIKE '%$search%' OR logstotal::text LIKE '%$search%' OR logspayable::text LIKE '%$search%' OR logsdate LIKE '%$search%')";
        }
?>
<!DOCTYPE html>
<head>
	<title>Logs</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
    <div class="container-content-header">
        <b>RECORDS</b>
    </div>
    <div class="container-records-logs-header">
        LOGS<br>
    </div>
    <div class="container-records-logs-body">
        <form method="GET">
                <input type="search" id="site-search" name="search" placeholder="" class="input-records-logs-search"><br>
                <button name="search1" class="button-records-logs-search">SEARCH</button>
            <div class="container-records-logs-body-buttons">
                <br>
                <button name="remove" formaction="remove_logs.php" class="button-records-logs-remove">DELETE</button>
                <button name="edit" formaction="edit_logs.php" class="button-records-logs-edit">EDIT</button>
            </div>

            <div class="container-table">
                <div class="row-records-logs-header">
                    <div class="cell-records-logs-header-customer">Customer</div>
                    <div class="cell-records-logs-header-bsserial">BS Serial No.</div>
                    <div class="cell-records-logs-header-ponumber">P.O No.</div>
                    <div class="cell-records-logs-header-total">Total</div>
                    <div class="cell-records-logs-header-payable">Payable</div>
                    <div class="cell-records-logs-header-paid">&nbsp;</div>
                    <div class="cell-records-logs-header-date">Date</div>
                </div>
            </div>
            <div class="container-table">
                <?php
                    $i = 1;
                    $j = "blue";
                    $rows = pg_query($con, $query);
                    while ($row = pg_fetch_object($rows)){
                ?>
                <input type="checkbox" class="cbx" id="cbx<?=$i?>" name="ids[]" value=<?=$row->logsid?> />
                <label class="logs-cbx-<?=$j?>" for="cbx<?=$i?>">
        </form>
                <div>
                    <div class="cell-records-logs-data-customer"><?=$row->logscustomer?>&nbsp;</div>
                    <div class="cell-records-logs-data-bsserial"><?=$row->logsbsserial?>&nbsp;</div>
                    <div class="cell-records-logs-data-ponumber"><?=$row->logspo?>&nbsp;</div>
                    <div class="cell-records-logs-data-total"><?=$row->logstotal?>&nbsp;</div>
                    <div class="cell-records-logs-data-payable"><?=$row->logspayable?>&nbsp;</div>
                    <div class="cell-records-logs-data-paid"><?php
                    if (number_format($row->logspayable) > 0){
                    ?>
                    <form method="GET">
                    <input type="hidden" name="id" value="<?=$row->logsid?>" />
                    <button type="submit" name="submit" class="button-records-logs-pay" formaction="logs_paid.php">Payment</button>
                    </form>
                    <?php
                    }
                    else{
                        echo "&nbsp;";
                    }
                    ?></div>
                    <div class="cell-records-logs-data-date"><?=$row->logsdate?>&nbsp;</div>
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
        header ("HTTP/1.1 301 Moved Permanently");
header ("Location: login.php");
        exit();
    }
?>