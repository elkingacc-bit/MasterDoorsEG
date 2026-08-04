<style>
 h1{font-size: 12px;}
</style>
<div class="table-responsive-lg">
 <table class='table table-sm table-bordered table-striped myTableInvismentYear w-100'>
  <thead class='bg-primary text-center'>
   <th class="col-1">Group</th>
   <th class="col-1">Serial</th>
   <th class="col-1">Date</th>
   <th class="col-1">Category</th>
   <th class="col-2">Buy</th>
   <th class="col-1">Quantity</th>
   <th class="col-2">Sale</th>
   <th class="col-1">Quantity</th>
   <th class="col-1">Balance</th>
   <th class="col-2">Profit</th>
  </thead>
  <tbody>
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("../connection.php");
    $myDate=date('Y-m-d');
    $gn=0;
    for ($categoryNum=10; $categoryNum < 16 ; $categoryNum++) { 
     $gn++;
     if($categoryNum == 10){
      $category="أراضى";
     }
     else if($categoryNum == 11){
      $category="عقارات";
     }
     else if($categoryNum == 12){
      $category="أسهم";
     }
     else if($categoryNum == 13){
      $category="ذهب";
     }
     else if($categoryNum == 14){
      $category="عملات";
     }
     else if($categoryNum == 15){
      $category="سيارات";
     }
     // Get Invesment Total Buy
     $sqlInvesmentTotalBuy="SELECT sum(`quantaty`) as countItem, sum(`buyingAmount`) as buying FROM `investment` 
     WHERE `investmentGroup` = $categoryNum  AND `salesAmount` = 0";
     $queryInvesmentTotalBuy=mysqli_query($link,$sqlInvesmentTotalBuy)or die("ERROR_SNSC : 01");
     if($invesmentTotalBuy=mysqli_fetch_assoc($queryInvesmentTotalBuy)){
      $buyTotalCount=$invesmentTotalBuy['countItem'];
      $buyTotalPrice=$invesmentTotalBuy['buying'];
     }
     else{
      $buyTotalCount = 0;
      $buyTotalPrice = 0;
     }
     // Get Invesment Total Sales
     $sqlInvesmentTotalSales="SELECT sum(`quantaty`) as countItem , sum(`salesAmount`) as saleing FROM `investment` 
     WHERE `investmentGroup` = $categoryNum AND `buyingAmount` = 0";
     $queryInvesmentTotalSales=mysqli_query($link,$sqlInvesmentTotalSales)or die("ERROR_SNSC : 01");
     if($invesmentTotalSales=mysqli_fetch_assoc($queryInvesmentTotalSales)){
      $salesTotalCount=$invesmentTotalSales['countItem'];
      $salesTotalPrice=$invesmentTotalSales['saleing'];
     }
     else{
      $salesTotalCount = 0;
      $salesTotalPrice = 0;
     }
     //
     if($buyTotalPrice == 0 ){
      $buyUnitPrice=0;
     }
     else{
      $buyUnitPrice=($buyTotalPrice / $buyTotalCount);
     }
     //
     if($salesTotalPrice == 0 ){
      $salesUnitPrice=0;
     }
     else{
      $salesUnitPrice=($salesTotalPrice / $salesTotalCount);
     }
     $avilablCount=($buyTotalCount - $salesTotalCount);
     $bProfit=($salesTotalPrice -$buyTotalPrice);
     if($avilablCount > 0){
      $avilablPrice=($avilablCount * $buyUnitPrice);
      $netProfit=($bProfit+$avilablPrice);
     }
     else{
      $netProfit=$bProfit;
     }
      
$sqlAccountantOutcom="SELECT sum(`debtor`) as debt,sum(`creditor`) as cridt FROM `financialTransactions` 
WHERE `transactionCode` LIKE '6%'"; 
$queryAccountantOutcome=mysqli_query($link,$sqlAccountantOutcom);
$tOucomeCount=mysqli_num_rows($queryAccountantOutcome);
if($tOucomeCount > 0){
$tOucome=mysqli_fetch_assoc($queryAccountantOutcome);
$netInstallment=($tOucome['debt'] - $tOucome['cridt']);      
}
else{
$netInstallment = 0;
}




     // Get Invesment Transaction
     $balance=0;
     $sqlInvesment="SELECT `transactionDate`,`quantaty`,`buyingAmount`,`salesAmount`,`investmentGroup`,`description` FROM `investment` 
     WHERE `investmentGroup` = $categoryNum ORDER BY `transactionDate` ASC";
     $queryInvesment=mysqli_query($link,$sqlInvesment)or die("ERROR_SNSC : 01");
     $sn=0;
     $profit=0;
     While($invesmentData=mysqli_fetch_assoc($queryInvesment)){
      $sn++;
      if($invesmentData['salesAmount'] == 0){
       $buyCount=$invesmentData['quantaty'];
       $salesCount=0;
      }
      else if($invesmentData['buyingAmount'] == 0){
       $salesCount=$invesmentData['quantaty'];
       $buyCount=0;
      }
      $balance+=$buyCount;
      $balance-=$salesCount;
      echo"<tr>
       <td>$gn</td>
       <td>$sn</td>
       <td>$invesmentData[transactionDate]</td>
       <td>$category</td>
       <td>".number_format(($invesmentData['buyingAmount']), 2)."</td>
       <td>$buyCount</td>
       <td>".number_format(($invesmentData['salesAmount']), 2)."</td>
       <td>$salesCount</td>
       <td>$balance</td>
       <td></td>
      </tr>";
     }
     if($salesTotalCount > 0 OR $buyTotalCount > 0){
      echo"<tr class='bg-info'>
       <td>$gn</td>
       <td>$sn</td>
       <td>$myDate</td>
       <td>$category</td>
       <td>".number_format(($buyTotalPrice), 2)."</td>
       <td>$buyTotalCount</td>
       <td>".number_format(($salesTotalPrice), 2)."</td>
       <td>$salesTotalCount</td>
       <td>$avilablCount</td>
       <td>".number_format(($netProfit), 2)."</td>
      </tr>";
     }
    }
   ?>
  </tbody>
  <tfoot>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   </tfoot>
  </table>

 </div>
<script type="text/javascript">
 $(document).ready(function(){
  var currentdate = new Date(); 
  var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();          
  var table = $('.myTableInvismentYear').DataTable( {
    search: false,
   fixedHeader: false,
             scrollY:'40vh',
             scrollX: true,
             scrollCollapse: true,
             paging: false, 
             order:[[0, "asc"]], 
         
   dom: 'Bfrtip',
       buttons: [
       
       {
            extend: 'excel',
            text: 'Excel',
            extension: '.xlsx',
            title:'Invisment_Year '+datetime,
            filename: function () {
            return "Invisment_Year" },
            className: 'btn btn-secondary',
            exportOptions: {
                
              columns: [  0,1,2,3,4,5,6,7,8,9]
            },
            footer: false,
            
        },
        
        {
            extend: 'pdf',
            text: 'PDF',
            title:'Invisment_Year '+datetime,
             filename: function () {
            return "Invisment_Year" },
            extension: '.pdf',
            className: 'btn btn-secondary',
            exportOptions: {
                
              columns: [  0,1,2,3,4,5,6,7,8,9]
            },
            footer: false,
            
        },
        
    {
      extend: 'print',
      text: 'Print',
      className: 'btn btn-secondary',
      title:'{Master Doors EG} | Invisment_Year '+datetime,
      footer: true,
       exportOptions: {
           
                   columns: [ 0,1,2,3,4,5,6,7,8,9]
               } ,          
customize: function ( win ) {
    $(win.document.body)
       
    .css( {'font-size':'8pt',  'text-align': 'left'} ).prepend('<img src="dist/img/logoMarker.png" style="position:absolute; top:2cm; left:30%; opacity: 0.1; filter: alpha(opacity=15); width: 350px; height:400px" />');
    $(win.document.body).find( 'table' )
    .addClass( 'compact' )
    .css( {'font-size' :'inherit',  'text-align': 'left'} );
  },
    }
 ],          
 

   });



  });
 



</script>