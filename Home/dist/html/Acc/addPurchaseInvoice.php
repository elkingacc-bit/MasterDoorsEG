<style>
 h1{font-size: 12px;}
 .rowTotal{background-color:#f8f9fa;}
</style>
<?php
 date_default_timezone_set("Africa/Cairo");
 include_once("../php/connection.php");
 $today = date('Y-m-d');
?>
<div class="table-responsive-lg">
 <h3 class="text-center text-body">إضافة فاتورة مشتريات</h3>

 <form id="addInvoiceForm">
  <div class="row mb-3">
   <div class="col-md-3">
    <label>تاريخ الفاتورة</label>
    <input type="date" name="invoiceDate" id="invoiceDate" class="form-control" value="<?php echo $today; ?>" required>
   </div>
  </div>

  <table class="table table-sm table-bordered table-striped w-100" id="invoiceItemsTable">
   <thead class="bg-primary text-center">
    <th class="col-1">SN</th>
    <th>الصنف</th>
    <th>العدد</th>
    <th>السعر</th>
    <th>الضريبة %</th>
    <th>الإجمالي</th>
    <th class="col-1"></th>
   </thead>
   <tbody>
    <tr class="itemRow">
     <td class="rowSn">1</td>
     <td><input type="text" name="itemName[]" class="form-control itemName" required></td>
     <td><input type="number" step="0.01" min="0" name="quantity[]" class="form-control quantity" value="1" required></td>
     <td><input type="number" step="0.01" min="0" name="price[]" class="form-control price" value="0" required></td>
     <td><input type="number" step="0.01" min="0" name="tax[]" class="form-control tax" value="0" required></td>
     <td class="rowTotal text-center">0.00</td>
     <td class="text-center"><button type="button" class="btn btn-sm btn-danger removeRow">X</button></td>
    </tr>
   </tbody>
   <tfoot>
    <th colspan="5" class="text-end">الإجمالي الكلي</th>
    <th id="grandTotal" class="text-center">0.00</th>
    <th></th>
   </tfoot>
  </table>

  <button type="button" class="btn btn-secondary" id="addRowBtn">إضافة صنف +</button>
  <button type="submit" class="btn btn-primary float-end" id="saveInvoiceBtn">حفظ الفاتورة</button>
 </form>
</div>

<script type="text/javascript">
 $(document).ready(function(){

  function calcRow(row){
   var qty = parseFloat(row.find('.quantity').val()) || 0;
   var price = parseFloat(row.find('.price').val()) || 0;
   var tax = parseFloat(row.find('.tax').val()) || 0;
   var total = (qty * price) * (1 + (tax/100));
   row.find('.rowTotal').text(total.toFixed(2));
   calcGrandTotal();
  }

  function calcGrandTotal(){
   var grand = 0;
   $('#invoiceItemsTable tbody .itemRow').each(function(){
    grand += parseFloat($(this).find('.rowTotal').text()) || 0;
   });
   $('#grandTotal').text(grand.toFixed(2));
  }

  function renumberRows(){
   $('#invoiceItemsTable tbody .itemRow').each(function(index){
    $(this).find('.rowSn').text(index + 1);
   });
  }

  $('#invoiceItemsTable').on('input', '.quantity, .price, .tax', function(){
   calcRow($(this).closest('.itemRow'));
  });

  $('#addRowBtn').on('click', function(){
   var newRow = $('#invoiceItemsTable tbody .itemRow:first').clone();
   newRow.find('input').val(function(i, val){
    return $(this).hasClass('quantity') ? 1 : ($(this).hasClass('price') || $(this).hasClass('tax') ? 0 : '');
   });
   newRow.find('.rowTotal').text('0.00');
   $('#invoiceItemsTable tbody').append(newRow);
   renumberRows();
  });

  $('#invoiceItemsTable').on('click', '.removeRow', function(){
   if($('#invoiceItemsTable tbody .itemRow').length > 1){
    $(this).closest('.itemRow').remove();
    renumberRows();
    calcGrandTotal();
   }
   else{
    alert('لازم يكون فيه صنف واحد على الأقل بالفاتورة');
   }
  });

  $('#addInvoiceForm').on('submit', function(e){
   e.preventDefault();
   $('#saveInvoiceBtn').prop('disabled', true).text('جارِ الحفظ...');
   $.ajax({
    url: 'dist/php/Acc/savePurchaseInvoice.php',
    type: 'POST',
    data: $(this).serialize(),
    dataType: 'json',
    success: function(res){
     $('#saveInvoiceBtn').prop('disabled', false).text('حفظ الفاتورة');
     if(res.status === 'success'){
      alert('تم حفظ الفاتورة رقم ' + res.invoiceId + ' بنجاح');
      $('#invoiceItemsTable tbody').html($('#invoiceItemsTable tbody .itemRow:first').clone());
      // reset to single empty row
      location.reload();
     }
     else{
      alert('حصل خطأ: ' + res.message);
     }
    },
    error: function(){
     $('#saveInvoiceBtn').prop('disabled', false).text('حفظ الفاتورة');
     alert('حصل خطأ فى الاتصال بالسيرفر');
    }
   });
  });

 });
</script>