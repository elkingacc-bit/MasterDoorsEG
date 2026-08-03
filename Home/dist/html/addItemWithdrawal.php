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
 <h3 class="text-center text-body">صرف أصناف من المخزون</h3>

 <form id="withdrawForm">
  <div class="row mb-3">
   <div class="col-md-3">
    <label>تاريخ الصرف</label>
    <input type="date" name="withdrawDate" id="withdrawDate" class="form-control" value="<?php echo $today; ?>" required>
   </div>
  </div>

  <table class="table table-sm table-bordered table-striped w-100" id="withdrawItemsTable">
   <thead class="bg-primary text-center">
    <th class="col-1">SN</th>
    <th>الصنف</th>
    <th>الرصيد المتاح</th>
    <th>الكمية المصروفة</th>
    <th>البيان / السبب</th>
    <th class="col-1"></th>
   </thead>
   <tbody>
    <tr class="itemRow">
     <td class="rowSn">1</td>
     <td>
      <select name="itemName[]" class="form-control itemSelect" required>
       <option value="">-- اختر الصنف --</option>
      </select>
     </td>
     <td class="text-center availableBalance">-</td>
     <td><input type="number" step="0.01" min="0.01" name="quantity[]" class="form-control quantity" value="1" required></td>
     <td><input type="text" name="description[]" class="form-control description"></td>
     <td class="text-center"><button type="button" class="btn btn-sm btn-danger removeRow">X</button></td>
    </tr>
   </tbody>
  </table>

  <button type="button" class="btn btn-secondary" id="addRowBtn">إضافة صنف +</button>
  <button type="submit" class="btn btn-primary float-end" id="saveWithdrawBtn">حفظ الصرف</button>
 </form>
</div>

<script type="text/javascript">
 $(document).ready(function(){

  var itemsBalanceList = [];

  function loadItemsList(){
   $.ajax({
    url: 'dist/php/getItemsBalanceList.php',
    type: 'GET',
    dataType: 'json',
    success: function(res){
     if(res.status === 'success'){
      itemsBalanceList = res.items;
      $('.itemSelect').each(function(){
       fillSelectOptions($(this));
      });
     }
    }
   });
  }

  function fillSelectOptions(select){
   var current = select.val();
   select.find('option:not(:first)').remove();
   $.each(itemsBalanceList, function(i, item){
    var disabled = item.balance <= 0 ? 'disabled' : '';
    var opt = $('<option></option>')
     .attr('value', item.itemName)
     .attr('data-balance', item.balance)
     .attr(disabled ? 'disabled' : 'data-ok', disabled ? true : false)
     .text(item.itemName + ' (المتاح: ' + item.balance.toFixed(2) + ')');
    select.append(opt);
   });
   if(current){ select.val(current); }
  }

  function updateRowBalance(row){
   var select = row.find('.itemSelect');
   var selectedOption = select.find('option:selected');
   var balance = parseFloat(selectedOption.attr('data-balance')) || 0;
   row.find('.availableBalance').text(select.val() ? balance.toFixed(2) : '-');
   row.find('.quantity').attr('max', balance > 0 ? balance : 0);
  }

  function renumberRows(){
   $('#withdrawItemsTable tbody .itemRow').each(function(index){
    $(this).find('.rowSn').text(index + 1);
   });
  }

  $('#withdrawItemsTable').on('change', '.itemSelect', function(){
   updateRowBalance($(this).closest('.itemRow'));
  });

  $('#addRowBtn').on('click', function(){
   var newRow = $('#withdrawItemsTable tbody .itemRow:first').clone();
   newRow.find('.itemSelect').val('');
   newRow.find('.quantity').val(1);
   newRow.find('.description').val('');
   newRow.find('.availableBalance').text('-');
   $('#withdrawItemsTable tbody').append(newRow);
   renumberRows();
  });

  $('#withdrawItemsTable').on('click', '.removeRow', function(){
   if($('#withdrawItemsTable tbody .itemRow').length > 1){
    $(this).closest('.itemRow').remove();
    renumberRows();
   }
   else{
    alert('لازم يكون فيه صنف واحد على الأقل بعملية الصرف');
   }
  });

  $('#withdrawForm').on('submit', function(e){
   e.preventDefault();

   // تحقق مبدئى من العميل (client) قبل الإرسال: مفيش كمية أكبر من الرصيد المتاح
   var valid = true;
   var totalsByItem = {};
   $('#withdrawItemsTable tbody .itemRow').each(function(){
    var itemName = $(this).find('.itemSelect').val();
    var qty = parseFloat($(this).find('.quantity').val()) || 0;
    if(itemName){
     totalsByItem[itemName] = (totalsByItem[itemName] || 0) + qty;
    }
   });
   $.each(itemsBalanceList, function(i, item){
    if(totalsByItem[item.itemName] && totalsByItem[item.itemName] > item.balance){
     alert('الكمية المطلوبة من "' + item.itemName + '" أكبر من الرصيد المتاح (' + item.balance.toFixed(2) + ')');
     valid = false;
    }
   });
   if(!valid){ return; }

   $('#saveWithdrawBtn').prop('disabled', true).text('جارِ الحفظ...');
   $.ajax({
    url: 'dist/php/saveItemWithdrawal.php',
    type: 'POST',
    data: $(this).serialize(),
    dataType: 'json',
    success: function(res){
     $('#saveWithdrawBtn').prop('disabled', false).text('حفظ الصرف');
     if(res.status === 'success'){
      alert('تم حفظ عملية الصرف رقم ' + res.withdrawalId + ' بنجاح');
      location.reload();
     }
     else{
      alert('حصل خطأ: ' + res.message);
     }
    },
    error: function(){
     $('#saveWithdrawBtn').prop('disabled', false).text('حفظ الصرف');
     alert('حصل خطأ فى الاتصال بالسيرفر');
    }
   });
  });

  loadItemsList();
 });
</script>