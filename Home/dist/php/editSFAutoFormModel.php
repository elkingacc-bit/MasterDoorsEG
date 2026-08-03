<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
 
 //echo "test";
 $JobRowId = $_POST['ModelEditSFJobRID'];
 $jobTypeSF1 = $_POST['ModelEditSFJobType'];
 
	 $sqlGetProjectN = "SELECT `projectName` FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetProjectN = mysqli_query($link,$sqlGetProjectN)or die("ERROR :01-ANJ_GCN_S");
	$resGetProjectN = mysqli_fetch_assoc($queryGetProjectN);
	
	$sqlGetm2Price = "SELECT `priceval` FROM `pricing` WHERE `pricetype` = 'Doors'";
	$queryGetm2Price = mysqli_query($link,$sqlGetm2Price)or die("ERROR :01-ANJ_GCN_S");
	$resGetm2Price = mysqli_fetch_assoc($queryGetm2Price);
	
	
$projectName = $resGetProjectN['projectName'];
if($jobTypeSF1 == 'Doors')
{
	$Price = $resGetm2Price['priceval'];
	$disply = "";
	$colspan = "4";
}
else if($jobTypeSF1 == 'Automatic')
{
	$Price = 0;
	$disply = "none";
	$colspan = "3";
} 
?>
 <div class="modal-header">
        <h5 class="modal-title">Add overhead margin for Project <span style="color:blue;">&nbsp;<b><?php echo $projectName; ?>
        </b></span> </h5>       
      </div>
       <div class="modal-body ">
       	<table class="table table-sm">
        	<th style="display:<?php echo $disply; ?>">M<sup>2</sup>&nbsp;Price</th>
            <th>Installation</th>
            <th>Sipping</th>
            <th>Overhead</th>
        	<tr>
            	<td style="display:<?php echo $disply; ?>">
                	<input type="number" class="form-control M2Price" min="1" step="0.01" autocomplete="off" 
                    value="<?php echo $Price;?>"/>
                </td>
                <td>
                	<input type="number" class="form-control Install" min="0" step="0.01" autocomplete="off" />
                </td>
                <td>
                	<input type="number" class="form-control Sipp" min="0" step="0.01" value="0" autocomplete="off">
                </td>
                <td >
                <div class="input-group">
                  <input type="number" class="form-control hwOverhead" id="hwOverhead" aria-label="%" 
                  list="hwOverheadList" min="1" autocomplete="off">
                  <datalist id="hwOverheadList">
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
            </tr>
            <tr>
            	<td colspan="<?php echo $colspan;?>" align="center">
                	<button class="btn btn-sm btn-success" id="editDoorsPriceBTN">Save</button>
                </td>
            </tr>
        </table>
       </div>
 <input type="text" value="<?php echo $JobRowId;?>" style="display:none" id="rowIdJobSFEdit"/>
 <input type="text" value="<?php echo $jobTypeSF1;?>" style="display:none" id="typeJobSFEdit"/>
  </div>
 <script type="text/javascript">
 $(document).ready(function() {
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	  
						
	$("#editDoorsPriceBTN").click(function(){
		
		var jobRowIdEditSFForm = $("#rowIdJobSFEdit").val();
		var jobTypeEditSFForm = $("#typeJobSFEdit").val();
		var editInstall = $(".Install").val();
		var editShipp = $(".Sipp").val();
		var editPrice = $(".M2Price").val();
		var editMargin = $(".hwOverhead").val();
		
		
	if(editPrice == "" && jobTypeEditSFForm == 'Doors')
      {
       alert('missing field');
			$('.M2Price').css("border-color","red");
			setTimeout(function(){
           		$('.M2Price').css("border-color","#EBEBEB");
				$('.M2Price').focus();				
				}, 1500);
      }
	  else if(editPrice == 0 && jobTypeEditSFForm == 'Doors')
      {
       alert('missing field');
			$('.M2Price').css("border-color","red");
			setTimeout(function(){
           		$('.M2Price').css("border-color","#EBEBEB");
				$('.M2Price').focus();				
				}, 1500);
      }
	else if(editInstall == "" )
      {
       alert('missing field');
			$('.Install').css("border-color","red");
			setTimeout(function(){
           		$('.Install').css("border-color","#EBEBEB");
				$('.Install').focus();				
				}, 1500);
      }
	else if(editShipp == "" )
      {
       alert('missing field');
			$('.Sipp').css("border-color","red");
			setTimeout(function(){
           		$('.Sipp').css("border-color","#EBEBEB");
				$('.Sipp').focus();				
				}, 1500);
      } 
	else if(editMargin == "" )
      {
       alert('missing field');
			$('.hwOverhead').css("border-color","red");
			setTimeout(function(){
           		$('.hwOverhead').css("border-color","#EBEBEB");
				$('.hwOverhead').focus();				
				}, 1500);
      }
	else
	  {
		  $.ajax({
			  url:"dist/php/saveDoorPriceingOverall.php",
			  type:"POST",
			  data:{JRIDESF:jobRowIdEditSFForm,InstallSF:editInstall,ShippSF:editShipp,MarginSF:editMargin,jobTypeSF:jobTypeEditSFForm,m2PriceSF:editPrice},
			  beforeSend: function()
			  {
					$("#editDoorsPriceBTN").prop("disabled",true);	
		      },
					
			  success: function(doneAddNewMargin)
			  {
				  if(doneAddNewMargin == 1)
				  {
					  alert("Data Saved");
					  $('.ShowHWDataExpt').html('');
					  $(".myModal").modal('toggle');
						setTimeout(function(){				
							$("#editDoorsPriceBTN").prop('disabled', false);
							$("#3_2").click();
      					}, 1500);
						
				  }
				  else if(doneAddNewMargin == 3)
				  {
					  alert("Missing Door Price it is still 0 Amount");
					  
						setTimeout(function(){		
						$('.ShowHWDataExpt').html('');
					  	$(".myModal").modal('toggle');		
							$("#editDoorsPriceBTN").prop('disabled', false);
      					}, 500);
						
				  }
				  else
				  {
					  alert(doneAddNewMargin);
					  $("#editDoorsPriceBTN").prop('disabled', false);
				  }
					
			  }
		});
		  
		  
		  
		  
	  }
	   
		
		return false;
		});
});
 
 </script>