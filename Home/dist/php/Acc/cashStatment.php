<style>
 h1{font-size: 12px;}
</style>
<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");

 // ---- 1) قراءة وتنقية المدخلات ----
 $sDate = isset($_POST['startDate']) ? trim($_POST['startDate']) : '';
 $eDate = isset($_POST['endDate'])   ? trim($_POST['endDate'])   : '';

 // تحقق أن التواريخ صحيحة فعلاً قبل استخدامها في أي استعلام
 if ($sDate === '' || $eDate === '' || strtotime($sDate) === false || strtotime($eDate) === false) {
     echo "<div class='alert alert-danger text-center'>تاريخ البداية أو النهاية غير صالح</div>";
     exit;
 }

 // تنسيق موحّد بعد التأكد من صحتها
 $sDate = date('Y-m-d', strtotime($sDate));
 $eDate = date('Y-m-d', strtotime($eDate));

 $accountBalance = 0;
 $startDate = date('Y-m-d', strtotime($sDate . ' - 1 days'));

 echo "
  <input value='Cash Statement From " . htmlspecialchars($sDate) . " To " . htmlspecialchars($eDate) . "' class='reportTitel' hidden>
  <h3 class='text-center text-body'>Cash Statement From " . htmlspecialchars($sDate) . " To " . htmlspecialchars($eDate) . "</h3>
 ";
?>
<div class="table-responsive-lg">
 <table class="table table-sm table-bordered table-striped myTableCash w-100">
  <thead  class="bg-primary text-center">
   <th class="col-1">SN</th>
   <th>Date</th>
  <th>Deposit</th>  
   <th>Withdraw</th>
   <th>Balance</th>
   <th>Description</th>
   <th class="col-2">Transaction By</th>
  </thead>
  <tbody>
   <?php
    // ---- 2) أول تاريخ حركة (لا حاجة لـ prepare لأنه بدون مدخلات مستخدم) ----
    $sqlStatmentStart = "SELECT `transactionDate` FROM `cash_transaction` WHERE `statmentRef` LIKE '116100%' ORDER BY `transactionDate` ASC LIMIT 1";
    $queryStatmentStart = mysqli_query($link, $sqlStatmentStart);
    if (!$queryStatmentStart) {
        echo "<tr><td colspan='7' class='text-danger'>خطأ في قراءة بيانات الحركة الأولى</td></tr>";
        $startBalance = 0;
    } elseif ($statmentStart = mysqli_fetch_assoc($queryStatmentStart)) {
        $srartWorkDate = $statmentStart['transactionDate'];
        if ($srartWorkDate > $sDate) {
            $startBalance = 0;
        } else {
            // ---- Prepared statement بدل التضمين المباشر لـ $sDate ----
            $stmt = mysqli_prepare($link, "SELECT (SUM(`income`) - SUM(`withdrawal`)) as balance
                FROM `cash_transaction`
                WHERE `transactionDate` < ? AND `statmentRef` LIKE '116100%'");
            mysqli_stmt_bind_param($stmt, 's', $sDate);
            mysqli_stmt_execute($stmt);
            $resStartBalance = mysqli_stmt_get_result($stmt);
            $statmentStartBalance = mysqli_fetch_assoc($resStartBalance);
            // SUM() على مجموعة فارغة يرجع NULL، لذلك نؤمّن القيمة الافتراضية
            $startBalance = $statmentStartBalance['balance'] !== null ? (float)$statmentStartBalance['balance'] : 0;
            mysqli_stmt_close($stmt);
        }
    } else {
        $startBalance = 0;
    }

    echo "<tr>
     <td></td>
     <td> $startDate </td>
     <td></td>
     <td></td>
     <td>" . number_format($startBalance, 2) . "</td>
     <td>Opening Balance</td>
     <td></td>
    </tr>";

    // ---- 3) الحركات ضمن الفترة عبر prepared statement ----
    $stmt = mysqli_prepare($link, "SELECT `transactionDate`,`income`,`withdrawal`,`description`,`statmentRef`,`account`,`empCode`
        FROM `cash_transaction`
        WHERE `statmentRef` LIKE '116100%' AND `transactionDate` BETWEEN ? AND ?
        ORDER BY `transactionDate` ASC");
    mysqli_stmt_bind_param($stmt, 'ss', $sDate, $eDate);
    mysqli_stmt_execute($stmt);
    $queryStatment = mysqli_stmt_get_result($stmt);

    $sn = 0;
    $accountBalance = $startBalance;

    if ($queryStatment) {
        while ($statment = mysqli_fetch_assoc($queryStatment)) {
            $sn++;
            $income     = (float)($statment['income'] ?? 0);
            $withdrawal = (float)($statment['withdrawal'] ?? 0);
            $accountBalance += $income;
            $accountBalance -= $withdrawal;

            // ---- اسم الموظف عبر prepared statement ----
            $empCode = (int)$statment['empCode'];
            $stmtEmp = mysqli_prepare($link, "SELECT `fullname` FROM `users` WHERE `userid` = ?");
            mysqli_stmt_bind_param($stmtEmp, 'i', $empCode);
            mysqli_stmt_execute($stmtEmp);
            $resEmp = mysqli_stmt_get_result($stmtEmp);
            $accData = mysqli_fetch_assoc($resEmp);
            $fullname = $accData['fullname'] ?? '';
            mysqli_stmt_close($stmtEmp);

            $duedate = date("d/m/Y", strtotime($statment['transactionDate']));

            // ---- تنقية المخرجات من HTML/XSS ----
            echo "<tr>
      <td>$sn</td>
      <td>$duedate</td>
      <td>" . number_format($income, 2) . "</td>
      <td>" . number_format($withdrawal, 2) . "</td>
      <td>" . number_format($accountBalance, 2) . "</td>
      <td>" . htmlspecialchars($statment['description'] ?? '') . "</td>
      <td>" . htmlspecialchars($fullname) . "</td>
     </tr>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<tr><td colspan='7' class='text-danger'>خطأ في قراءة الحركات</td></tr>";
    }
   ?>
  </tbody>
  <tfoot>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th>Account Balance</th>
    <th><?php echo number_format($accountBalance, 2); ?></th>
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
  var table = $('.myTableCash').DataTable({
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
     className: 'btn btn-secondary',
     exportOptions:{
      columns: [0,1,2,3,4,5,6]
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
     className: 'btn btn-secondary',
     exportOptions:{
      columns: [0,1,2,3,4,5,6]
     },
     footer: false,
    },
    {
     extend: 'print',
     text: 'Print',
     className: 'btn btn-secondary',
     title:titleName+datetime,
     footer: true,
     exportOptions: {
      columns: [0,1,2,3,4,5,6]
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
   "footerCallback": function(row, data, start, end, display){
    var api = this.api(), data;
    var intval = function(i){
     return typeof i === 'string' ?
     i.replace(/[\$,]/g, '')*1:
     typeof i === 'number' ?
     i : 0;
    };
    // Col A
    total = api
    .column( 2 )
    .data()
    .reduce(function(a, b){
     return intval(a) + intval(b);
    }, 0 );
    pageTotal = api
    .column( 2, {page: 'current'} )
    .data()
    .reduce(function(a, b){
     return intval(a) + intval(b);
    }, 0 );
    $(api.column( 2 ).footer() ).html(
     Number((pageTotal).toFixed(1)).toLocaleString())
    .css("color","blue");   
    //Col B
    total = api
    .column( 3 )
    .data()
    .reduce(function(a, b){
     return intval(a) + intval(b);
    }, 0 );
    pageTotal = api
    .column( 3, {page: 'current'} )
    .data()
    .reduce(function(a, b){
     return intval(a) + intval(b);
    }, 0 );
    $(api.column( 3 ).footer() ).html(
     Number((pageTotal).toFixed(1)).toLocaleString())
    .css("color","blue");   
   }
  });
 });
</script>