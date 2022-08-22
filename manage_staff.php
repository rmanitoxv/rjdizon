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
        $query="SELECT * FROM accounts INNER JOIN staff ON accounts.userid = staff.userid WHERE user_isactive=1";
        $query1 = pg_query($con, "SELECT * FROM accounts WHERE isadmin=1");
        if (isset($_GET['search1'])){
            $search=$_GET['search'];
            $query = "SELECT * FROM accounts INNER JOIN staff ON accounts.userid = staff.userid WHERE user_isactive=1 AND (uname LIKE '%$search%' OR stafffname LIKE '%$search%' OR stafflname LIKE '%$search%' OR staffposition LIKE '%$search%')";
            $query1 = "SELECT * FROM accounts WHERE user_isactive=1 AND isadmin=1 AND uname LIKE '%$search%'";
        }
?>
<!DOCTYPE html>
<head>
	<title>Staff</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
<div class="container-content-header">
        <b>RECORDS</b>
    </div>
    <div class="container-records-logs-header">
        USERS<br>
    </div>
    <div class="container-records-logs-body">
        <form method="GET">
                <input type="search" id="site-search" name="search" placeholder="" class="input-records-logs-search"><br>
                <button name="search1" class="button-records-logs-search">SEARCH</button>
            <div class="container-records-logs-body-buttons">
                <br>
                <a href="add_user.php"><button type="button" class="button-records-logs-remove">ADD</button></a>
                <button name="remove" formaction="remove_user.php" class="button-records-logs-remove">DELETE</button>
                <button name="edit" formaction="edit_user.php" class="button-records-logs-edit">EDIT</button>
            </div>

            <div class="container-table">
                <div class="row-records-logs-header">
                    <div class="cell-records-logs-header-customer">Username</div>
                    <div class="cell-records-logs-header-bsserial">First Name</div>
                    <div class="cell-records-logs-header-ponumber">Last Name</div>
                    <div class="cell-records-logs-header-total">Position</div>
                </div>
            </div>
            <div class="container-table">
                <?php 
                    while ($row1 = pg_fetch_object($query1)){
                        ?>
                    <input type="checkbox" class="cbx" id="cbx1" name="ids[]" value=<?=$row1->userid?> />
                    <label class="logs-cbx-blue" for="cbx1">
                        <div>
                            <div class="cell-records-logs-data-customer"><?=$row1->uname?></div>    
                            <div class="cell-records-logs-data-customer"></div>
                            <div class="cell-records-logs-data-customer"></div>
                            <div class="cell-records-logs-data-customer">General Manager</div>
                        </div>
                    </label>
                    <?php
                }
                ?>
                <?php
                    $i = 2;
                    $j = "grey";
                    $rows = pg_query($con, $query);
                    while ($row = pg_fetch_object($rows)){
                ?>
                <input type="checkbox" class="cbx" id="cbx<?=$i?>" name="ids[]" value=<?=$row->userid?> />
                <label class="logs-cbx-<?=$j?>" for="cbx<?=$i?>">
        </form>
            <div>
                <div class="cell-records-logs-data-customer"><?=$row->uname?></div>
                <div class="cell-records-logs-data-customer"><?=$row->stafffname?></div>
                <div class="cell-records-logs-data-customer"><?=$row->stafflname?></div>
                <div class="cell-records-logs-data-customer"><?=$row->staffposition?></div>
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
        header("Location: login.php");
        exit();
    }
?>