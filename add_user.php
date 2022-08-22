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
        if(isset($_POST['submit'])){
            $uname = $_POST['uname'];
            $pw = $_POST['pw'];
            $cpw = $_POST['cpw'];
            $sql = "SELECT * FROM accounts WHERE uname='$uname' and user_isactive=1";
            $result = pg_query($con, $sql);
            $result1 = pg_fetch_assoc($result);
            if ($pw != $cpw){
                $error="Password and Change Password is not the same";
                header ("Location: add_user.php?error=$error");
            }
            else if(pg_num_rows($result) > 0) {
                $error="Username is already taken";
                header ("Location: add_user.php?error=$error");
            }
            else{
                $pw = md5($pw);
                $pos = $_POST['pos'];
                $isadmin = 0
                $isstaff = 1
                if ($pos == "General Manager"){
                    $isadmin = 1
                    $isstaff = 0
                }
                $query = "INSERT into accounts (uname, pw, isadmin, isstaff, user_isactive, user_date_of_inactive) VALUES ('$uname', '$pw', $isadmin, $isstaff, 1, '')";
                pg_query($con, $query);
                $fname = $_POST['fname'];
                $lname = $_POST['lname'];
                $get_id = "SELECT userid from accounts WHERE uname='$uname' and user_isactive=1 LIMIT 1";
                $get = pg_query($con, $get_id);
                $id1 = pg_fetch_assoc($get);
                $id = $id1['userid'];
                $query = "INSERT into staff (userid, stafffname, stafflname, staffposition) VALUES ($id, '$fname', '$lname', '$pos')";
                pg_query($con, $query);
                header ("Location: manage_staff.php");
            }
        }
?>
<!DOCTYPE php>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
    <title>Staff</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-content-header">
        <b>STAFF</b>
    </div>
    <div class="container-add-new-order-details">
        ADD NEW USER
    </div>
<div class="container-add-new-order">
    <div class="content">
    <form method="POST">
        <div class="content">
        <table width="100%" class="table-orderinfo" cellspacing="0">
            <tr class="row-container-add-new-order">
                <td class="cell-add-new-order-text" width="30%">Username</td>
                <td colspan="3"><input type="text"  name="uname" class="add-new-order-input-text" required /></td>
            </tr>
            <tr>
                <td height="15px"></td>
            </tr>
            <tr class="row-container-add-new-order">
                <td class="cell-add-new-order-text" width="30%">Password</td>
                <td colspan="3"><input type="password"  name="pw" class="add-new-order-input-text"/></td>
            </tr>
            <tr>
                <td height="15px"></td>
            </tr>
            <tr class="row-container-add-new-order">
                <td class="cell-add-new-order-text" width="30%">Confirm Password</td>
                <td colspan="3"><input type="password"  name="cpw" class="add-new-order-input-text"/></td>
            </tr>
            <tr>
                <td height="15px"></td>
            </tr>
            <tr class="row-container-add-new-order">
                <td class="cell-add-new-order-text" width="30%">First Name</td>
                <td colspan="3"><input type="text"  name="fname" class="add-new-order-input-text" required /></td>
            </tr>
            <tr>
                <td height="15px"></td>
            </tr>
            <tr class="row-container-add-new-order">
                <td class="cell-add-new-order-text" width="30%">Last Name</td>
                <td colspan="3"><input type="text"  name="lname" class="add-new-order-input-text" required /></td>
            </tr>
            <tr>
                <td height="15px"></td>
            </tr>
            <tr class="row-container-add-new-order">
                <td class="cell-add-new-order-text" width="30%">Position</td>
                <td colspan="3"><select name="pos" class="add-new-order-input-text" required >
                    <option selected disabled>Select</option>
                    <option value="Artist">Artist</option>
                    <option value="Office Clerk">Office Clerk</option>
                    <option value="General Manager">General Manager</option>
                </select></td>
            </tr>
        </table>
        <?php if(isset($_GET['error'])) {?>
            <p class="error" style="color:black"> <?php echo $_GET['error']; ?></p>
          <?php } ?>
        </div>
        <a href="#" onClick="history.go(-1)"><button type="button" class="add-new-order-button" >BACK</button></a>
        <button class="add-new-order-button" name="submit">ADD</button>
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