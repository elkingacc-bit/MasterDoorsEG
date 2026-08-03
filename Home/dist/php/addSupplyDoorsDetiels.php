<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

 $ItemRowId = $_POST['itmRowId'];
 $SuppOrderRowId = $_POST['SuppORID'];
 $SuppOrderType = $_POST['orderTy'];
 $SuppjobRID = $_POST['suppJobRID'];
 $LoopQTY = $_POST['loopQTY'];


$sqlGetItem = "SELECT  `itemtype`, `itemname`, `itemhight`, `itemwidth`, `itemdepth`,`itemm2` 
FROM `itemoffer` WHERE  `id` = $ItemRowId ";
$queryGetItem = mysqli_query($link,$sqlGetItem)or die("ERROR :01-ANJ_GCN_S");
$resGetItem= mysqli_fetch_assoc($queryGetItem);	

$sqlGetOfferNum="SELECT `localref`,`jobtype`, `projectName` FROM `job` WHERE `jobId` = $SuppjobRID";
			$queryGetOfferNum=mysqli_query($link,$sqlGetOfferNum)or die("ERROR :04-AU_AU_S");
			$resGetOfferNum= mysqli_fetch_assoc($queryGetOfferNum);	

?>

<!doctype html>
<html>

 <p class="title">
		Add Supply Details ->:&nbsp;
        <b><?php echo $resGetOfferNum['projectName']; ?></b>  Name <span style="color:blue;">
        <b><?php echo $resGetItem['itemname']; ?></b></span> | Type <span style="color:blue;">
        <b><?php echo $resGetItem['itemtype']; ?></b></span> | Width <span style="color:blue;">
        <b><?php echo $resGetItem['itemwidth']; ?></b></span> | Hight <span style="color:blue;">
        <b><?php echo $resGetItem['itemhight']; ?></b></span> | M<sup>2</sup> <span style="color:blue;">
        <b><?php echo $resGetItem['itemm2']; ?></b></span>
         
        </p>	
<form id="itemsDitealsform" method="post">
	
    <table class="table table-sm table-striped table-bordered" style="width:80%">
	<thead>
    	<th>No.</th>
        <th>RAL</th>
        <th>Handling</th>
        <th>Door No</th>
        <th>Overlap</th>
    </thead>
<?php

for($row = 1; $row <= $LoopQTY; $row++)
{
	 
echo "
	<tr>
   		
        <td>$row</td>
        <td>
        	<input type='text' class='form-control checked RAL' name='RAL[]' />
        </td>
        <td>
            <input type='text' class='form-control checked Handle' name='Handle[]' />
        </td>
        <td>
            <input type='text' class='form-control checked DorNo' name='DorNo[]' />
        </td>
		<td>
            <input type='text' class='form-control checked OverLap' name='OverLap[]' />
        </td>
        
    </tr>    
";
 
}

?>
	
    <td colspan="5" align="center">
    	<button class="btn btn-success btn-sm" type="submit" id="saveAddDetialsBTN">Save</button>
    </td>
    
    </table>
    
    <input type="text" value="<?php echo $SuppjobRID?>" style="display:none" name="DetJobRID2" 
    id="DetJobRID2"/>
<input type="text" value="<?php echo $ItemRowId?>" style="display:none" name="DetItemRID2" 
id="DetItemRID2"/>
 <input type="text" value="<?php echo $SuppOrderType?>" style="display:none" name="DetSupplyOrderType2" 
 id="DetSupplyOrderType2"/>
 <input type="text" value="<?php echo $SuppOrderRowId?>" style="display:none" name="orderSupplyRIDDet2"
  id="orderSupplyRIDDet2"/>
  <input type="text" value="<?php echo $LoopQTY?>" style="display:none" name="loopQty"
  id="loopQty"/>

</form>

<script type="text/javascript">
	
	$(document).ready(function() {
        
		$("#itemsDitealsform").submit(function(){
			
			var RalVal = $(".RAL").val();
			var HandleVal = $(".Handle").val();
			var DorNoVal = $(".DorNo").val();
			var SuppORowID2 = $("#orderSupplyRIDDet2").val();
			var jobType2 = $("#DetSupplyOrderType2").val();
			var jobRowId2 = $("#DetJobRID2").val();
		var anyFieldIsEmpty = $(".checked").filter(function() {
        return $.trim(this.value).length === 0;
    	}).length > 0;	
		if(anyFieldIsEmpty )
		{ 
			alert('missing field');
			$('.RAL').css("border-color","red");
			$('.Handle').css("border-color","red");
			$('.DorNo').css("border-color","red");
			setTimeout(function(){
           		$('.RAL').css("border-color","#EBEBEB");
				$('.Handle').css("border-color","#EBEBEB");	
				$('.DorNo').css("border-color","#EBEBEB");			
				}, 1500);
		} 
		
		else
		{
			$.ajax({
			url:"dist/php/saveAddSuppItemDetails.php",
			type:"POST",
			data: new FormData(this),
			contentType: false,
        	cache: false,
   			processData:false,
			
			beforeSend: function(){
			$("#saveAddDetialsBTN").prop('disabled', true);	
				
			},
			success : function(doneAddedDetails){
				
				if(doneAddedDetails == 1)
				{
					alert("Date Saved");
					$("#saveAddDetialsBTN").prop('disabled', false);	
					$(".body").html('');
					$(".body").load('dist/php/addSupplyItemNew.php',{ModelSuppORID:SuppORowID2, MoadelOrderType:jobType2,JRID:jobRowId2});
					
					
				}
				else
				{
					alert(doneAddedDetails);
					$("#saveAddDetialsBTN").prop('disabled', false);
				}
				
			}
			
			});
		}
			
			return false; 
			});
		
    });

</script>

</html>