<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 
 $JobRowId = $_POST['ModelJobRIDNote'];
 
 $sqlGetAllNewJob="SELECT `customer` FROM `job` WHERE `jobId` = $JobRowId";
	$queryGetAllNewJob=mysqli_query($link,$sqlGetAllNewJob)or die("ERROR :01-AU_AU_S");
	$resGetAllNewJob= mysqli_fetch_assoc($queryGetAllNewJob);
	
	
	$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` = $resGetAllNewJob[customer]";
	$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :02-AU_AU_S");
	$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	$sqlGetAttCurrent="SELECT `offerNotes` FROM `offerpolicy` WHERE `jobRowId` = $JobRowId";
	$queryGetAttCurrent=mysqli_query($link,$sqlGetAttCurrent)or die("ERROR :02-AU_AU_S");
	$resGetAttCurrent= mysqli_fetch_assoc($queryGetAttCurrent);
	if($resGetAttCurrent['offerNotes'] != "")
	{	
		
		$Note = strip_tags($resGetAttCurrent['offerNotes']);
	}
	else
	{
		
		$Note = "- All fire doors will be manufacture according to standard with frame thickness 1.5 mm and leaf thicness1.25 mm.\n- All prices below are NET prices.\n- Prices include Supplying, installing & commissioning .\n- We reserve the right to re-quote in the event of changes in quality & specification.\n- This prices include Hardware AHRAM.\n- ALL prices not incl grout.";
	}
	
?>
 <div class="modal-header">
        <h5 class="modal-title">Add offer Note for customer:&nbsp;<span style="color:blue;"><?php echo $resGetCustomer['customername'];?></span</h5>
        
      </div>
       <div class="modal-body ">
<table class="table table-sm " style="width:100%" align="center">
   
      <thead class="bg-warning">
        <th>Offer Note</th>
      </thead>
      <tbody>
        <td class='col-sm-5'>
        <textarea class="form-control" id="offerNotes" style="height:30vh; font-size:14px"><?php echo $Note;?></textarea> 
        </td>
 <tr>
 	<td  align="center">	
    	<button class="btn btn-sm btn-success" id="saveNoteInOfferBTN">Save</button>
    </td> 
  </tr>
  </tbody>
     
 </table>
 </div>
 <input type="text" value="<?php echo $JobRowId?>" style="display:none" id="rowIdJobLoadNote"/>
 <script type="text/javascript">
 $(document).ready(function() {

	$("#saveNoteInOfferBTN").click(function(){
		
		var noteJobRID = $("#rowIdJobLoadNote").val();
		var noteDateNote = $("#offerNotes").val();
		//
		if(noteDateNote == "" || noteDateNote == null)
		{
			alert('missing field');
			$('#offerNotes').css("border-color","red");
			setTimeout(function(){
           		$('#offerNotes').css("border-color","#EBEBEB");
				$("#offerNotes").focus();				
				}, 1500);
		}
		
		else
		{
			
			$.ajax({
				
					url:"dist/php/saveOfferNote.php",
					type:"POST",
					data:{offerNote:noteDateNote, noteJobRowId:noteJobRID},
					beforeSend: function(){
						$("#saveNoteInOfferBTN").prop('disabled', true);	
					},
					success: function(doneAddNote){
						
						if(doneAddNote == 1)
						{
							alert("Date Saved");
							 $('.ShowHWDataExpt').html('');
							 $(".myModal").modal('toggle');
							 $("#Note").removeClass("btn-dark");
							 $("#Note").addClass("btn-info");
							 $(".policyTable").show();
							 $(".noteTH").show();
							 $(".noteData").show();
							 $(".noteData").html(noteDateNote);
							 
						}
						else
						{
							alert(doneAddNote);
							$("#saveNoteInOfferBTN").prop('disabled', false);	
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