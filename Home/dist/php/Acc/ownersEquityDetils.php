<?php
 include_once("../connection.php");

 // FIX: تنظيف مدخل $_POST قبل استخدامه في استعلام SQL (حماية من SQL Injection)
 $ownerCode = mysqli_real_escape_string($link, $_POST['ownerCode']);

 $netIncome = 0;
 $netOutcome = 0;
 $netProfit = 0;
 // FIX: يجب تهيئتها بقيمة افتراضية، وإلا فستكون Undefined variable
 // إذا لم يوجد الشريك في جدول partnershipRatio
 $percentage = 0;

 // Owner Name
 $sqlAccountantCodeData="SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = $ownerCode";  
 $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData) or die("ERROR_OWN:01 - " . mysqli_error($link));
 $accData=mysqli_fetch_assoc($queryAccountantCodeData);
 // FIX: تفادي Undefined index لو الكود غير موجود أصلاً في accountantcode
 $patrnerName = $accData['accountName'] ?? '-';

 // Revenue
 // FIX: إزالة transactionsYear من الـ SELECT لأنها عمود غير مُجمَّع بدون GROUP BY،
 // وهو ما كان يسبب: "In aggregated query without GROUP BY..." تحت وضع ONLY_FULL_GROUP_BY
 $sqlAccountantIncom="SELECT sum(`debtor`) as debt,sum(`creditor`) as cridt FROM `financialTransactions` WHERE `transactionCode` LIKE '4%'";  
 $queryAccountantIncome=mysqli_query($link,$sqlAccountantIncom) or die("ERROR_OWN:02 - " . mysqli_error($link));
 if(mysqli_num_rows($queryAccountantIncome) > 0){
  $tIncome=mysqli_fetch_assoc($queryAccountantIncome);
  // FIX: تعويض NULL الناتجة عن SUM() بصفر
  $netIncome=(($tIncome['cridt'] ?? 0) - ($tIncome['debt'] ?? 0));
 }
 // Expenses
 // FIX: نفس الإصلاح - إزالة transactionsYear غير المستخدمة من استعلام تجميعي
 $sqlAccountantOutcom="SELECT sum(`debtor`) as debt,sum(`creditor`) as cridt FROM `financialTransactions` WHERE `transactionCode` LIKE '3%'"; 
 $queryAccountantOutcome=mysqli_query($link,$sqlAccountantOutcom) or die("ERROR_OWN:03 - " . mysqli_error($link));
 if(mysqli_num_rows($queryAccountantOutcome) > 0){
  $tOucome=mysqli_fetch_assoc($queryAccountantOutcome);
  $netOutcome=(($tOucome['debt'] ?? 0) - ($tOucome['cridt'] ?? 0));      
 }
 // Income
 $netProfit=($netIncome - $netOutcome);
 // Partner Percentage
 $sqlPartnerData="SELECT `partnerPercentage` FROM `partnershipRatio` WHERE `partnersName` = '$ownerCode' ";  
 $queryPartnerData=mysqli_query($link,$sqlPartnerData) or die("ERROR_OWN:04 - " . mysqli_error($link));
 if(mysqli_num_rows($queryPartnerData) > 0){
  $partnerData=mysqli_fetch_assoc($queryPartnerData);
  $percentage=$partnerData['partnerPercentage'];
 }
 // Partner Profit
 $partnerProfit = ( ($netProfit * $percentage ) / 100 ); 
?>
<div class="table-responsive-lg">
 <input type="text" class="reportTitel" value="Owners Equity - <?php echo htmlspecialchars($patrnerName); ?>" style="display: none;">
 <table class="table table-sm w-100 table-bordered table-striped text-center">
  <thead>
   <th>Partner Name</th>
   <th>Partner percentage</th>
   <th>Partner Income</th>
   <th>Partner Profit</th>
  </thead>
  <tbody>
   <tr>
    <td><?php echo htmlspecialchars($patrnerName); ?></td>
    <td><?php echo htmlspecialchars($percentage); ?></td>
    <td><?php echo number_format( ( $netProfit ), 2); ?></td>
    <td><?php echo number_format( ( $partnerProfit ), 2); ?></td>
   </tr>
  </tbody>
 </table>
 <table class="table table-sm w-100 table-bordered table-striped text-center ownersEquityTable">
  <thead>
   <th>SN</th>
   <th>Date</th>
   <th>Fund</th>
   <th>withdrawal</th>
   <th>Description</th>
  </thead>
  <tbody>
   <?php
    // Partner Withdrawal
    $sqlPartnerWithdrawal="SELECT `transactionDate`,`income`,`withdrawal`,`description` FROM `cash_transaction` 
    WHERE `account` = $ownerCode ORDER BY `transactionDate` ASC";
    $queryPartnerWithdrawal=mysqli_query($link,$sqlPartnerWithdrawal)or die("ERROR_SNSC : 01 - " . mysqli_error($link));
    // FIX: تجميع الإجماليات يدوياً بدل الاعتماد على footerCallback الخاص بـ DataTable (تمت إزالته)
    $totalIncome = 0;
    $totalWithdrawal = 0;
    if(mysqli_num_rows($queryPartnerWithdrawal) > 0){
     $num=0;
     while($artnerWithdrawaltData=mysqli_fetch_assoc($queryPartnerWithdrawal)){
      $num++;
      $duedate = date("d/m/Y", strtotime($artnerWithdrawaltData['transactionDate']));
      $totalIncome += (float)$artnerWithdrawaltData['income'];
      $totalWithdrawal += (float)$artnerWithdrawaltData['withdrawal'];
      echo "<tr>
       <td>$num</td>
       <td>$duedate</td>
       <td>". number_format( ( $artnerWithdrawaltData['income']), 2)."</td>
       <td>". number_format( ( $artnerWithdrawaltData['withdrawal']), 2)."</td>
       <td>". htmlspecialchars($artnerWithdrawaltData['description']) ."</td>
      </tr>";
     }
    }
   ?>
  </tbody>
  <tfoot>
    <th></th>
    <th>Total</th>
    <th style="color:blue;"><?php echo number_format($totalIncome, 2); ?></th>
    <th style="color:blue;"><?php echo number_format($totalWithdrawal, 2); ?></th>
    <th></th>
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

  // ملحوظة: الجدول ده بيتحمل جوه مودال (Bootstrap) لسه مقفول وقت ما السكريبت ده بيتنفذ
  // (الـ .modal('show') بتتنادى بعد كده في الصفحة اللي فتحت المودال). لو عملنا
  // DataTable وهو لسه مخفي (display:none)، بتحسب عرض الأعمدة بصفر فيتولد اختلاف
  // بين رأس الجدول (thead) والصفوف بسبب scrollX/scrollY - وده اللي كان بيسبب
  // إن الهيدر مش موازي للجدول. فبننتظر لحد ما المودال يظهر فعلياً قبل ما نبني الجدول،
  // ولو المودال ظاهر بالفعل (لما تفتح شريك تاني والمودال مفتوح أصلاً) نبنيه فورًا.
  function initOwnersEquityTable(){
   $('.ownersEquityTable').DataTable({
   fixedHeader: false,
   scrollY:'30vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false,
   searching: false,
   ordering: false,
   dom: 'Bfrtip',
   buttons:[
    {
     extend: 'excel',
     text: 'Excel',
     extension: '.xlsx',
     title:titleName+datetime,
     filename: function () { return titleName },
     className: 'btn btn-secondary',
     footer: true,
    },
    {
     extend: 'pdf',
     text: 'PDF',
     title:titleName+datetime,
     filename: function () { return titleName },
     extension: '.pdf',
     className: 'btn btn-secondary',
     footer: true,
    },
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-secondary',
     title:titleName+datetime,
     footer: true,
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
  }

  if($('#ownersEquityModal').hasClass('show')){
   initOwnersEquityTable();
  }
  else{
   $('#ownersEquityModal').one('shown.bs.modal', initOwnersEquityTable);
  }
 });
</script>