<?php
    session_start();
    include("config.php");
    $id = $_GET['id'];
    $query=pg_query($con, "SELECT * FROM billingstatement WHERE bsid=$id");
    $result=pg_fetch_assoc($query);
    $pgid = $result['pgid'];
    $query1=pg_query($con, "SELECT * FROM projectgroup WHERE pgid=$pgid");
    $result1=pg_fetch_assoc($query1);
    $total = $result['bstotal'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Statement</title>
    <meta HTTP-EQUIV="REFRESH" CONTENT="600; URL='logout.php';">
</head>
<body> 
    <table>
        <tr>
            <td width="20%"> <img src="./img/oldlogo.png" width="100%">
            <td width="80%"> <center>
            <h1>RJ DIZON PRINTING PRESS</h1>
            2242 P.Binay St. Brgy. Bangkal, Makati City, Philippines 1233 <br/>
            Tel. Nos.: 986-8394 / Fax: 889-1269<br>
            E-mail: rjdizonprintingpress@yahoo.com<br>
            Ruben DJ. Dizon Jr. -Prop.<br>
            VAT Reg.Tin: 222-925-125-00000</td>
            <td width="20%"><center>
            <h1>BILLING STATEMENT</h1>
            <h2 style="color:red"><?=$result['bsserial']?></h2></td>
        </tr>
        <tr>
            <td colspan="2">BILLED TO: <?=$result['bscustomer']?></td>
            <td>DATE: <?=$result['bsdate']?></td>
        </tr>
        <tr>
            <td colspan="2">BUSINESS NAME/STYLE: <?=$result['bsbstyle']?> </td>
            <td>TIN: <?=$result['bstin']?></td>
        </tr>
        <tr>
            <td colspan="2">ADDRESS: <?=$result1['pgaddress']?></td>
            <td>P.O. No.: <?=$result1['pgpo']?></td>
        </tr>
    </table>
    <table width="100%" border=1 style="border-spacing: 0;">
        <tr>
            <td><b>Quantity</td>
            <td><b>Unit</td>
            <td><b>Description</td>
            <td><b>Unit Price</td>
            <td><b>Amount</td>
        </tr>
        <?php
            $rows=pg_query($con, "SELECT * FROM bsdetails WHERE bsid=$id");
            $i=1;
            while ($row = pg_fetch_object($rows)){
                $ppu = (int)$row->bsdprice;
                $q = (int)$row->bsdquantity;
                $amount = $ppu * $q
        ?>
        <tr>
            <td><?=$row->bsdquantity?></td>
            <td><?=$row->bsdunit?></td>
            <td><?=$row->bsddescription?></td>
            <td><?=$row->bsdprice;?> Pesos</td>
            <td><?=$amount?> Pesos</td>
        </tr>
        <?php
        $i+=1; 
            }
        $i = 10 - $i;
        for ($j=0;$j<=$i;$j++){
        ?>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td style="text-align:right">TOTAL AMOUNT PAYABLE</td>
            <td>&nbsp;</td>
            <td><?=$total?> Pesos</td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="40%">Prepared by:</td>
            <td width="20%">&nbsp;</td>
            <td>Received the above goods in good order and condition
        </tr>
        <tr>
            <td><center><?=$result['bscashier'];?></td>
            <td>&nbsp;</td>
            <td>By:______________________________
        </tr>
        <tr>
            <td style="border-top: 1px solid black"><center>NAME</td>
            <td>&nbsp;</td>
            <td><center>PRINT NAME OVER SIGNATURE
        </tr>
    </table>

</body>
</html>