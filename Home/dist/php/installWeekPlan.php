<!doctype html>
<html>
	<div class="modal-header">
        <h5 class="modal-title">Add New Installation Plan</h5>
       <button class="btn btn-xs  close">
        <i class='fa fa-close' aria-hidden='true' style='font-size:20px;color:#0275d8; font-weight:bold'>
        </i>
        </button>
        
      </div>
       <div class="modal-body ">
        <table class="table table-sm" style="width:100%">
    	<thead class="bg-warning">
        	<th>Choose PO</th>
            <th>Date</th>
        </thead>
        <tbody>
        	 <td >
            	<input type="text" class="form-control allValidPO" style="width:" id="allValidPO" 
                list="AllCustPo" autocomplete="off"/>
                <datalist id="AllCustPo" ></datalist>
            </td>
            <td>
            	<input type="date" id="PalnDate" class="form-control PalnDate" />
            </td>
         </tbody>   
		</table>
        <center>
        	<button class="btn btn-sm btn-success" id="saveAddPlanBTN">Save</button>
        </center>
        <br>
        <br>
      </div>
  <script type="text/javascript">
  $(document).ready(function() {
    
$('#AllCustPo').load("dist/php/allCustPOForPlan.php");
	
	
	$("#saveAddPlanBTN").click(function(){
		
		var dateSelected = $("#PalnDate").val();
		var PoGetItemsVal = $("#allValidPO").val();
		var dataPo = {};
			$("#AllCustPo option").each(function(i,el) {  
  			 dataPo[$(el).data("value")] = $(el).val();
			});
		console.log(dataPo, $("#AllCustPo option").val());
	
		var PoId = $('#AllCustPo [value="' + PoGetItemsVal + '"]').data('value');
		
	
		//alert(PoGetItemsID);
		
		var PoChosenValideate2 = $('#AllCustPo [value="' + PoGetItemsVal + '"]');
	if(PoChosenValideate2.length <= 0)
	   {
			alert('Please Choose Valid Customer name / PO Number form the list');
			$("#allValidPO").css("border-color","red");
		  setTimeout(function(){
		   $("#allValidPO").css("border-color","#EBEBEB");    						
		   $("#allValidPO").val('');	
		   $("#allValidPO").focus();							
		  }, 1500);
		}
	 else if(dateSelected == "")
	 {
		 alert('Please Choose date');
			$("#PalnDate").css("border-color","red");
		  setTimeout(function(){
		   $("#PalnDate").css("border-color","#EBEBEB");    						
		   $("#PalnDate").val('');	
		   $("#PalnDate").focus();							
		  }, 1500);
	 }
	 else
	 {
		 $.ajax({
			 	
				url:"dist/php/saveNewinstallPlan.php",
				type:"POST",
				data:{PoIdTable:PoId,chooseDate:dateSelected},
				beforeSend: function(){
						$("#AllCustPo").prop("disabled",true);	
					},
				success: function(doneAddNewPlan){
				   if(doneAddNewPlan == 0)
					{
						alert("Same Project assigned to same Date before");
						$("#saveAddPlanBTN").prop("disabled",false);
						$("#PalnDate").css("border-color","red");
						  setTimeout(function(){
						   $("#PalnDate").css("border-color","#EBEBEB");    						
						  }, 1500);	
						
					}
					else if(doneAddNewPlan == 1)
					{
						 alert("Data Saved");
					  $('.ShowHWData').html('');
					  $(".myModal").modal('toggle');
					  $("#saveAddPlanBTN").prop("disabled",false);
						setTimeout(function(){				
								
							$('.data_display').html('');
	 						$('.data_display').load('dist/html/homePage.html');
      					}, 500);
					}
					else
					{
						alert(doneAddNewPlan);
						$("#saveAddPlanBTN").prop("disabled",false);	
					}
				}
			 
			 });
	 }
		
		return false;
		});
	
});
  
  
  </script>      
</html>