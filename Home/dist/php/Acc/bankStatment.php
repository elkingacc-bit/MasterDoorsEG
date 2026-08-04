<style>
 h1{font-size: 12px;}
</style>
<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");

 // ---- 1) قراءة وتنقية المدخلات ----
 $myBank = isset($_POST['bank']) ? trim($_POST['bank']) : '';
 $sDate  = isset($_POST['startDate']) ? trim($_POST['startDate']) : '';
 $eDate  = isset($_POST['endDate'])   ? trim($_POST['endDate'])   : '';

 // التأكد أن bank رقم فعلاً (كان يُستخدم بدون علامات اقتباس داخل SQL بدون أي تحقق)
 if (!ctype_digit($myBank)) {
     echo "<div class='alert alert-danger text-center'>كود الحساب غير صالح</div>";
     exit;
 }
 $myBank = (int)$myBank;

 // التحقق من صحة التواريخ قبل استخدامها
 if ($sDate === '' || $eDate === '' || strtotime($sDate) === false || strtotime($eDate) === false) {
     echo "<div class='alert alert-danger text-center'>تاريخ البداية أو النهاية غير صالح</div>";
     exit;
 }
 $sDate = date('Y-m-d', strtotime($sDate));
 $eDate = date('Y-m-d', strtotime($eDate));

 $startDate = date('Y-m-d', strtotime($sDate . ' - 1 days'));

 // ---- اسم الحساب عبر prepared statement ----
 $stmt = mysqli_prepare($link, "SELECT `accountName` FROM `accountantcode` WHERE `accountCode` = ?");
 mysqli_stmt_bind_param($stmt, 'i', $myBank);
 mysqli_stmt_execute($stmt);
 $resFirstLevel = mysqli_stmt_get_result($stmt);
 $firstLevel = $resFirstLevel ? mysqli_fetch_assoc($resFirstLevel) : null;
 mysqli_stmt_close($stmt);

 if (!$firstLevel) {
     echo "<div class='alert alert-danger text-center'>لم يتم العثور على الحساب المطلوب</div>";
     exit;
 }
 $bankName = $firstLevel['accountName'];

 echo "
  <input value='" . htmlspecialchars($bankName) . " Statement From " . htmlspecialchars($sDate) . " To " . htmlspecialchars($eDate) . "' class='reportTitel' hidden>
  <h3 class='text-center text-body'>" . htmlspecialchars($bankName) . " Statement From " . htmlspecialchars($sDate) . " To " . htmlspecialchars($eDate) . "</h3>
 ";
?>
<div class="table-responsive-lg">
 <table class="table table-sm table-bordered table-striped w-100 myTableBank">
  <thead  class="bg-primary text-center">
   <th class="col-1">SN</th>
   <th>Date</th>
   <th>Deposit</th>  
   <th>Withdrawal</th>
   <th>Balance</th>
   <th>Description</th>
  </thead>
  <tbody>
   <?php
    // ---- 2) أول تاريخ حركة لهذا الحساب ----
    $stmt = mysqli_prepare($link, "SELECT `transactionsDate` FROM `financialTransactions` WHERE `transactionCode` = ? ORDER BY transactionsDate ASC LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $myBank);
    mysqli_stmt_execute($stmt);
    $resStatmentStart = mysqli_stmt_get_result($stmt);
    $statmentStart = $resStatmentStart ? mysqli_fetch_assoc($resStatmentStart) : null;
    mysqli_stmt_close($stmt);

    if ($statmentStart) {
        $srartWorkDate = $statmentStart['transactionsDate'];
        if ($srartWorkDate > $sDate) {
            $startBalance = 0;
        } else {
            $stmt = mysqli_prepare($link, "SELECT (SUM(`debtor`) - SUM(`creditor`)) as balance
                FROM `financialTransactions`
                WHERE `transactionsDate` < ? AND `transactionCode` = ?");
            mysqli_stmt_bind_param($stmt, 'si', $sDate, $myBank);
            mysqli_stmt_execute($stmt);
            $resStartBalance = mysqli_stmt_get_result($stmt);
            $statmentStartBalance = mysqli_fetch_assoc($resStartBalance);
            // SUM() على مجموعة فارغة يرجّع NULL
            $startBalance = $statmentStartBalance['balance'] !== null ? (float)$statmentStartBalance['balance'] : 0;
            mysqli_stmt_close($stmt);
        }
    } else {
        $startBalance = 0;
    }

    // ---- 3) الحركات ضمن الفترة عبر prepared statement ----
    $stmt = mysqli_prepare($link, "SELECT `transactionsDate`,`debtor`,`creditor`,`description`,`transactionCode`
        FROM `financialTransactions`
        WHERE `transactionsDate` BETWEEN ? AND ? AND `transactionCode` = ?");
    mysqli_stmt_bind_param($stmt, 'ssi', $sDate, $eDate, $myBank);
    mysqli_stmt_execute($stmt);
    $queryStatment = mysqli_stmt_get_result($stmt);

    $sn = 0;
    $accountBalance = $startBalance;

    // ---- رصيد الافتتاح: نفس تنسيق باقي الصفوف (number_format) ----
    echo "<tr>
      <td></td>
      <td>" . htmlspecialchars($startDate) . "</td>
      <td></td>
      <td></td>
      <td>" . number_format($startBalance, 2) . "</td>
      <td>Opening Balance</td>
     </tr>";

    if ($queryStatment) {
        while ($statment = mysqli_fetch_assoc($queryStatment)) {
            $sn++;
            $debtor  = (float)($statment['debtor'] ?? 0);
            $creditor = (float)($statment['creditor'] ?? 0);
            $accountBalance += $debtor;
            $accountBalance -= $creditor;

            // ---- توحيد تنسيق التاريخ مع كاش ستيتمنت (d/m/Y) ----
            $duedate = date("d/m/Y", strtotime($statment['transactionsDate']));

            echo "<tr>
      <td>$sn</td>
      <td>$duedate</td>
      <td>" . number_format($debtor, 2) . "</td>
      <td>" . number_format($creditor, 2) . "</td>
      <td>" . number_format($accountBalance, 2) . "</td>
      <td>" . htmlspecialchars($statment['description'] ?? '') . "</td>
     </tr>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<tr><td colspan='6' class='text-danger'>خطأ في قراءة الحركات</td></tr>";
    }
   ?>
  </tbody>
  <tfoot>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th><?php echo number_format($accountBalance, 2); ?></th>
    <th>Close Balance</th>
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
  var table = $('.myTableBank').DataTable({
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
      columns: [0,1,2,3,4,5]
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
      columns: [0,1,2,3,4,5]
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
      columns: [0,1,2,3,4,5]
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
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
            
    
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
            Number((pageTotal).toFixed(1)).toLocaleString()).css("color","blue");   
            
        

            
        }

   });




});
</script>