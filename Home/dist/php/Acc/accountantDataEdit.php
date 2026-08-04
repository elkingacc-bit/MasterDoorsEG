<div class="container-fluid">
 <hr>
 <div class="card text-white bg-gradient-info w-75">
  <div class="card-body">
   <?php
    date_default_timezone_set("Africa/Cairo");
    include_once("../connection.php");
    $resData=$_POST['editType'];
    #1 Partnership   #4 Investment #5 Custdy #6 Advance #7 Manufactured Offer #8 Purchases Invoice #9 Sales Invoice
    if($resData == 1){}
    #2 Cash
    else if($resData == 2){
     echo"
      <input type='text' class='editModuleName invisible' value='Cash'>
      <h4 class='text-center font-weight-bolder text-body'>Cash</h4>    
      <p class='card-text'>
       <div class='col-auto'>
        <label class='sr-only' for='dateSearch'>Choose Date</label>
        <div class='input-group mb-2'>
         <div class='input-group-prepend'>
          <div class='input-group-text'>Action Date</div>
         </div>
         <input type='date' class='form-control' id='dateSearch'>
        </div>
       </div>
      </p>
      <button type='button' class='btn btn-light btn-lg btn-block getDataEditChoose' value='2'>Go To Edit</button>
     ";
    }

 else if($resData == 4){}    
 else if($resData == 5){}
 else if($resData == 6){}
 else if($resData == 7){}
 else if($resData == 8){}
 else if($resData == 9){}
?>
  
  



  </div>
 </div>



</div>


<script type="text/javascript">
 $(document).ready(function(){
  var titelName=$(".editModuleName").val();
  $('.m-0').html("Edit To " + titelName);
  
  



  $(".getDataEditChoose").click(function(){
   var dateEdit=$("#dateSearch").val();
   $.ajax({
    url:'dist/php/Acc/admin_cash.php',
    type:"POST",
    data:{myDate:dateEdit},
    success: function(editDataShow){
     $(".addDiv").html('');
     $(".addDiv").html(editDataShow);
    }
   });
  });


  
  return false;
 }); 
</script>