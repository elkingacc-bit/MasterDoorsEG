<?php
@session_start();
date_default_timezone_set("Africa/Cairo");
include_once("connection.php");

$UserCode = $_SESSION['code'];
$sqlGetSalesEvents = "SELECT `id`, `title`,`customerName`, `description`, `start`, `end`, `color`
FROM `events` WHERE `ref` = 0 ";

$queryGetSalesEvents = mysqli_query($link,$sqlGetSalesEvents);

?>

<!DOCTYPE html>
<html lang="en">

	
    <!-- Bootstrap Core CSS 
	
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css'>
-->

    <!-- Custom CSS -->
    
	 <style>
    
	#calendar {
		max-width: auto;
	}
	.nocheckbox {
    display: none;
}

.label-on {
    border-radius: 3px;
    background: red;
    color: #ffffff;
    padding: 10px;
    border: 1px solid red;
    display: table-cell;
}

.fc-h-event { /* allowed to be top-level */
    display: block;
    height: 70px !important;
    border: 2px solid #3788d8;
    border: 2px solid var(--fc-event-border-color, #3788d8);
    background-color: #3788d8;
    background-color: var(--fc-event-bg-color, #3788d8)
}

.label-off {
    border-radius: 3px;
    background: white;
    border: 1px solid red;
    padding: 10px;
    display: table-cell;
}
	
	  #calendar a.fc-event {
  color: #fff; /* bootstrap default styles make it black. undo */
  background-color: #0065A6;
}
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


    <!-- Page Content -->
    <table style="margin-top:-1%">
  <td>
  <button class="btn btn-dark btn-sm" id="showTable">Show In Table</button>
  </td>
  </td>
  </table>
    <div class="container" style="margin-top:-1%">

        <div class="row">
            <div class="col-lg-12 text-center">
			<div style="height:20px"></div>
                <div id="calendar" class="col-centered">
                </div>
            </div>
			
        </div>
        <!-- /.row -->
		<!-- Modal -->
<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
     <form class="form-horizontal" method="POST" id="addEventForm" >
			
			  <div class="modal-header">
			  <h4 class="modal-title" id="myModalLabel">Add Event</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  </div>
			  <div class="modal-body">
				
				  <div class="form-group-sm">
					<label for="title" class="col-sm-2 control-label">Title</label>
					<div class="col-sm-10">
					  <input type="text" name="title" class="form-control" id="title" placeholder="Title">
					</div>
				  </div>
                   <div class="form-group-sm">
					<label for="title" class="col-sm-2 control-label">Customer</label>
					<div class="col-sm-10">
					  <input type="text" name="customer" class="form-control" id="customer" 
                      placeholder="Customer name">
					</div>
				  </div>
				  <div class="form-group-sm">
					<label for="description" class="col-sm-2 control-label">Description</label>
					<div class="col-sm-10">
					  <input type="text" name="description" class="form-control" id="description" placeholder="Description">
					</div>
				  </div>
				  <div class="form-group-sm">
					<label for="color" class="col-sm-2 control-label">Color</label>
					<div class="col-sm-10">
					  <select name="color" class="form-control" id="color">
						  <option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
						  <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
						  <option style="color:#008000;" value="#008000">&#9724; Green</option>						  
						  <option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
						  <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
						  <option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
						  <option style="color:#000;" value="#000">&#9724; Black</option>
						  
						</select>
					</div>
				  </div>
				  <div class="container">
				  <div class="row">
				  <div class="form-group-sm">
					<label for="start" class="col-sm-12 control-label">Start date</label>
					<div class="col-sm-12">
					  <input type="datetime-local" name="start" class="form-control" id="start" readonly>
					</div>
				  </div>
				  <div class="form-group-sm">
					<label for="end" class="col-sm-12 control-label">End date</label>
					<div class="col-sm-12">
					  <input type="datetime-local" name="end" class="form-control" id="end" readonly>
					</div>
				  </div>
				</div>
				</div>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save</button>
			  </div>
			</form>
    </div>
  </div>
</div>
		
		<!-- Modal -->
<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
			<form class="form-horizontal" method="POST" id="editEventsForm">
			  <div class="modal-header">
			  <h4 class="modal-title" id="myModalLabel">Edit Event</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  </div>
			  <div class="modal-body">
				
				  <div class="form-group-sm">
					<label for="title" class="col-sm-2 control-label">Title</label>
					<div class="col-sm-10">
					  <input type="text" name="title" class="form-control" id="title2" placeholder="Title">
					</div>
				  </div>
                   <div class="form-group-sm">
					<label for="title" class="col-sm-2 control-label">Customer</label>
					<div class="col-sm-10">
					  <input type="text" name="customer" class="form-control" id="customer2" placeholder="customer">
					</div>
				  </div>
				  <div class="form-group-sm">
					<label for="description" class="col-sm-2 control-label">Description</label>
					<div class="col-sm-10">
					  <input type="text" name="description" class="form-control" id="description2" placeholder="Description">
					</div>
				  </div>
				  <div class="form-group-sm">
					<label for="color" class="col-sm-2 control-label">Color</label>
					<div class="col-sm-10">
					  <select name="color" class="form-control" id="color2">
						  <option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
						  <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
						  <option style="color:#008000;" value="#008000">&#9724; Green</option>						  
						  <option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
						  <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
						  <option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
						  <option style="color:#000;" value="#000">&#9724; Black</option>
						  
						</select>
					</div>
				  </div>
				    <div class="form-group-sm"> 
						<div class="col-sm-2">
						  <label onclick="toggleCheck('check1');" class="label-off" for="check1" id="check1_label">
						  Delete
						</label>
                         
						<input class="nocheckbox" type="checkbox" id="check1" name="delete">
						</div>
					</div>
				  <input type="hidden" name="id" class="form-control" id="id2">
				<script>
					function toggleCheck(check) {
						if ($('#'+check).is(':checked')) {
							$('#'+check+'_label').removeClass('label-on');
							$('#'+check+'_label').addClass('label-off');
						} else {
							$('#'+check+'_label').addClass('label-on');
							$('#'+check+'_label').removeClass('label-off');
						}
					}		  
					</script>
				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save</button>
			  </div>
			</form>
			</div>
		  </div>
		</div>

    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 
		<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
	<script src='FullCalendar/js/moment.min.js'></script>
    <script
  src="https://code.jquery.com/jquery-1.9.1.min.js"
  integrity="sha256-wS9gmOZBqsqWxgIVgA8Y9WcQOa7PgSIX+rPA0VL2rbQ="
  crossorigin="anonymous"></script>
	
	
	 <!-- Bootstrap Core JavaScript 
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js'></script>
	-->
	
	<script>
	$(document).ready(function() {
         var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear();
    
	 $(function() {
		
		$('#calendar').fullCalendar({
			header: {//agendaWeek,agendaDay,
				left: 'prev,next,today',
				center: 'title',
				right: 'month,listWeek'
			},
			timezone: 'Africa/Cairo',
			//views: {
			 // timeGridWeek: { buttonText: 'Week' }
				//},
			firstDay: 6,
			height: 470,
			businessHours: {
			  dow: [ 0,1, 2, 3, 4, 6 ],

			  start: '8:00',
			  end: '17:00',
			},
			nowIndicator: true,
			//now: '2020-12-11T11:15:00', //remove - only for demo
			scrollTime: '08:00:00',
			defaultDate: Date(d,m,y), // Use this line to use the current date: new Date(),
			editable: true,
			navLinks: true,
			eventLimit: true, // allow "more" link when there are too many events
			selectable: true,
			selectHelper: true,
			select: function(start, end) {
				
				$('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
				$('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
				$('#ModalAdd').modal('show');
			},
			eventAfterRender: function(eventObj, $el) {
				var request = new XMLHttpRequest();
				request.open('GET', '', true);
				request.onload = function () {
					$el.popover({
						title: eventObj.customer,
						content: eventObj.description,
						trigger: 'hover',
						placement: 'top',
						container: 'body'
					});
				}
			request.send();
			},
	
			eventRender: function(event, element) {
				element.bind('click', function() {
					$('#ModalEdit #id2').val(event.id);
					$('#ModalEdit #title2').val(event.title);
					$('#ModalEdit #customer2').val(event.customer);
					$('#ModalEdit #description2').val(event.description);
					$('#ModalEdit #color2').val(event.color);
					$('#ModalEdit').modal('show');
				});
			},
			eventDrop: function(event, delta, revertFunc) { // si changement de position

				edit(event);

			},
			eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

				edit(event);

			},
			events: [
			
			<?php 
			
				while($event = mysqli_fetch_assoc($queryGetSalesEvents))
				{
				$start = explode(" ", $event['start']);
				$end = explode(" ", $event['end']);
				if($start[1] == '00:00:00'){
					$start = $start[0]." 00:00:00";
				}else{
					$start = $event['start'];
				}
				if($end[1] == '00:00:00'){
					$end = $end[0]." 00:00:00";
				}else{
					$end = $event['end'];
				}
			?>
				{
					id: '<?php echo $event['id']; ?>',
					title: '<?php echo $event['title']; ?>',
					customer: '<?php echo $event['customerName']; ?>',
					description: '<?php echo $event['description']; ?>',
					start: '<?php echo $start; ?>',
					end: '<?php echo $end; ?>',
					color: '<?php echo $event['color']; ?>'
					
				},
			<?php } ?>
			]
		});
		
		function edit(event){
			start = event.start.format('YYYY-MM-DD HH:mm:ss');
			if(event.end){
				end = event.end.format('YYYY-MM-DD HH:mm:ss');
			}else{
				end = start;
			}
			
			id =  event.id;
			customer = event.customer;
			Event = [];
			Event[0] = id;
			Event[1] = start;
			Event[2] = end;
			Event[3] = customer;
			
			$.ajax({
			 url: 'dist/php/edit-date.php',
			 type: "POST",
			 data: {Event:Event},
			 success: function(rep) {
					if(rep == 1){
						alert('Saved');
					}else{
						alert('Could not be saved. try again.'); 
					}
				}
			});
		}
		
	
		
	});
	
	$("#addEventForm").submit(function(){
		
		
		$.ajax({
				
				url:"dist/php/add-event.php",
				type:"POST",
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData:false,
				success: function(doneAddEvent)
				{
					if(doneAddEvent == 1)
					{
					$('#ModalAdd').modal('hide');
					
					setTimeout(function(){				
						 $('.data_display').html('');
	 					$('.data_display').load('dist/php/showEventsSales.php');
						
						
      					}, 1500);		
					}
					else
					{
						alert(doneAddEvent);
					}
				}
			
			
			});
			return false;
		});	
			
		$("#editEventsForm").submit(function(){
		
		$.ajax({
				
				url:"dist/php/editEventTitle.php",
				type:"POST",
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData:false,
				success: function(doneEditEvent)
				{
					if(doneEditEvent == 1)
					{
					
					$('#ModalEdit').modal('hide');
					
					setTimeout(function(){				
						 $('.data_display').html('');
	 					$('.data_display').load('dist/php/showEventsSales.php');
						
      					}, 1500);		
					}
					else
					{
						alert(doneEditEvent);
					}
				}
			
			
			});	
		
		return false;
		});
		
	$("#showTable").click(function(){
		
				 
				$('.data_display').html('');
				setTimeout(function(){		
				$('.data_display').load('dist/php/showEventsSalesInTable.php');
				}, 500);			
			return false;
		
		});			
					 		
		
});
</script>


</html>
