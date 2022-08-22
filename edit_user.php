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
            header ("Location: manage_staff.php");
        }
        $all_id = $_GET['ids'];
        $ids = implode(', ', $all_id);
        $count = count($all_id);
        if(isset($_POST['submit'])){
            for($i=0;$i<$count;$i++){
                $id = $all_id[$i];
                $uname = $_POST['uname'.$i];
                $pw = $_POST['pw'.$i];
                $cpw = $_POST['cpw'.$i];
                $sql = pg_query($con, "SELECT * FROM accounts INNER JOIN staff ON accounts.userid = staff.userid WHERE uname='$uname' and user_isactive=1");
                if ($pw != $cpw){
                    $error="Password and Change Password is not the same";
                    header ("Location: edit_user.php?error=$error&ids=$all_id");
                }
                else{
                    $result = pg_fetch_assoc($sql);
                    if(pg_num_rows($sql) > 0) {
                        if ($uname != $result['uname']){
                            $error="Username is already taken";
                            header ("Location: edit_user.php?error=$error&ids=$all_id");
                        }
                    }
                    $query = pg_query($con, "SELECT * from accounts WHERE userid='$id'");
                    $result = pg_fetch_assoc($query);
                    $pw1 = $result['password'];
                    if ($pw != $pw1){
                        $pw = md5($pw);
                    }
                    $pos = $_POST['pos'.$i];
                    $isadmin = 0
                    $isstaff = 1
                    if ($pos == "General Manager"){
                        $isadmin = 1
                        $isstaff = 0
                    }
                    $query = "UPDATE accounts SET uname='$uname', pw='$pw', isadmin=$isadmin, isstaff=$isstaff WHERE userid='$id'";
                    pg_query($con, $query);
                    $fname = $_POST['fname'.$i];
                    $lname = $_POST['lname'.$i]; 
                    if ($pos == "none"){
                        $pos = $result['staffposition'];
                    }
                    $query = "UPDATE staff SET stafffname='$fname', stafflname='$lname', staffposition='$pos' WHERE userid='$id'";
                    pg_query($con, $query);
                }
            }
            header ("Location: manage_staff.php?pos=$pos");
        }

?>
<!DOCTYPE php>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>
    <link rel="stylesheet" href="style.css">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body>
<div class="container-content-header">
        <b>RECORDS</b>
    </div>
    <div class="container-inventory-add-header">
        EDIT USER ACCOUNTS<br>
    </div>
    <div class="container-inventory-add2">
        <div class="container-inventory-add">
        <form method="POST">
        <?php 
            for($i=0;$i<$count;$i++){
                $id = $all_id[$i];
                $sql = pg_query($con, "SELECT * FROM accounts WHERE userid=$id and isadmin=1");
                $sql1 = pg_query($con, "SELECT * FROM staff WHERE userid=$id");
                if (pg_num_rows($sql) == 1){
                    while ($row1 = pg_fetch_object($sql)){
                        ?>
                        <h2 style="color:white;font-size:3rem;"> User # <?=$i+1?></h2>
                        <table width="100%">
                            <tr>
                                <td class="cell-inventory-add-label">Username:</td>
                                <td><input class="cell-inventory-add-input" type="text" name="uname<?=$i?>" value="<?=$row1->uname?>" required/></td>
                            </tr>
                            <tr>
                                <td class="cell-inventory-add-label">Password:</td>
                                <td><input class="cell-inventory-add-input" type="password" name="pw<?=$i?>" value="<?=$row1->pw?>" required/></td>
                            </tr>
                            <tr>
                                <td class="cell-inventory-add-label">Confirm Password:</td>
                                <td><input class="cell-inventory-add-input" type="password" name="cpw<?=$i?>" value="<?=$row1->pw?>" required/></td>
                            </tr>
                            <?php 
                                if (pg_num_rows($sql1) == 1){
                                    while ($row2 = pg_fetch_object($sql1)){
                            ?>
                                <tr>
                                    <td class="cell-inventory-add-label">First Name:</td>
                                    <td><input class="cell-inventory-add-input" type="text" name="fname<?=$i?>" value="<?=$row2->stafffname?>" required /></td>
                                </tr>
                                <tr>
                                    <td class="cell-inventory-add-label">Last Name:</td>
                                    <td><input class="cell-inventory-add-input" type="text" name="lname<?=$i?>" value="<?=$row2->stafflname?>" required /></td>
                                </tr>
                                <tr>
                                    <td class="cell-inventory-add-label">Position:</td>
                                    <td><select class="cell-inventory-add-input" name="pos<?=$i?>" required>
                                        <option selected disabled value="">Select</option>
                                        <option value="Artist">Artist</option>
                                        <option value="Office Clerk">Office Clerk</option>
                                        <option value="General Manager">General Manager</option>
                                    </select></td>
                                </tr>
                        </table>
                <?php
                        }}
                    }
                }
                $query = pg_query($con, "SELECT * FROM accounts INNER JOIN staff ON accounts.userid = staff.userid WHERE accounts.userid='$id'");
                while ($row = pg_fetch_object($query)){
        ?>
            <h2 style="color:white;font-size:3rem;"> User # <?=$i+1?></h2>
            <table width="100%">
                <tr>
                    <td class="cell-inventory-add-label">Username:</td>
                    <td><input class="cell-inventory-add-input" type="text" name="uname<?=$i?>" value="<?=$row->uname?>" required/></td>
                </tr>
                <tr>
                    <td class="cell-inventory-add-label">Password:</td>
                    <td><input class="cell-inventory-add-input" type="password" name="pw<?=$i?>" value="<?=$row->pw?>" required/></td>
                </tr>
                <tr>
                    <td class="cell-inventory-add-label">Confirm Password:</td>
                    <td><input class="cell-inventory-add-input" type="password" name="cpw<?=$i?>" value="<?=$row->pw?>" required/></td>
                </tr>
                <tr>
                    <td class="cell-inventory-add-label">First Name:</td>
                    <td><input class="cell-inventory-add-input" type="text" name="fname<?=$i?>" value="<?=$row->stafffname?>" required /></td>
                </tr>
                <tr>
                    <td class="cell-inventory-add-label">Last Name:</td>
                    <td><input class="cell-inventory-add-input" type="text" name="lname<?=$i?>" value="<?=$row->stafflname?>" required /></td>
                </tr>
                <tr>
                    <td class="cell-inventory-add-label">Position:</td>
                    <td><select class="cell-inventory-add-input" name="pos<?=$i?>" required>
                        <option selected disabled value="">Select</option>
                        <option value="Artist">Artist</option>
                        <option value="Office Clerk">Office Clerk</option>
                        <option value="General Manager">General Manager</option>
                    </select></td>
                </tr>
            </table>
        <?php 
                }
            }
        ?>
        <button type="submit" name="submit" class="button-staff-add-material-submit">EDIT</button>
            </form>
            <a href="manage_staff.php"><button class="button-staff-back-material-submit">BACK</button></a>
        </div>
    </div>
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