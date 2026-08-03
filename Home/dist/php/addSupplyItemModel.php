<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 $SuppOrderRID = $_POST['ModelSuppORID'];
 $SuppOrderType = $_POST['MoadelOrderType'];
 $offerRowId = $_POST['JRID'];
 
	 $sqlGetOrderData = "SELECT `SuppCode`, `OrderNumber` FROM `supplierorder` 
	 WHERE `SOId` = $SuppOrderRID";
	$queryGetOrderData = mysqli_query($link,$sqlGetOrderData)or die("ERROR :01-ANJ_GCN_S");
	$resGetOrderData = mysqli_fetch_assoc($queryGetOrderData);
	
	$sqlGetSupplier="SELECT `suppliername` FROM `allsuppliers` WHERE `suppliercode` = 
	$resGetOrderData[SuppCode]";
	$queryGetSupplier=mysqli_query($link,$sqlGetSupplier)or die("ERROR :02-AU_AU_S");
	$resGetSupplier= mysqli_fetch_assoc($queryGetSupplier);	
	
	$sqlGetOfferNum="SELECT `projectName` FROM `job` WHERE `jobId` = $offerRowId";
			$queryGetOfferNum=mysqli_query($link,$sqlGetOfferNum)or die("ERROR :04-AU_AU_S");
			$resGetOfferNum= mysqli_fetch_assoc($queryGetOfferNum);	
	
	$suppName = $resGetSupplier['suppliername'];
	$OrderNo = $resGetOrderData['OrderNumber'];
	$OrderTyTitel = $resGetOrderData['OrderNumber'];
	$projectName = $resGetOfferNum['projectName'];
	
?>
 <div class="modal-header">
        <h5 class="modal-title">Add Supply order Item/s Supplier<span style="color:blue;">
        <b><?php echo $suppName; ?>
        </b></span> Supplier Order Number:&nbsp;<span style="color:blue;">
        <b><?php echo $OrderNo; ?></b></span><br>
         Order Type:&nbsp;<span style="color:blue;">
        <b><?php echo $SuppOrderType; ?></b></span>
        Project Name:&nbsp;<span style="color:blue;">
        <b><?php echo $projectName; ?></b></span>
        </h5>
  <!-- <button class="btn btn-xs btn-link close" data-toggle='tooltip' data-placement='top' title='Add New HW'
       id="AddMoreHw">
        <i class='fa fa-plus-square' aria-hidden='true' style='font-size:20px;color:#0275d8; font-weight:bold'>
        </i>
        <table class="table table-sm ItemTable" style="display:none" style="width:100%">
           
              <thead class="bg-info">
                <th>Type</th>
                <th>Name</th>
                <th>QTY</th>
                <th>Supply QTY</th>
              </thead>
              <tbody>
        		
          	  </tbody>
         </table>
  </button>-->
        
      </div>
       <div class="modal-body addNewSuppItem">
       
       <center>
       	<input style="width:30%" type="text" id="itemTypeDL" class="form-control" list="allItemInOffer"/>
        <datalist id="allItemInOffer"></datalist>
       </center>
       
       <div class="SelectResult">
 		</div>
 </div>
 <input type="text" value="<?php echo $SuppOrderRID;?>" style="display:none" id="SuppRowId"/>
  <input type="text" value="<?php echo $SuppOrderType;?>" style="display:none" id="OrderType"/>
  <input type="text" value="<?php echo $offerRowId;?>" style="display:none" id="JobId"/>
  <input type="text" style="display:none" id="itemRowIdM"/>
 
 
 <script type="text/javascript">
 $(document).ready(function() {
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	  
var SuppORId = $("#SuppRowId").val();
var OrderTy = $("#OrderType").val();	
var JRowId = $("#JobId").val();
//alert(SuppORId);
$("#allItemInOffer").load('dist/php/addSupplyItemToOrder.php',{SORID:SuppORId, oType:OrderTy,jobTableRId:JRowId});
		
	$("#itemTypeDL").change(function(){
		var selectedItemName = $(this).val();
		
		//alert(JRowId);
		if(selectedItemName != "")
		{
			var data1 = {};
				$("#allItemInOffer option").each(function(i,el) {  
				 data1[$(el).data("value")] = $(el).val();
				});
			console.log(data1, $("#allItemInOffer option").val());
			var itemRowID = $('#allItemInOffer [value="' + selectedItemName + '"]').data('value');
			var ItemNameforCheck = $('#allItemInOffer [value="' + selectedItemName + '"]');
			//alert(itemRowID);
			if(ItemNameforCheck.length <= 0)
			{
				alert('Please Choose Valid Item form the list');
				$("#itemTypeDL").css("border-color","red");
				setTimeout(function(){
				   $("#itemTypeDL").css("border-color","#EBEBEB");    						
				   $("#itemTypeDL").val('');	
				   $("#itemTypeDL").focus();							
				}, 1500);
			}
			else if(itemRowID == 0)
			{
				alert('No pending Item to Supply');
				$("#itemTypeDL").css("border-color","red");
				setTimeout(function(){
				   $("#itemTypeDL").css("border-color","#EBEBEB");    						
				   $("#itemTypeDL").val('');	
				   $("#itemTypeDL").prop('disabled', true);
				  					
				}, 1000);
				setTimeout(function(){
					 $('.ShowData').html('');
				   $(".myModal").modal('toggle');
				}, 1500);
			}
			
			else
			{
				$(".SelectResult").html('');
				$(".SelectResult").load("dist/php/supplyItemForm.php",{SupplyORID:SuppORId, SupplyOrderType:OrderTy,SjobTableRId:JRowId,IRowId:itemRowID,IName:selectedItemName });
			}	
		}
	return false;
	});
	
});
  
 </script>
 <?php
 
 }
 ?>