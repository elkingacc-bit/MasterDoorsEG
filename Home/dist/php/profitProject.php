<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 $jopRowId=$_POST['project'];
 // Get Job Id
 $sqlJop="SELECT `PoNum`,`poVal`,`jobidref` FROM `customerpo` WHERE `poId` = $jopRowId";
 $queryJop=mysqli_query($link,$sqlJop)or die("ERROR_SNSC : 02");
 $jopIdData=mysqli_fetch_assoc($queryJop);
 $poNum=$jopIdData['PoNum'];
 $jopId=$jopIdData['jobidref'];
 $poValue=$jopIdData['poVal'];
 // Get Order Data
 $sqlSalesJop="SELECT `projectName`,`customer`,`jobtype` FROM `job` WHERE `jobId` = $jopId";
 $querySalesJop=mysqli_query($link,$sqlSalesJop)or die("ERROR_SNSC : 02");
 $jopData=mysqli_fetch_assoc($querySalesJop);
 $projectName=$jopData['projectName'];
 $customerCode=$jopData['customer'];
 $orderType=$jopData['jobtype'];
 //customerCode
 $sqlCheckCustN="SELECT `customername` FROM `customers` WHERE `customercode` = $customerCode";
 $queryCheckCustN=mysqli_query($link,$sqlCheckCustN)or die("ERROR :01-CCN_ACC_S".mysqli_error($link));
 $customerData=mysqli_fetch_assoc($queryCheckCustN);
 $customerName= $customerData['customername'];
 echo "<input type='text' class='project_Expencess invisible' value='customerName / $projectName Expencess'>";
 $poSN=2;
 $projectExpencess=0;
?>
<div class="table-responsive-lg">
 <table class="table table-sm table-bordered table-striped projectExpen w-100">
  <thead  class="bg-primary text-center">
   <th>#</th>
   <th>Income</th>
   <th>Outcome</th>
   <th>Description</th>
   <th>Account</th>
  </thead>
  <tbody>
   <?php
    # Po Data
    echo"<tr>
     <td>1</td>
     <td>".number_format(($poValue), 2)."</td>
     <td></td>
     <td>Order Value</td>
     <td>$customerName</td>
    </tr>";
    # Collect
    $sqlProjectCollect="SELECT sum(`income`) as totalCollect FROM `cash_transaction` WHERE `poNum` = $jopId";
    $queryProjectCollect=mysqli_query($link,$sqlProjectCollect)or die("ERROR_SNSC : 02");
    $resProjectCollect=mysqli_fetch_assoc($queryProjectCollect);
    echo"<tr>
     <td>2</td>
     <td>".number_format(($resProjectCollect['totalCollect']), 2)."</td>
     <td></td>
     <td>Customer Collect</td>
     <td>$customerName</td>
    </tr>";
    // Payment Cach
    $sqlSalesJop="SELECT sum(`withdrawal`) as payment,`account` FROM `cash_transaction` WHERE `poNum` = $jopId GROUP BY `account`";
    $querySalesJop=mysqli_query($link,$sqlSalesJop)or die("ERROR_SNSC : 02");
    While($jopData=mysqli_fetch_assoc($querySalesJop)){
     $poSN++;
     //accountatCode
     $sqlAccount="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $jopData[account]";
     $queryAccount=mysqli_query($link,$sqlAccount)or die("ERROR LOA_S:01");
     if($accountData=mysqli_fetch_assoc($queryAccount)){
      $account= $accountData['accountName'];
      $projectExpencess+=$jopData['payment'];
      echo"<tr>
       <td>$poSN</td>
       <td></td>
       <td>".number_format(($jopData['payment']), 2)."</td>
       <td>Project Payment</td>
       <td>$account</td>
      </tr>";
     }
    }
    # Manufactur Order    
    $sqlOrderItems="SELECT sum(`totalAmout`) as purValue,`supplierid` FROM `purchasesorder` WHERE `jobref` = $jopId GROUP BY `supplierid`";
    $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
    if($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
     $poSN ++ ;
     $projectExpencess+=$itemsData['purValue'];
     echo"<tr>
      <td>$poSN </td>
      <td></td>
      <td>".number_format(($itemsData['purValue']), 2)."</td> 
      <td>Manufacture</td>
      <td>Purchases</td>
     </tr>";      
    }
    # Get Hardware Cost
    $sqlOrderItems="SELECT `id` FROM `itemoffer` WHERE `jobref` = $jopId";
    $queryOrderItemsData=mysqli_query($link,$sqlOrderItems)or die("ERROR_SNSC : 01");
    if(mysqli_num_rows($queryOrderItemsData) > 0){
     $poSN ++ ;
     $totalPrice=0;
     WHILE($itemsData=mysqli_fetch_assoc($queryOrderItemsData)){
      $rowId=$itemsData['id'];
      $sqlOrderItemsHW="SELECT `descripcode`,sum(`descripquantity`) as num FROM `offerproperties` WHERE  `ioidref` = $rowId GROUP BY `descripcode`";
      $queryOrderItemsDataHW=mysqli_query($link,$sqlOrderItemsHW)or die("ERROR_SNSC : 01");
      if($itemsHW=mysqli_fetch_assoc($queryOrderItemsDataHW)){
       $desCode=$itemsHW['descripcode'];
       //Stock Cost
       $sqlStockCost="SELECT `amount` FROM `warehouse` WHERE `description` = $desCode ";
       $queryStockCost=mysqli_query($link,$sqlStockCost)or die("ERROR :01-CCN_ACC_S".mysqli_error($link));
       if($resStockCost=mysqli_fetch_assoc($queryStockCost)){
        $uprice=($itemsHW['num'] * $resStockCost['amount']);
        $totalPrice+=$uprice;        
       }        
      }
     }
     $projectExpencess+=$totalPrice;
     echo"<tr>
      <td>$poSN </td>
      <td></td>
      <td>".number_format(($totalPrice), 2)."</td> 
      <td> Hardware</td>
      <td></td>
     </tr>";
    }
    $subValue=($poValue - $projectExpencess);
    $stutsValue2=($subValue / $poValue) * 100;
    $stutsValue=number_format(($stutsValue2), 2);
    echo"<tr class='bg-info text-white font-weight-bolder text-center'>
     <td>99</td>
     <td>".number_format(($poValue), 2)."</td>
     <td>".number_format(($projectExpencess), 2)."</td> 
     <td></td>
     <td>Total</td>
    </tr>";
    if($stutsValue <= 0){
     $valueBar="<div class='progress'>
      <div class='progress-bar progress-bar-striped bg-success' role='progressbar' style='width: $stutsValue%' aria-valuenow='$stutsValue' aria-valuemin='0' aria-valuemax='10'>
      $stutsValue%</div>
     </div>";
    }
    else if($stutsValue<= 25){
     $valueBar="<div class='progress'>
      <div class='progress-bar progress-bar-striped bg-danger' role='progressbar' style='width: $stutsValue%' aria-valuenow='$stutsValue' aria-valuemin='0' aria-valuemax='100'>
       $stutsValue%
      </div>
     </div>";
    }
    else if($stutsValue <= 50){
     $valueBar="<div class='progress'>
      <div class='progress-bar progress-bar-striped bg-warning' role='progressbar' style='width: $stutsValue%' aria-valuenow='$stutsValue' aria-valuemin='0' aria-valuemax='100'>
       $stutsValue%
      </div>
     </div>";
    }
    else if($stutsValue <= 75){
     $valueBar="<div class='progress'>
      <div class='progress-bar progress-bar-striped bg-info' role='progressbar' style='width: $stutsValue%' aria-valuenow='$stutsValue' aria-valuemin='0' aria-valuemax='100'>
       $stutsValue%
      </div>
     </div>";
    }
    else if($stutsValue <= 100){
     $valueBar="<div class='progress'>
      <div class='progress-bar progress-bar-striped bg-success' role='progressbar' style='width: $stutsValue%' aria-valuenow='$stutsValue' aria-valuemin='0' aria-valuemax='100'>
       $stutsValue%
      </div>
     </div>";
    }
    $subCollect=($resProjectCollect['totalCollect'] - $projectExpencess);
    $stutsCollect2=( $subCollect / $poValue ) * 100;
    $stutsCollect=number_format(($stutsCollect2), 2);
    if($stutsCollect <= 0){
     $collectBar="<div class='progress'>
      <div class='progress-bar progress-bar-striped bg-success' role='progressbar' style='width: $stutsCollect%' aria-valuenow='$stutsCollect' aria-valuemin='0' aria-valuemax='10'>
       $stutsCollect%
      </div>
     </div>";
    }
    else if($stutsCollect <= 25){
     $collectBar="<div class='progress'>
      <div class='progress-bar progress-bar-striped bg-danger' role='progressbar' style='width: $stutsCollect%' aria-valuenow='$stutsCollect' aria-valuemin='0' aria-valuemax='100'>
       $stutsCollect%
      </div>
     </div>";
    }
    else if($stutsCollect <= 50){
     $collectBar="<div class='progress'>
      <div class='progress-bar progress-bar-striped bg-warning' role='progressbar' style='width: $stutsCollect%' aria-valuenow='$stutsCollect' aria-valuemin='0' aria-valuemax='100'>
       $stutsCollect%
      </div>
     </div>";
    }
    else if($stutsCollect <= 75){
     $collectBar="<div class='progress'>
      <div class='progress-bar progress-bar-striped bg-info' role='progressbar' style='width: $stutsCollect%' aria-valuenow='$stutsCollect' aria-valuemin='0' aria-valuemax='100'>
       $stutsCollect%
      </div>
     </div>";
    }
    else if($stutsCollect <= 100){
     $collectBar="<div class='progress'>
      <div class='progress-bar progress-bar-striped bg-success' role='progressbar' style='width: $stutsCollect%' aria-valuenow='$stutsCollect' aria-valuemin='0' aria-valuemax='100'>
       $stutsCollect%
      </div>
     </div>";
    }
   ?>
  </tbody>
  <tfoot>
   <th>project Stuts</th>
   <th>Profit By Order Value</th>
   <th><?php echo $valueBar;?></th>
   <th>Profit By project Collect</th>
   <th><?php echo  $collectBar;?></th>
  </tfoot>
 </table>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  var titleName = $('.project_Expencess').val();
  var currentdate = new Date(); 
  var datetime = currentdate.getDate() + "/"
               + (currentdate.getMonth()+1)  + "/" 
               + currentdate.getFullYear() + " @ "  
               + currentdate.getHours() + ":"  
               + currentdate.getMinutes() + ":" 
               + currentdate.getSeconds();
  var table = $('.projectExpen').DataTable({
   fixedHeader: false,
   scrollY:'35vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false, 
   order:[[0, "asc"]],
   dom: 'Bfrtip',
   buttons:[
    {
     extend: 'excel',
     text: 'Excel',
     extension: '.xlsx',
     title:titleName+datetime,
     filename: function () {
      return titleName 
     },
     className: 'btn btn-primary',
     exportOptions:{
      columns: [  0,1,2,3,4]
     },
     footer: false,
    },
    {
     extend: 'pdf',
     text: 'PDF',
     title:titleName+datetime,
     filename: function () {
      return titleName
     },
     extension: '.pdf',
     className: 'btn btn-info',
     exportOptions:{
      columns: [  0,1,2,3,4]
     },
     footer: false,
    },
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-primary',
     title:'Master Doors EG |'+titleName+datetime,
     footer: true,
     exportOptions: {
      columns: [ 0,1,2,3,4]
     },          
     customize: function ( win ) {
      $(win.document.body)
      .css( {'font-size':'8pt',  'text-align': 'left'} )
      .prepend('<img src="dist/img/logoMarker.png" style="position:absolute;top:2cm;left:30%;opacity: 0.1;filter: alpha(opacity=15);width: 350px; height:400px" />');
      $(win.document.body).find( 'table' )
      .addClass( 'compact' )
      .css( {'font-size' :'inherit',  'text-align': 'left'} );
     },
    } 
   ],
  });
 });
</script>