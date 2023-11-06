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
        $query="SELECT * FROM officialreceipt WHERE or_isactive=1";
        if (isset($_GET['search1'])){
            $search=$_GET['search'];
            $full = 1;
            $partial = 1;
            if (strtolower($search) == "full"){
                $full = 1;
                $partial =0;
            }
            if (strtolower($search) == "partial"){
                $full = 0;
                $partial =1;
            }
            $query = "SELECT * FROM officialreceipt WHERE or_isactive=1 AND (orcustomer LIKE '%$search%' OR orserial::text LIKE '%$search%' OR ortin LIKE '%$search%' OR orcashier LIKE '%$search%' OR or_ispartial::text LIKE '%$partial%' OR or_isfull::text LIKE '%$full%' OR oramount::text LIKE '%$search%' OR ordate LIKE '%$search%')";
        }
?>
<!DOCTYPE html>
<head>
	<title>Official Receipt</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
    <div class="container-content-header">
            <b>RECORDS</b>
        </div>
        <div class="container-records-logs-header">
            OFFICIAL RECEIPT<br>
        </div>
        <div class="container-records-logs-body">
            <form method="GET">
                <input type="search" id="site-search" name="search" placeholder="" class="input-records-logs-search"><br>
                <button name="search1" class="button-records-logs-search">SEARCH</button>
            <div class="container-records-logs-body-buttons">
                <br>
                <button name="remove" formaction="remove_or.php" class="button-records-logs-remove">DELETE</button>
                <button name="edit" formaction="edit_or.php" class="button-records-logs-edit">EDIT</button>
            </div>
            <div class="container-table">
                <div class="row-records-logs-header">
                    <div class="cell-records-logs-header-customer">Serial No.</div>
                    <div class="cell-records-logs-header-bsserial">Customer</div>
                    <div class="cell-records-logs-header-ponumber">TIN</div>
                    <div class="cell-records-logs-header-total">Cashier</div>
                    <div class="cell-records-logs-header-payable">Pay Status</div>
                    <div class="cell-records-logs-header-paid">Amount</div>
                    <div class="cell-records-logs-header-date">Date</div>
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
                <input type="checkbox" class="cbx" id="cbx<?=$i?>" name="id[]" value=<?=$row->orid?> />
                <label class="logs-cbx-<?=$j?>" for="cbx<?=$i?>">
                <div>
                    <div><?=$row->orserial?></div>
                    <div><?=$row->orcustomer?></div>
                    <div><?=$row->ortin?></div>
                    <div><?=$row->orcashier?></div>
                    <div><?php
                            if ($row->or_ispartial == 1){
                                    echo "Partial"; 
                            }
                            else {
                                echo "Full";
                            }
                    ?></div>
                    <div><?=$row->oramount?></div>
                    <div><?=$row->ordate?></div>
                    <div class="viewbutton"><a href="view_or.php?id=<?=$row->orid?>"><button type="button" class="button-records-bs-view">VIEW</button></a></div>
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
            </form>
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