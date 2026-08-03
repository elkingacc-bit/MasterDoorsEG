<div class="table-responsive-lg text-center">
 <table class='table table-sm table-bordered table-striped'>
  <thead class='bg-info'>
   <th>Items</th>
   <th>Quantity</th>
   <th>Unit Price</th>
   <th>Total Price</th>
  </thead>
  <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("connection.php");
    $jopId=$_POST['jopId'];
    $sqlSalesJop="SELECT `startDate`,`localref`,`customer`,`offerValue`,`jobtype`,`jobreceivables`,`endDate`,`invoice`, `salesman` FROM `job` WHERE `jobId` = $jopId";
    $querySalesJop=mysqli_query($link,$sqlSalesJop)or die("ERROR_SNSC : 02");
    $totalInv=0;
    While($jopData=mysqli_fetch_assoc($querySalesJop)){
    $orderType=$jopData['jobtype'];
    if($orderType == "Automatic"){
     $sqlOrderItems="SELECT `id`,`doortype`,`doorspecs`,`motorspecs`,`doorprice`,`doorqty`,`totalprice`,`jobid`,`ref` FROM `autodoorsoffer` WHERE `jobid` = $jopId";
     $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
      WHILE($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
       $totalInv += $itemsData['totalprice'];
       echo "<tr>
        <td>$itemsData[doortype]</td>
        <td>$itemsData[doorqty]</td>
        <td>$itemsData[doorprice]</td>
        <td>$itemsData[totalprice]</td>
       </tr>";
      }
    }
    else if($orderType == "Doors" ){
     $sqlOrderItems="SELECT `id`,`itemname`,`itemtype`,`msquerprice`,`itemqty`,`totalprice` FROM `itemoffer` WHERE `jobref` = $jopId";
     $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
     WHILE($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){

       $rowId=$itemsData['id'];
       $totalInv += $itemsData['totalprice'];
       echo "<tr>
        <td class='text-left'>$itemsData[itemtype]</td>
        <td>$itemsData[itemqty]</td>
        <td>$itemsData[msquerprice]</td>
        <td>$itemsData[totalprice]</td>
       </tr>";      
       

       //HW
       $sqlOrderItemsHW="SELECT sum(`totalprice`) as hwPrice FROM `offerproperties` WHERE  `ioidref` = $rowId";
       $queryOrderItemsDataHW=mysqli_query($link,$sqlOrderItemsHW)or die("ERROR_SNSC : 01");
       $itemsHW=mysqli_fetch_assoc($queryOrderItemsDataHW);
       $hardwarePrice=$itemsHW['hwPrice'];
       $totalInv += $itemsHW['hwPrice'];
       echo"<tr>
         <td class='text-right'>Hardware</td>
         <td></td>
         <td></td>
         <td>$hardwarePrice</td>
        </tr>";
       

      }
    }    
    else if($orderType == "Stock" ){
     $sqlOrderItems="SELECT `id`, `descripcode`,`descripqty`,`descripprice`,`totalprice`,`jobref`,`ref`,`whref` FROM `stockoffers` WHERE `jobref` = $jopId";
     $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
     while($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
      $totalInv += $itemsData['totalprice'];
      echo"<tr>
       <td>$itemsData[descripcode]</td>
       <td>$itemsData[descripqty]</td>
       <td>$itemsData[descripprice]</td>
       <td>$itemsData[totalprice]</td>
      </tr>";        
      }
    }

       // Maintenance Type  
       else if($orderType == "Maintenance" ){
         $sqlOrderItems="SELECT `type`, `price`, `typeqty`, `totalprice`, `jobid`, `ref`, `purchasesCost` FROM `maintoffers` WHERE `jobid` = $jopId";
        $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
        while($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
          $totalInv += $itemsData['totalprice'];
      echo"<tr>
       <td>$itemsData[type]</td>
       <td>$itemsData[typeqty]</td>
       <td>$itemsData[price]</td>
       <td>$itemsData[totalprice]</td>
      </tr>";              
        }


        
      }

    
     }
   ?>
  </tbody>
  <tfoot>
      <th></th>
      <th></th>
      <th>Total</th>
      <th><?php echo $totalInv;?> </th>      
  </tfoot>
 </table>
</div>