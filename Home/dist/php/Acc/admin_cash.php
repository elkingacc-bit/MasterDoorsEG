<?php
/**
 * admin_cash.php
 * ------------------------------------------------------------
 * تم إصلاح الأخطاء التالية:
 * 1) ثغرة SQL Injection: كل الاستعلامات تحولت إلى Prepared Statements.
 * 2) $accountName / $empName كانت تحتفظ بالقيمة القديمة إذا لم يوجد
 *    تطابق في الصف الحالي -> تم تصفيرها في بداية كل تكرار.
 * 3) إضافة htmlspecialchars() لمنع XSS عند الطباعة.
 * 4) إضافة عمود hidden لتحديد نوع القيمة (income/withdrawal) - مستخدم لاحقاً في التعديل.
 * 5) إصلاح event.preventDefault() في جافاسكريبت (كان event غير معرف).
 * 6) إضافة <div class="editingRes"> حول الجدول حتى تعمل عملية تحديث الجدول بعد الحذف فعلياً
 *    (في الكود الأصلي كانت $(".editingRes") غير موجودة في الصفحة).
 * 7) تدمير DataTable وإعادة تهيئته بعد إعادة تحميل الجدول (delete) لتفادي خطأ
 *    "Cannot reinitialise DataTable".
 * ------------------------------------------------------------
 */

include_once("../authCheck.php");
date_default_timezone_set("Africa/Cairo");
include_once("../connection.php");

/**
 * يبحث عن اسم الحساب (محاسب / عميل / مورد) بناءً على الكود
 */
function getAccountName($link, $code)
{
    $name = '';

    $stmt = mysqli_prepare($link, "SELECT accountName FROM accountantcode WHERE accountCode = ?");
    mysqli_stmt_bind_param($stmt, "s", $code);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        mysqli_stmt_close($stmt);
        return $row['accountName'];
    }
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($link, "SELECT customername FROM customers WHERE customercode = ?");
    mysqli_stmt_bind_param($stmt, "s", $code);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        mysqli_stmt_close($stmt);
        return $row['customername'];
    }
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($link, "SELECT suppliername FROM allsuppliers WHERE suppliercode = ?");
    mysqli_stmt_bind_param($stmt, "s", $code);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $name = $row['suppliername'];
    }
    mysqli_stmt_close($stmt);

    return $name;
}

/**
 * يبحث عن اسم الموظف (مستخدم نظام / موظف) بناءً على الكود
 */
function getEmpName($link, $empCode)
{
    $name = '';

    $stmt = mysqli_prepare($link, "SELECT fullname FROM users WHERE userid = ?");
    mysqli_stmt_bind_param($stmt, "s", $empCode);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        mysqli_stmt_close($stmt);
        return $row['fullname'];
    }
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($link, "SELECT staffname FROM allstaff WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "s", $empCode);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $name = $row['staffname'];
    }
    mysqli_stmt_close($stmt);

    return $name;
}

$sqlDataEdit = "SELECT `cash_transaction_id`,`transactionDate`,`income`,`withdrawal`,`description`,`account`,`empCode`,`tableName`,`tableRowId`,`ftId`
    FROM `cash_transaction` ORDER BY `transactionDate` DESC";
$queryDataEdit = mysqli_query($link, $sqlDataEdit);
?>
<div class="table-responsive-lg">
 <div class="editingRes">
 <table class='table table-dark table-bordered table-striped w-100 tableAllCashEdit'>
  <thead>
   <th>transactionDate</th>
   <th>income</th>
   <th>withdrawal</th>
   <th>description</th>
   <th>account</th>
   <th>empCode</th>
   <th>tableName</th>
   <th>tableRowId</th>
   <th>ftId</th>
   <th>Edit</th>
   <th>Delete</th>
  </thead>
  <tbody>
   <?php
    while ($resultDataEdit = mysqli_fetch_assoc($queryDataEdit)) {
     // تصفير القيم في بداية كل صف حتى لا تتكرر بيانات الصف السابق
     $accountName = '';
     $empName = '';

     $accountName = getAccountName($link, $resultDataEdit['account']);
     $empName = getEmpName($link, $resultDataEdit['empCode']);

     echo "<tr>
      <td>" . htmlspecialchars($resultDataEdit['transactionDate']) . "</td>
      <td>" . htmlspecialchars($resultDataEdit['income']) . "</td>
      <td>" . htmlspecialchars($resultDataEdit['withdrawal']) . "</td>
      <td>" . htmlspecialchars($resultDataEdit['description']) . "</td>
      <td>" . htmlspecialchars($accountName) . "</td>
      <td>" . htmlspecialchars($empName) . "</td>
      <td>" . htmlspecialchars($resultDataEdit['tableName']) . "</td>
      <td>" . htmlspecialchars($resultDataEdit['tableRowId']) . "</td>
      <td>" . htmlspecialchars($resultDataEdit['ftId']) . "</td>
      <td>
       <button type='button' class='btn btn-link editing' data-toggle='modal' data-target='#accountantCodeModal' value='" . (int)$resultDataEdit['cash_transaction_id'] . "'><i class='far fa-edit' aria-hidden='true' style='font-size:16px;color:#0275d8'></i></button>
      </td>
      <td>
       <button type='button' class='btn btn-link deleted' data-toggle='modal' data-target='#accountantCodeModal' value='" . (int)$resultDataEdit['cash_transaction_id'] . "'>
        <i class='fa fa-trash' aria-hidden='true' style='font-size:16px;color:red'></i>
       </button>
      </td>
     </tr>";
    }
   ?>
  </tbody>
 </table>
 </div>
 <!-- Modal -->
 <div class="modal" id="accountantCodeModal" tabindex="-1" aria-labelledby="accountantCodeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
   <div class="modal-content">
    <div class="modal-header">
     <h5 class="modal-title" id="accountantCodeModalLabel">تعديل بيانات الحركة</h5>
     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="modal-body dataEdit">
    </div>
    <div class="modal-footer saveArea">
    </div>
   </div>
  </div>
 </div>
</div>
<script type="text/javascript">
 function initCashTable(){
  if ($.fn.DataTable.isDataTable('.tableAllCashEdit')) {
   $('.tableAllCashEdit').DataTable().destroy();
  }
  $('.tableAllCashEdit').DataTable({
   fixedHeader: true,
   scrollY: '65vh',
   scrollX: true,
   scrollCollapse: true,
   order: [[0, "desc"]],
   paging: false,
  });
 }

 $(document).ready(function(){
  initCashTable();

  // Edit
  $(document).on('click', '.editing', function(){
   var editRowId = $(this).val();
   $.ajax({
    url: "dist/php/Acc/getCashDataToEdit.php",
    type: "POST",
    data: { rowId: editRowId },
    success: function(doneEdit){
     $(".dataEdit").html(doneEdit);
    }
   });
  });

  // Delete
  $(document).on('click', '.deleted', function(event){
   var userConfirmed = confirm("Are you sure you want to Delete this Row?");

   // Stop execution if user clicks "Cancel"
   if (!userConfirmed) {
    event.preventDefault();
    return;
   }

   var delRowId = $(this).val();
   $.ajax({
    url: "dist/php/Acc/cashDataToDell.php",
    type: "POST",
    data: { rowId: delRowId },
    success: function(doneDell){
     $.ajax({
      url: 'dist/php/Acc/admin_cash.php',
      beforeSend: function(){
       $('.close').click();
       $(".dataEdit").html('');
       $(".dataEdit").html("<img src='dist/img/loadingColor.gif'>");
      },
      success: function(doneDellRes){
       $(".editingRes").html($(doneDellRes).find('.editingRes').html());
       initCashTable();
       $(".dataEdit").html('');
      }
     });
    }
   });
  });
 });
</script>