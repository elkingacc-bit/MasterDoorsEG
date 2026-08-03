<div class="table-responsive-lg text-center">
 <table class="table table-sm table-bordered table-striped tableAllAccountantCode" width="100%">
  <thead  class="bg-primary">
   <th>Account Name</th>
   <th>Account Code</th>
   <th>Edit</th>
  </thead>
  <tbody>
   <?php
   include_once("connection.php");
    $sqlAccountantCodeData="SELECT `accountant_code_Id`,`accountCode`,`accountName` FROM `accountantcode` WHERE `codeLen` = 12 ";  
    $queryAccountantCodeData=mysqli_query($link,$sqlAccountantCodeData);
    while($accData=mysqli_fetch_assoc($queryAccountantCodeData)){
     echo"<tr>
      <td class='col-sm-6'>$accData[accountName]</td>
      <td class='col-sm-4'>$accData[accountCode]</td>
      <td class='col-sm-1'>
       <button type='button' class='btn btn-link rename' data-toggle='modal' data-target='#accountantCodeModal' value='$accData[accountant_code_Id]'><i class='far fa-edit' aria-hidden='true' style='font-size:16px;color:#0275d8'></i></button>
      </td>
     </tr>";
    }

   ?>
  </tbody>
 </table>
 <!-- Modal -->
 <div class="modal" id="accountantCodeModal" tabindex="-1" aria-labelledby="accountantCodeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
   <div class="modal-content">
    <div class="modal-header">
     <h5 class="modal-title" id="accountantCodeModalLabel">Modal title</h5>
     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="modal-body dataEdit">
    </div>
    <div class="modal-footer">
     <button type="button" class="btn btn-secondary closeModel" data-dismiss="modal">Close</button>
     <button type="button" class="btn btn-primary saveEditCode">Save changes</button>
    </div>
   </div>
  </div>
 </div>
</div>


<script type="text/javascript">
 $(document).ready(function(){

  var table = $('.tableAllAccountantCode').DataTable({
   fixedHeader: false,
   scrollY:'75vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false, 
   order:[[`1`, "asc"]],
  });


  $(".saveEditCode").click(function(){
   var codId =$("#saveEdite").val();
   var newName=$("#accountantName").val();
   $.ajax({
    url:"dist/php/saveCodeEditing.php",
    type:"POST",
    data:{rowNum:codId,nName:newName},
    success: function(saveEdit){
     $('#accountantCodeModal').modal('hide');
     $(".prviewData").html("");
    $(".m-0").html("All Accountant Code");
   $(".data_display").load("dist/html/allCode.html");
    

    }  
   }); 
   return false;
  });
  $(".dellCode").click(function(){
   var rowId =$(this).val();
   var msg1 = confirm("Want to delete From App?");
   if (msg1){
    var msg2 = confirm("Can't Find This Account Agine");
    if (msg2) {
     $.ajax({
      url:"dist/php/deleteCode.php",
      type:"POST",
      data:{codeNum:rowId},
      success: function(doneDelete){
       if(doneDelete == 1){$(".prviewData").load("dist/html/allCode.html");}
       else{alert(doneDelete);}  
      }  
     });
    }
   }
   return false;
  });
  
  $(".rename").click(function(){
   var codId =$(this).val();
   $.ajax({
    url:"dist/php/dataToEdit.php",
    type:"POST",
    data:{rowNum:codId},
    success: function(doneEdit){
     $(".dataEdit").html(doneEdit);      
    }  
   });
  });
 }); 
</script>