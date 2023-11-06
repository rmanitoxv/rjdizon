<?php
    session_start();
    include("config.php");

    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result1 =  pg_fetch_assoc($online);
        if ($result1['isadmin'] == 1){
            header ("Location: admin.php");
        }
        if ($result1['isstaff'] == 1){
            header ("Location:  staff/staff.php");
        }
    }
    else {
        if(isset($_POST['username']) && isset($_POST['password'])) {

            function validate($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            $username = validate($_POST['username']);
            $password = validate($_POST['password']);

            $sql = "SELECT * FROM accounts WHERE uname='$username'";

            $result = pg_query($con, $sql);

            if(pg_num_rows($result) == 1) {
              $row = pg_fetch_assoc($result);
              if($row['uname'] == $username && $row['pw'] == md5($password) && $row['user_isactive'] == 1) {
                  if($row['isadmin'] == 1){
                      $_SESSION['username'] = $row['uname'];
                      $_SESSION['userID'] = $row['userid'];
                      header("HTTP/1.1 301 Moved Permanently");
                      header("Location: admin.php");
                      exit();
                    }
                  else{
                      $_SESSION['username'] = $row['uname'];
                      $_SESSION['userID'] = $row['userid'];
                      header("HTTP/1.1 301 Moved Permanently");
                      header("Location:  staff/staff.php");
                      exit();
                    }
              }
              else{
                  header("Location: login.php?error=Incorrect Username or Password");
              }
            }
            else{
                header("Location: login.php?error=Incorrect Username or Password");
            }
        }
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="site.css">
    <title>Staff Login | RJ DIZON PRINTING PRESS</title>
    <script>
      this.top.location !== this.location && (this.top.location = this.location);
    </script>
  </head>
  <body>
    <div class="container">
      <div class="indexlogo">
        <img src="img/logo.png" class="indexlogoimg">
      </div>
      <div class="form_login">
        <div class="maintitle2">
          Staff Login<br>
        </div>
      
        <form method="post">
          <div class="subtitle2">
            Username<br>
          </div>
          <input type="text" name="username" class="input_indexusername" placeholder="ENTER USERNAME" required><br>
          <div class="subtitle2">
            Password<br>
          </div>
          <input type="password" name="password" class="input_indexpassword" placeholder="ENTER PASSWORD" required><br>
          <input type="submit" name="" value="LOGIN" class="button_loginenter" >
          <?php if(isset($_GET['error'])) {?>
            <p class="error"> <?php echo $_GET['error']; ?></p>
          <?php } ?>
        </form>
          <a href="#" onClick="history.go(-1)">
            <button type="button" name="button" class="button_indexback1">BACK</button>
          </a><br>
      </div>
    </div>
  </body>
</html>
<?php
    }
?>