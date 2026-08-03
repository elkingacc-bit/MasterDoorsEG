<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
  
 $attendRowId = $_POST['attendRowId'];
 $dateOldSelect = $_POST['OldDateVal'];//date("Y-m-d");
 
 	$sqlCheckAttend = "SELECT `id`, `staffRId`, `poRowId`, `attendDate`,`penalty`, `Reward` 
	FROM `outsidemanpower` WHERE `attendDate` = '$dateOldSelect' And `id` = $attendRowId";
	$queryCheckAttend=mysqli_query($link,$sqlCheckAttend)or die("ERROR :01-AU_AU_S");
	$resCheckAttend= mysqli_fetch_assoc($queryCheckAttend);
	
	$sqlGetPOData="SELECT `custCode`, `PoNum`, `jobidref` FROM `customerpo` 
	WHERE `poId` = $resCheckAttend[poRowId]";
	$queryGetPOData=mysqli_query($link,$sqlGetPOData)or die("ERROR :01-AU_AU_S");
	$resGetPOData= mysqli_fetch_assoc($queryGetPOData);
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetPOData[custCode]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :01-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlGetProject="SELECT `projectName` FROM `job` WHERE `jobId` = $resGetPOData[jobidref]";
	$queryGetProject=mysqli_query($link,$sqlGetProject)or die("ERROR :01-AU_AU_S");
	$resGetProject= mysqli_fetch_assoc($queryGetProject);

	$sqlGetStaffName="SELECT `staffname`, `staffposition` FROM `allstaff` 
	WHERE `id`= $resCheckAttend[staffRId]";
	$queryGetStaffName=mysqli_query($link,$sqlGetStaffName)or die("ERROR :01-AU_AU_S");
	$resGetStaffName= mysqli_fetch_assoc($queryGetStaffName);	
?>
 <div class="modal-header">
        <h5 class="modal-title">Edit Attendance In Po: <span style="color:blue;"><b>
		<?php echo $resGetPOData['PoNum']; ?>
        </b></span> Customer Name:&nbsp;<span style="color:blue;"><b>
		<?php echo $resGetCustomer['customername']; ?></b></span></h5>
       <button class="btn btn-xs btn-link close delete" data-toggle='tooltip' data-placement='top' 
       title='remove attendance' id="RemoveAttend" value="<?php echo $attendRowId; ?>">
        <i class='far fa-trash-alt' aria-hidden='true' style='font-size:20px;color:#d9534f'>
        </i>
        </button>
        
      </div>
       <div class="modal-body editAttData">
<table class="table table-sm "  style="width:100%">
   
      <thead class="bg-info">
       	<th>Staff Name</th>
        <th>Position</th> 
        <th>Project</th>
        <th>Date</th>
        <th>Penalty</th>
        <th>Reward</th>
      </thead>
      <tbody>
		<td><?php echo $resGetStaffName['staffname'];?></td>
        <td><?php echo $resGetStaffName['staffposition'];?></td>
        <td>
            <input type="text" class="form-control" style="width:" id="allValidPO" list="AllCustPo"
            value="<?php echo $resGetPOData['PoNum']." ". $resGetProject['projectName']; ?>"/>
            <datalist id="AllCustPo" ></datalist>
        </td>
         <td>
        	<input type="date" class="form-control" id="AttendDate" 
            value="<?php echo $resCheckAttend['attendDate'];?>"/>
        </td>
        <td>
            <select id="penaltyDay" class="form-control">
            	<option value="<?php echo $resCheckAttend['penalty'];?>">
				<?php echo $resCheckAttend['penalty'];?></option>
                <option value=".25">1/4</option>
                <option value=".5">1/2</option>
                <option value="1">1</option>
                <option value="1.5">1 & 1/5</option>
                <option value="2">2</option>
                <option value="0">0</option>
            </select>
        </td>
       
        <td>
           	 <select id="RewardDay" class="form-control">
            	<option value="<?php echo $resCheckAttend['Reward'];?>">
				<?php echo $resCheckAttend['Reward'];?></option>
                <option value=".25">1/4</option>
                <option value=".5">1/2</option>
                <option value="1">1</option>
                <option value="1.5">1 & 1/5</option>
                <option value="2">2</option>
                <option value="0">0</option>
            </select>	
        </td>
       <tr>
       	<td colspan="5" align="center">
        	<button type="button" class="btn btn-success" id="saveEditAttendBTN">Save</button>
        </td>
       </tr> 
  </tbody>
 </table>
 </div>
 <input type="text" value="<?php echo $resGetCustomer['customername'];?>" style="display:none" 
 id="CustomerNameInput"/>
  <input type="text" value="<?php echo $attendRowId;?>" style="display:none" id="AttendRowIdInput"/>
   <input type="date" value="<?php echo $dateOldSelect;?>" style="display:none" id="AttendOldDateInput"/>
  <input type="text" value="<?php echo $resGetPOData['PoNum'];?>" style="display:none" id="PoInpout"/>
  <input type="text" value="<?php echo $resGetStaffName['staffname'];?>" style="display:none" 
  id="StaffNameInput"/>
 <script type="text/javascript">
 $(document).ready(function() {
  $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});	  

$('#AllCustPo').load("dist/php/allCustPOSite.php");

$("#RemoveAttend").click(function(){
	
	var remRowID = $(this).val();
	
	var confRemove = confirm("Confirm remove Attendance for this Staff?");
	var attRowId = $("#AttendRowIdInput").val();
	var custNameRemove = $("#CustomerNameInput").val();
	var poNoRemove = $("#PoInpout").val();
	var staffNameRemove = $("#StaffNameInput").val();
	
	if(confRemove === true)
	{
		$.ajax({
				
				url:"dist/php/removeStaffAttendToday.php",
				type:"POST",
				data:{attendRID:attRowId,CustName:custNameRemove,PoNumber:poNoRemove,StaffN:staffNameRemove},
				beforeSend: function(){
				$("#RemoveAttend").prop('disabled', true);	
				},
				success: function(doneRMAttend){
					
					if(doneRMAttend == 1)
					{
						alert("Data Saved");
						
						$("#RemoveAttend").prop('disabled', true);
						$('.ShowData').html('');	
						setTimeout(function(){
							
						$('.editAttend').html('');
						$('.editAttend').load("dist/php/showAllAttendtoday.php");
						}, 500);
						$(".myModal").modal('toggle');
					}
					else
					{
						alert(doneRMHW);
						$("#RemoveAttend").prop('disabled', true);	
					}
					
					
				}
			
			});
	}
	
	return false; 
	});
	
	$("#saveEditAttendBTN").click(function(){
		
	var CustPOAttend = $("#allValidPO").val();
	var custdata = {};
$("#AllCustPo option").each(function(i,el) {  
   custdata[$(el).data("value")] = $(el).val();
});
console.log(custdata, $("#AllCustPo option").val());

	var PoChosenValideate = $('#AllCustPo [value="' + CustPOAttend + '"]');					
	var PoRID = $('#AllCustPo [value="' + CustPOAttend + '"]').data('value');
		
	var attRowId2 = $("#AttendRowIdInput").val();
	var custNameRemove2 = $("#CustomerNameInput").val();
	var poNoRemove2 = $("#PoInpout").val();
	var staffNameRemove2 = $("#StaffNameInput").val();
	var staffReword = $("#RewardDay").val();
	var staffPalenty = $("#penaltyDay").val();
	var staffAttend = $("#AttendDate").val();
	var staffOldDate = $("#AttendOldDateInput").val();
	
	/*if(CustPOAttend == poNoRemove2 &&  staffReword == 0 && staffPalenty == 0)
	{
		alert("No Data Changed!");
						
		$('.ShowData').html('');	
		setTimeout(function(){
			$(".myModal").modal('toggle');
		}, 500);
	}
	else
	{*/
		
	if(PoChosenValideate.length <= 0)
	   {
			alert('Please Choose Valid Customer name / PO Number form the list');
			$("#allValidPO").css("border-color","red");
		  setTimeout(function(){
		   $("#allValidPO").css("border-color","#EBEBEB");    						
		   $("#allValidPO").val('');	
		   $("#allValidPO").focus();							
		  }, 1500);
		}	
	else if(staffAttend == "")
		{
			alert('Please Choose Valid date');
			$("#AttendDate").css("border-color","red");
		  setTimeout(function(){
		   $("#AttendDate").css("border-color","#EBEBEB");    							
		   $("#AttendDate").focus();							
		  }, 1500);	
		}
		else
		{
			$.ajax({
				
				url:"dist/php/saveEditStaffAttendToday.php",
				type:"POST",
				data:{attendRIDEdit:attRowId2,CustNameOld:custNameRemove2,PoNumberOld:poNoRemove2,StaffNOld:staffNameRemove2, newPoNum: CustPOAttend, NewCustPoRowId:PoRID, pelantyVal:staffPalenty, rewordVal:staffReword,attendDateVal:staffAttend,OldSelctDate:staffOldDate},
				beforeSend: function(){
				$("#saveEditAttendBTN").prop('disabled', true);	
				},
				success: function(doneEditAttend){
					
					if(doneEditAttend == 1)
					{
						alert("Data Saved");
						
						$("#saveEditAttendBTN").prop('disabled', true);
						$('.ShowData').html('');	
						setTimeout(function(){
							
						$('.editAttend').html('');
						$('.editAttend').load("dist/php/showAllAttendtoday.php",{DateVal:staffOldDate});
						}, 500);
						$(".myModal").modal('toggle');
					}
					else
					{
						alert(doneEditAttend);
						$("#saveEditAttendBTN").prop('disabled', true);	
					}
					
					
				}
			
			});

		}
	//}
		return false;
		});
	
});
 
 </script>
 <?php
 
 }
 ?>
 