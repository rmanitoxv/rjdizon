<?php
    session_start();

    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result1 =  pg_fetch_assoc($online);
        if ($result1['isadmin'] == 1){
            echo '<meta http-equiv="refresh" content="0;url=admin.php">';
        }
        if ($result1['isstaff'] == 1){
            echo '<meta http-equiv="refresh" content="0;url=staff/staff.php">';
        }
    }
    if(isset($_POST['submit'])){
        $link = strtolower($_POST['link']);
        $sql = "SELECT * FROM projectgroup WHERE pglink='$link'";
        $result = pg_query($con, $sql);
        if(pg_num_rows($result) == 1) {
            echo '<meta http-equiv="refresh" content="0;url=track.php?link=$link">';
        }
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="site.css">
    <title>Homepage | RJ DIZON PRINTING PRESS</title>
    <script>
      this.top.location !== this.location && (this.top.location = this.location);
    </script>
  </head>
  <body>

    <div class="container">
        
      <div class="indexlogo">
        <img src="img/logo.png" class="indexlogoimg">
      </div>
      
        <div class="maintitle">
          ORDER TRACKER<br>
        </div>
      <div class="form_trackingnumber">
        <div class="subtitle">
          Tracking Number<br>
        </div>
        <div class="trackingnumberinput">
          <form method="POST">
            <input type="text" name="link" value="" class="trackingnumbertextbox" placeholder="Enter your Tracking Number here" required><br>
            <input type="submit" name="submit" value="ENTER" class="button_trackingnumbersubmit">
          </form>
        </div>
        <a href="login.php">
        <button type="button" name="button" class="button_stafflogin1">STAFF LOGIN</button>
        </a>
      </div>
    </div>
  </body>
</html>
