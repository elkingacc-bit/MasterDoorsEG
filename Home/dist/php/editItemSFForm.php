<!doctype html>
<html>

<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 
 $itemRowId = $_POST['itemRId'];
 
 $sqlGetItems="SELECT `descriptionname`, `description` FROM `stockitems` WHERE `itemsid` = $itemRowId";
$queryGetItems=mysqli_query($link,$sqlGetItems)or die("ERROR :01-AU_AU_S");
$resGetItems = mysqli_fetch_assoc($queryGetItems);

$sqlGetItemCost="SELECT `cost`, `sales` FROM `lookupstock` WHERE `descriptioncode` = $resGetItems[description]";
$queryGetItemCost=mysqli_query($link,$sqlGetItemCost)or die("ERROR :02-AU_AU_S");
$resGetItemCost = mysqli_fetch_assoc($queryGetItemCost);
	
?>
 <div class="modal-header">
        <h5 class="modal-title">Add Sales Factor & Overcost for Item :&nbsp;<span style="color:blue;"><?php echo $resGetItems['descriptionname'];?></span></h5>
        
      </div>
       <div class="modal-body ">
<table class="table table-sm " style="width:100%" align="center">
   
      <thead class="bg-warning">
       	<th>Cost</th>
        <th>Sales</th>
        <th>Sales Factor</th>
        <th>Overcost</th>
      </thead>
      <tbody>
      <td><?php echo number_format($resGetItemCost['cost']); ?></td>
      <td><?php echo number_format($resGetItemCost['sales']); ?></td>
		 
		<td >
        	<div class="input-group">
              <input type="number" class="form-control itemSalesFact" id="itemSalesFact" aria-label="%" 
              list="presntageVal" min="1">
              <datalist id="presntageVal">
              <?php 
			  	for($p = 1; $p <= 400; $p++)
				{
					echo "<option value='$p'>";
				}
			   
			  ?>
              </datalist>
              <div class="input-group-append">
                <span class="input-group-text">%</span>
              </div>
            </div>
        </td>
        <td >
         <input type="number" class="form-control itemOverCost" id="itemOverCost" min="0" value="0"/>
        </td>
 <tr>
 	<td colspan="4" align="center">	
    	<button class="btn btn-sm btn-success" id="saveAddSalseFact">Save</button>
    </td> 
  </tr>
  </tbody>
     
 </table>
 </div>
 <input type="text" value="<?php echo $itemRowId?>" style="display:none" id="ItemRowIdUpdate"/>
 <script type="text/javascript">
 $(document).ready(function() {

	$("#saveAddSalseFact").click(function(){
		
		var ItemRID = $("#ItemRowIdUpdate").val();
		var SalesFactPeg = $("#itemSalesFact").val();
		var itemOverCost = $("#itemOverCost").val();
		//
		if(SalesFactPeg == "" || SalesFactPeg == 0)
		{
			alert('missing field');
			$('#itemSalesFact').css("border-color","red");
			setTimeout(function(){
           		$('#itemSalesFact').css("border-color","#EBEBEB");
				$("#itemSalesFact").focus();				
				}, 1500);
		}
		else if(itemOverCost == "")
		{
			alert('missing field');
			$('#itemOverCost').css("border-color","red");
			setTimeout(function(){
           		$('#itemOverCost').css("border-color","#EBEBEB");
				$("#itemOverCost").focus();				
				}, 1500);
		}
		else
		{
			
			$.ajax({
				
					url:"dist/php/saveAddSFactor.php",
					type:"POST",
					data:{IRID:ItemRID, SalesFactVal:SalesFactPeg, overPriCost:itemOverCost},
					beforeSend: function(){
						$("#saveAddSalseFact").prop('disabled', true);	
					},
					success: function(doneAddSalesFact){
						
						if(doneAddSalesFact == 1)
						{
							alert("Date Saved");
							 $('.ShowData').html('');
							 $(".myModal").modal('toggle');
							 $(".itemNotHaveSF").html("");
							 $(".itemNotHaveSF").load("dist/php/showAllItemsForAddSF.php");
							 
							 
						}
						else
						{
							alert(doneAddSalesFact);
							$("#saveAddSalseFact").prop('disabled', false);	
						}
						
					}
				});
		}
		
		return false;
		});
});
 

 </script>
 <?php
 }
 ?>
 
 </html>