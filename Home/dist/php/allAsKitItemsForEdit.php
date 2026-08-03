<div align="center" style="margin-top:-4%">
	<table class="table" style="width:40%">
    	
       		 <th>Choose Name</th>
			<td>
            	<input type="text" id="allAsKitNames" class="form-control" list="allAsKitDList" />
                <datalist id="allAsKitDList"></datalist>
            </td>
		
    </table>
</div>

<div class="KitCompntsData"></div>

<div class="toast align-items-center text-white bg-dark border-0 " role="alert"  aria-live="polite" aria-atomic="true" data-delay="4000">
  <div class="d-flex" >
    <div class="toast-body">
      No data has been changed
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close">x</button>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {

$("#allAsKitDList").load("dist/php/getAllAsKitDList.php");

	$("#allAsKitNames").change(function(){
		
		var AssKitRowId = $(this).val();
		
		if(AssKitRowId != "")
		{
		var dataAsKit = {};
			$("#allAsKitDList option").each(function(i,el) {  
  			 dataAsKit[$(el).data("value")] = $(el).val();
			});
		console.log(dataAsKit, $("#allAsKitDList option").val());
	
	var AsKitIDVal = $('#allAsKitDList [value="' + AssKitRowId + '"]').data('value');
		
		
		
			$.ajax({
				
			url:"dist/php/ShowAllKitCompnts.php",
			type:"POST",
			data:{AsKitRID:AsKitIDVal, AsKitNamePass:AssKitRowId},
			beforeSend: function(){
			$(".KitCompntsData").html("");
			$(".KitCompntsData").html("<center><img src='dist/img/loadingColor.gif' alt='loading'></center>");
			},
			success: function(allKitCompontsData){
				
				setTimeout(function(){	
				$(".KitCompntsData").html("");
				$(".KitCompntsData").html(allKitCompontsData);
				}, 1000);
			}
				
				});
		
		}
		return false;
		});
    });

</script>
