<style>
 h1{font-size: 12px;}
</style>
<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("connection.php");
 // asOfDate اختياري: لو مش مبعوت هيحسب الرصيد لغاية النهاردة
 $asOfDate = isset($_POST['asOfDate']) && $_POST['asOfDate'] !== '' ? $_POST['asOfDate'] : date('Y-m-d');
 echo"
  <input value='Items Balance Report as of $asOfDate' class='reportTitel' hidden>
  <h3 class='text-center text-body'>رصيد الأصناف (المشتريات - المصروف) حتى تاريخ $asOfDate</h3>
 ";
?>
<div class="table-responsive-lg">
 <table class="table table-sm table-bordered table-striped myTableItemsBalance w-100">
  <thead class="bg-primary text-center">
   <th class="col-1">SN</th>
   <th>الصنف</th>
   <th>إجمالي الكمية المشتراة</th>
   <th>إجمالي الكمية المصروفة</th>
   <th>الرصيد المتاح</th>
   <th>متوسط سعر الشراء</th>
   <th>إجمالي قيمة المشتريات (شامل الضريبة)</th>
  </thead>
  <tbody>
   <?php
    $sqlBalance = "SELECT
      p.itemName,
      p.purchasedQty,
      p.avgPrice,
      p.totalValue,
      COALESCE(w.withdrawnQty, 0) AS withdrawnQty,
      (p.purchasedQty - COALESCE(w.withdrawnQty, 0)) AS remainingQty
     FROM (
      SELECT pit.`itemName`,
       SUM(pit.`quantity`) AS purchasedQty,
       AVG(pit.`price`) AS avgPrice,
       SUM((pit.`quantity` * pit.`price`) * (1 + (pit.`tax`/100))) AS totalValue
      FROM `purchase_invoice_items` pit
      INNER JOIN `purchase_invoices` pi ON pi.`id` = pit.`invoiceId`
      WHERE pi.`invoiceDate` <= '$asOfDate'
      GROUP BY pit.`itemName`
     ) p
     LEFT JOIN (
      SELECT wit.`itemName`, SUM(wit.`quantity`) AS withdrawnQty
      FROM `item_withdrawal_items` wit
      INNER JOIN `item_withdrawals` w ON w.`id` = wit.`withdrawalId`
      WHERE w.`withdrawDate` <= '$asOfDate'
      GROUP BY wit.`itemName`
     ) w ON w.itemName = p.itemName
     ORDER BY p.itemName ASC";
    $queryBalance = mysqli_query($link, $sqlBalance);
    $sn = 0;
    $grandTotalValue = 0;
    while($row = mysqli_fetch_assoc($queryBalance)){
     $sn++;
     $grandTotalValue += $row['totalValue'];
     echo"<tr>
      <td>$sn</td>
      <td>$row[itemName]</td>
      <td>".number_format(($row['purchasedQty']), 2)."</td>
      <td>".number_format(($row['withdrawnQty']), 2)."</td>
      <td>".number_format(($row['remainingQty']), 2)."</td>
      <td>".number_format(($row['avgPrice']), 2)."</td>
      <td>".number_format(($row['totalValue']), 2)."</td>
     </tr>";
    }
   ?>
  </tbody>
  <tfoot>
   <th></th>
   <th>الإجمالي</th>
   <th></th>
   <th></th>
   <th></th>
   <th></th>
   <th><?php echo number_format(($grandTotalValue), 2); ?></th>
  </tfoot>
 </table>
</div>
<script type="text/javascript">
  $(document).ready(function(){
  var titleName = $(".reportTitel").val();
  var currentdate = new Date(); 
  var datetime = currentdate.getDate() + "/"
               + (currentdate.getMonth()+1)  + "/" 
               + currentdate.getFullYear() + " @ "  
               + currentdate.getHours() + ":"  
               + currentdate.getMinutes() + ":" 
               + currentdate.getSeconds();
  var table = $('.myTableItemsBalance').DataTable({
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
     filename: function () { return titleName },
     className: 'btn btn-secondary',
     exportOptions:{ columns: [0,1,2,3,4,5,6] },
     footer: false,
    },
    {
     extend: 'pdf',
     text: 'PDF',
     title:titleName+datetime,
     filename: function () { return titleName },
     extension: '.pdf',
     className: 'btn btn-secondary',
     exportOptions:{ columns: [0,1,2,3,4,5,6] },
     footer: false,
    },
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-secondary',
     title:titleName+datetime,
     footer: true,
     exportOptions: { columns: [0,1,2,3,4,5,6] },          
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
   "footerCallback": function(row, data, start, end, display){
    var api = this.api(), data;
    var intval = function(i){
     return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
    };
    var pageTotal = api
    .column( 6, {page: 'current'} )
    .data()
    .reduce(function(a, b){ return intval(a) + intval(b); }, 0 );
    $(api.column( 6 ).footer() ).html(
     Number((pageTotal).toFixed(2)).toLocaleString())
    .css("color","blue");   
   }
  });
 });
</script>