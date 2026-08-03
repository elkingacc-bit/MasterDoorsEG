<?php
 date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 $ajaxPost = $_POST['customerRId'];
 
 	$sqlGetCusts="SELECT  `customername`, `customercode`, `activity`, `area` FROM `customers`
	WHERE `customersid` = $ajaxPost";
	$queryGetCusts=mysqli_query($link,$sqlGetCusts)or die("ERROR :01-AU_AU_S");
	$resGetCusts = mysqli_fetch_assoc($queryGetCusts);

?>
<style>
 
</style>
<input type='text' id='RowId' name='RowId' value='<?php echo $ajaxPost;?>' style='display:none;'/>
<input type='text' id='CustCode' name='CustCode' value='<?php echo $resGetCusts['customercode'];?>' 
style='display:none;'/>
   <table class="table">
      <tr class="bg-warning">
            <th>Customer Name</th>
            <th>Customer Place</th>
            <th>Activity</th>
	</tr>
	<tr>
      <td><input type="text" placeholder="Name" id="newCustomerName" class="form-control"
      value="<?php echo $resGetCusts['customername'];?>"/></td>
      <td>
       <select class="form-control newCustomerTypes" id="customerArea" >
       <option value="<?php echo $resGetCusts['area'];?>"><?php echo $resGetCusts['area'];?></option>
       <option value="القاهرة" title=" الكبرى القاهرة ">القاهرة</option>
       <option value="الجيزة" title=" الكبرى القاهرة ">الجيزة</option>
       <option value="القليوبية" title=" الكبرى القاهرة ">القليوبية</option>
       <option value="الإسكندرية" title="الإسكندرية">الإسكندرية</option>
       <option value="البحيرة" title="الإسكندرية">البحيرة</option>
       <option value="مطروح" title="الإسكندرية">مطروح</option>
       <option value="الدقهلية" title="الدلتا">الدقهلية</option>
       <option value="كفر الشيخ" title="الدلتا"> كفر الشيخ </option>
       <option value="الغربية" title="الدلتا">الغربية</option>
       <option value="المنوفية" title="الدلتا">المنوفية</option>
       <option value="دمياط" title="الدلتا"> دمياط</option>
       <option value="بورسعيد" title="القناة">بورسعيد</option>
       <option value="الإسماعيلية" title="القناة">الإسماعيلية</option>
       <option value="السويس" title="القناة">السويس</option>
       <option value="الشرقية" title="القناة">الشرقية</option>
       <option value="شمال سيناء" title="القناة">شمال سيناء</option>
       <option value="جنوب سيناء" title="القناة">جنوب سيناء</option>
       <option value="بني سويف" title="الصعيد">بني سويف</option>
       <option value="المنيا" title="الصعيد">المنيا</option>
       <option value="الفيوم" title="الصعيد">الفيوم</option>
       <option value="أسيوط" title="الصعيد">أسيوط</option>
       <option value="الوادي الجديد" title="الصعيد">الوادي الجديد</option>
       <option value="سوهاج" title="الصعيد">سوهاج</option>
       <option value="قنا" title="الصعيد">قنا</option>
       <option value="البحر الأحمر" title="الصعيد">البحر الأحمر</option>
       <option value="الأقصر" title="الصعيد">الأقصر</option>
       <option value="أسوان" title="الصعيد">أسوان</option>
       
       </select>
     </td>
     <td><input type="text" placeholder="Activity" list="CustActivityList" id="customeractivity" class="form-control" value="<?php echo $resGetCusts['activity'];?>"/>
       <datalist id="CustActivityList">
       </datalist>
     </td>
   </tr>
   <tr>    
       <td colspan="3" align="center"> <button class="btn btn-success" id="saveEdirCustomer">Save</button></td>
  </tr>    
      </table>
 <script type="text/javascript">
 $(document).ready(function() {
	 
	$("#CustActivityList").load("dist/php/CheckAllCustActivity.php");

$("#saveEdirCustomer").click(function(){
	

		var CustRowId = $("#RowId").val();
		var customerName = $("#newCustomerName").val();
		customerName = customerName.replace(/^\s+|\s+$|\s+(?=\s)/g, "").replace(/[^A-Z0-9]+/ig, " ");
		var custArea = $("#customerArea").val();
		var custActivity = $("#customeractivity").val();
		var custCode = $("#CustCode").val();

	 if(customerName == "" || null )
		{
			alert('missing field');
			$('#newCustomerName').css("border-color","red");
			setTimeout(function(){
           		$('#newCustomerName').css("border-color","#EBEBEB");
				$("#newCustomerName").focus();				
				}, 1500);
								
			
		}
		
		else if(custArea == "" || null )
		{
			alert('missing field');
			$('#customerArea').css("border-color","red");
			setTimeout(function(){
           		$('#customerArea').css("border-color","#EBEBEB");
				$("#customerArea").focus();				
				}, 1500);
								
			
		}
		
	else if(custActivity == "" || null )
		{
			alert('missing field');
			$('#customeractivity').css("border-color","red");
			setTimeout(function(){
           		$('#customeractivity').css("border-color","#EBEBEB");
				$('#customeractivity').focus();				
				}, 1500);
								
			
		}
		
	else
		{
			
		$.ajax({
			url:"dist/php/saveEditCustomer.php",
			type:"POST",
			data: { CustomerN:customerName, CustAreaN:custArea,CustActivityN:custActivity,CRowId:CustRowId,OldCode:custCode},
			beforeSend: function(){
				
				$("#saveEdirCustomer").prop('disabled', true);
				},
				
			success: function(doneEditCustomer){
				if(doneEditCustomer == 0)
					{
						alert("Customer Name Is Already existing in Database.!");
						$("#saveEdirCustomer").prop('disabled', false);
						$('#newCustomerName').css("border-color","red");
						setTimeout(function(){
							$('#newCustomerName').css("border-color","#EBEBEB");
							$("#newCustomerName").focus();				
							}, 1500);
					}
					else if(doneEditCustomer == 1)
					{
						alert("Data Saved");
						setTimeout(function(){				
							$("#saveEdirCustomer").prop('disabled', false);
      					}, 1500);
						$("#editCust").click();
					}
					else if(doneEditCustomer == 9)
					{
						alert("Sorry Session expired please re-login again");
						
						setTimeout(function(){
						var ref1 = "/Maintenance_Tracker/";
                    	window.location.href= ref1;
											
						}, 1500);
					}
					else
					{
						$("#saveEdirCustomer").prop('disabled', false);
						alert(doneEditCustomer);
					}
			}
		});
	}
		return false;
		});
		
    });
 </script>
