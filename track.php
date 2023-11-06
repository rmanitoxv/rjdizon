<?php
    session_start();

    include("config.php");
    if(isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $online = pg_query($con, "SELECT * FROM accounts WHERE userid = '$id'");
        $result1 =  pg_fetch_assoc($online);
        if ($result1['isAdmin'] == 1){
            echo '<meta http-equiv="refresh" content="0;url=admin.php">';
        }
        if ($result1['isStaff'] == 1){
            echo '<meta http-equiv="refresh" content="0;url=staff.php">';
        }
    }
    if(isset($_GET['link'])){
        $link = $_GET['link'];
        $sql = pg_query($con, "SELECT * FROM projectgroup WHERE pglink='$link'");
        $result=pg_fetch_assoc($sql);
        $id = $result['pgid'];
        $c = $result['pgstatus'];
        $status=array("Product Design","CTP Processing", "Proofing", "Mass Production", "Delivery", "Completed");
        $color =array();
        for ($i=0;$i<=5;$i++){
            if ($status[$i] != $c){
                array_push($color, "done");
            }
            else {
                array_push($color, "done", "blank", "blank", "blank", "blank", "blank");
                break;
            }
        }
    }
    else{
        echo '<meta http-equiv="refresh" content="0;url=index.php">';
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="site.css">
    <title>Tracking Progress | RJ DIZON PRINTING PRESS</title>
  </head>
  <body>
    <div class="container">
      <div class="button_indexback">
        <a href="#" onClick="history.go(-1)">
            <button type="button" name="button" class="button_indexback1">BACK</button>
        </a>
      </div>
      <div class="indexlogo">
        <img src="img/logo.png" class="indexlogoimg">
      </div>
      <br>
      <div class="maintitle3">
        ORDER TRACKER<br>
      </div>
      <div class="title_indextrackingnumber">
        Tracking Number<br>
      </div>
      <div class="indextrackingnumber">
        <?=strtoupper($result['pglink'])?>
      </div>
      <div class="title_indexorderinformation">
        Order Progress Information
      </div>

      <div class="container_trackinginformation">
        <div class="container_container_trackinginformation">
            <table>
                <tr>
                  <td class="c1">Customer Name:
                  <td class="c1-1" style="width:50%;" >&nbsp; <?=$result['pgcustomer'];?>
                <tr>
                  <td class="c1">Date Ordered:
                  <td class="c1-1" style="width:50%;">&nbsp; <?=$result['pgdate'];?><br>
            </table>
        </div>
        <div class="title_developmentcycle">
          Current Progress<br>
        </div>
        <div class="container_currentprogress">
          <center><table>
            <tr>
              <td style="width: 150px;">
                <img src="img/productdesign.png">
                </td>
                <?php if($color[1] == "done") { ?>
                    <td>
                    <img src="img/blueDash.png">
                    </td>
                    <td style="width: 150px;">
                    <img src="img/ctpProcessing.png"></td>
                <?php } else { ?>
                    <td>
                    <img src="img/whiteDash.png">
                    </td>
                    <td style="width: 150px;">
                    <img src="img/ctpProcessing.png"></td>
                <?php } ?>
                <?php if($color[2] == "done") { ?>
                    <td>
                    <img src="img/blueDash.png">
                    </td>
                    <td style="width: 150px;">
                    <img src="img/proofing.png"></td>
                <?php } else { ?>
                    <td>
                    <img src="img/whiteDash.png">
                    <td style="width: 150px;">
                    <img src="img/proofing.png"></td>
                <?php } ?>
                <?php if($color[3] == "done") { ?>
                    <td>
                    <img src="img/blueDash.png">
                    </td>
                    <td style="width: 150px;">
                    <img src="img/massproduction.png"></td>
                <?php } else { ?> 
                    <td>
                    <img src="img/whiteDash.png">
                    </td>
                    <td style="width: 150px;">
                    <img src="img/massproduction.png"></td>
                <?php } ?>
                <?php if($color[4] == "done") { ?>
                    <td>
                    <img src="img/blueDash.png">
                    </td>
                    <td style="width: 150px;">
                    <img src="img/delivery.png"></td>
                <?php } else { ?> 
                    <td>
                    <img src="img/whiteDash.png">
                    </td>
                    <td style="width: 150px;">
                    <img src="img/delivery.png"></td> 
                <?php } ?>
            </tr>
            <tr style="text-align:center">
            <?php
              $c1 = array();
              for ($i=1;$i<=4;$i++){
                if ($color[$i] == "done"){
                  array_push($c1, "#222831");
                }
                else {
                    array_push($c1, "#00adb5", "#00adb5", "#00adb5", "#00adb5", "#00adb5", "#00adb5");
                    break;
                }
              } 
            ?>
              <td>Product Design</td>
              <td></td>
              <td style="color: <?=$c1[0]?>">CTP Processing</td>
              <td></td>
              <td style="color: <?=$c1[1]?>">Proofing</td>
              <td></td>
              <td style="color: <?=$c1[2]?>">Mass Production</td>
              <td></td>
              <td style="color: <?=$c1[3]?>">Delivery</td>
            </tr>
          </table>
          <?php if ($result['pgstatus']=="Complete"){
            echo "<p class='status' style='color:green'>COMPLETE</p>";
          } else {?>
          <p class="status"><?=strtoupper($result['pgstatus']);?></p> 
          <?php } 
                $prog = $result['pgstatus'];
                $rows = pg_query($con, "SELECT * FROM projecttask WHERE pgID=$id and taskprogress='$prog'");
                $count = pg_num_rows($rows);
                $query = pg_query($con, "SELECT * FROM projecttask WHERE pgID=$id and taskprogress='$prog' and taskstatus=1");
                $count1 = pg_num_rows($query);
                if ($count>0){
                      $percent = $count1/$count;
                      $percent = $percent * 100;
                ?>
                <div style="margin-bottom: 3rem; color: #222831;"><?=round($percent)?>%</div>
                <h3>Tasks to Do</h3>
                <div class="table">
                <?php
                  while ($row = pg_fetch_object($rows)){
                ?>
                    <div class="row">
                      <div style="text-align: right;">
                        <?php 
                        if ($row->taskstatus == 1){
                          echo "&#10004;";
                        }
                        else{
                          echo "&nbsp;";
                        }
                        ?>
                      </div>
                      <div style="width:5rem;"></div>
                      <div style="text-align:left;"><?=$row->taskdesc?></div>
                      <div style="width:5rem;"></div>
                      <div></div>
                    </div>
                <?php
                  }
                ?>
                </div>
                <?php } ?>
          </center>
          <div style="padding-bottom: 5rem;"></div>
        </div>
      </div>
    </div>
  </body>
</html>
