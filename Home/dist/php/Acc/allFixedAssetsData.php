<style>
 h1{font-size: 12px;}
 .bsTitleBar{background-color:#ffd400;text-align:center;font-weight:bold;font-size:1.4em;padding:10px;}
</style>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");

 $asOfDate = isset($_POST['asOfDate']) && $_POST['asOfDate'] !== '' ? $_POST['asOfDate'] : date('Y-m-d');

 // إهلاك بطريقة القسط الثابت (Straight-Line) - بيتحسب وقت العرض فقط، مفيش أي ترحيل تلقائي
 // لـ financialTransactions أو تعديل على الميزانية العمومية.
 $stmt = mysqli_prepare($link, "SELECT `fixedAssetId`,`assetName`,`assetCategory`,`accountCode`,`purchaseDate`,`purchaseCost`,`usefulLifeYears`,`salvageValue`,`status`,`disposalDate`,
   GREATEST(0, LEAST(TIMESTAMPDIFF(MONTH, `purchaseDate`, LEAST(?, COALESCE(`disposalDate`, ?))), `usefulLifeYears`*12)) as monthsElapsed
  FROM `fixedAssets`
  ORDER BY `purchaseDate` DESC");
 mysqli_stmt_bind_param($stmt, "ss", $asOfDate, $asOfDate);
 mysqli_stmt_execute($stmt);
 $result = mysqli_stmt_get_result($stmt);

 $rows = [];
 $totalCost = 0; $totalAccum = 0; $totalBook = 0;
 while ($r = mysqli_fetch_assoc($result)) {
  $cost = (float)$r['purchaseCost'];
  $salvage = (float)$r['salvageValue'];
  $usefulLife = (int)$r['usefulLifeYears'];
  $monthlyDepr = $usefulLife > 0 ? ($cost - $salvage) / ($usefulLife * 12) : 0;
  $accumDepr = $monthlyDepr * (int)$r['monthsElapsed'];
  $bookValue = $cost - $accumDepr;
  $rows[] = array_merge($r, ['monthlyDepr' => $monthlyDepr, 'accumDepr' => $accumDepr, 'bookValue' => $bookValue]);
  $totalCost += $cost; $totalAccum += $accumDepr; $totalBook += $bookValue;
 }
 mysqli_stmt_close($stmt);

 echo "<input value='Fixed Assets Register - As Of $asOfDate' class='reportTitel' hidden>";
?>
<div class="table-responsive-lg">
 <center>
  <div class="row justify-content-center mb-3">
   <div class="col-md-3">
    <label>حتى تاريخ (As Of)</label>
    <input type="date" id="faAsOfDate" class="form-control" value="<?php echo htmlspecialchars($asOfDate); ?>">
   </div>
   <div class="col-md-2 d-flex align-items-end">
    <button class="btn btn-info w-100" id="faRefresh">تحديث</button>
   </div>
  </div>
 </center>
 <div class="bsTitleBar">Fixed Assets Register سجل الأصول الثابتة</div>
 <table class="table table-sm table-bordered table-striped myTableFixedAssets w-100 text-center">
  <thead class="bg-primary">
   <th>Asset الأصل</th>
   <th>Category الفئة</th>
   <th>Account الحساب</th>
   <th>Purchase Date تاريخ الشراء</th>
   <th>Cost التكلفة</th>
   <th>Useful Life العمر</th>
   <th>Monthly Depr. إهلاك شهري</th>
   <th>Accum. Depr. مجمع الإهلاك</th>
   <th>Book Value القيمة الدفترية</th>
   <th>Status الحالة</th>
   <th>Actions</th>
  </thead>
  <tbody>
   <?php foreach ($rows as $r): ?>
    <tr>
     <td><?php echo htmlspecialchars($r['assetName']); ?></td>
     <td><?php echo htmlspecialchars($r['assetCategory'] ?? ''); ?></td>
     <td><?php echo htmlspecialchars($r['accountCode'] ?? ''); ?></td>
     <td><?php echo htmlspecialchars($r['purchaseDate']); ?></td>
     <td><?php echo number_format((float)$r['purchaseCost'], 2); ?></td>
     <td><?php echo (int)$r['usefulLifeYears']; ?> yrs</td>
     <td><?php echo number_format($r['monthlyDepr'], 2); ?></td>
     <td><?php echo number_format($r['accumDepr'], 2); ?></td>
     <td><?php echo number_format($r['bookValue'], 2); ?></td>
     <td>
      <?php if ($r['status'] === 'Disposed'): ?>
       <span class="badge badge-secondary">Disposed <?php echo htmlspecialchars($r['disposalDate']); ?></span>
      <?php else: ?>
       <span class="badge badge-success">Active</span>
      <?php endif; ?>
     </td>
     <td>
      <button class="btn btn-sm btn-secondary faSchedule" data-id="<?php echo (int)$r['fixedAssetId']; ?>" title="Depreciation Schedule"><i class="fas fa-calendar-alt"></i></button>
      <?php if ($r['status'] !== 'Disposed'): ?>
       <button class="btn btn-sm btn-warning faDispose" data-id="<?php echo (int)$r['fixedAssetId']; ?>" title="Dispose"><i class="fas fa-box"></i></button>
      <?php endif; ?>
      <button class="btn btn-sm btn-danger faDelete" data-id="<?php echo (int)$r['fixedAssetId']; ?>" title="Delete"><i class="fas fa-trash"></i></button>
     </td>
    </tr>
   <?php endforeach; ?>
  </tbody>
  <tfoot>
   <th colspan="4">Grand Total الإجمالي</th>
   <th><?php echo number_format($totalCost, 2); ?></th>
   <th></th>
   <th></th>
   <th><?php echo number_format($totalAccum, 2); ?></th>
   <th><?php echo number_format($totalBook, 2); ?></th>
   <th colspan="2"></th>
  </tfoot>
 </table>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  $("#faRefresh").click(function(){
   var newDate = $("#faAsOfDate").val();
   $(".data_display").load("dist/php/Acc/allFixedAssetsData.php", {asOfDate: newDate});
   return false;
  });

  $(".faSchedule").click(function(){
   var assetId = $(this).data('id');
   $(".data_display").load("dist/php/Acc/depreciationSchedule.php", {assetId: assetId});
   return false;
  });

  $(".faDispose").click(function(){
   var assetId = $(this).data('id');
   var disposalDate = prompt("Disposal Date (YYYY-MM-DD):", new Date().toISOString().slice(0,10));
   if(disposalDate === null || disposalDate === ""){ return false; }
   $.ajax({
    url:'dist/php/Acc/disposeFixedAsset.php',
    type:"POST",
    data:{fixedAssetId:assetId, disposalDate:disposalDate},
    success: function(response){
     if(response == 1){ $("#faRefresh").click(); }
     else { alert(response); }
    }
   });
   return false;
  });

  $(".faDelete").click(function(){
   var assetId = $(this).data('id');
   if(!confirm("Delete this asset?")){ return false; }
   $.ajax({
    url:'dist/php/Acc/deleteFixedAsset.php',
    type:"POST",
    data:{fixedAssetId:assetId},
    success: function(response){
     if(response == 1){ $("#faRefresh").click(); }
     else { alert(response); }
    }
   });
   return false;
  });

  var titleName = $(".reportTitel").val();
  var currentdate = new Date();
  var datetime = currentdate.getDate() + "/"
               + (currentdate.getMonth()+1)  + "/"
               + currentdate.getFullYear() + " @ "
               + currentdate.getHours() + ":"
               + currentdate.getMinutes() + ":"
               + currentdate.getSeconds();
  $('.myTableFixedAssets').DataTable({
   fixedHeader: false,
   scrollY:'50vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false,
   searching: false,
   ordering: false,
   info: false,
   dom: 'Bfrtip',
   buttons:[
    {
     extend: 'excel',
     text: 'Excel',
     extension: '.xlsx',
     title:titleName+datetime,
     filename: function () { return titleName },
     className: 'btn btn-secondary',
     exportOptions:{ columns: [0,1,2,3,4,5,6,7,8,9] },
     footer: true,
    },
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-secondary',
     title:titleName+datetime,
     footer: true,
     exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9] },
     customize: function ( win ) {
      $(win.document.body).css( {'font-size':'8pt',  'text-align': 'left'} );
      $(win.document.body).find( 'table' ).addClass( 'compact' ).css( {'font-size' :'inherit',  'text-align': 'left'} );
     },
    }
   ],
  });
 });
</script>
