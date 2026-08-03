<div class="table-responsive-lg text-center">
 <center>
  <table class="table table-sm table-bordered table-striped tableThierdCode text-center" width="70%">
   <thead  class="bg-secondary">
    <th>Account Name</th>
    <th>Account Code</th>
   </thead>
   <tbody>
    <?php
     include_once("connection.php");
     $sqlThierdCode="SELECT `accountant_code_Id`,`accountCode`,`accountName` FROM `accountantcode` WHERE `codeLen` = 6";  
     $queryThierdtCode=mysqli_query($link,$sqlThierdCode);
     while($resThierdCode=mysqli_fetch_assoc($queryThierdtCode)){
      echo"<tr>
       <td class='col-sm-6'>$resThierdCode[accountName]</td>
       <td class='col-sm-4'>$resThierdCode[accountCode]</td>
      </tr>";
     }
    ?>
   </tbody>
  </table>
 </center>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  var table = $('.tableThierdCode').DataTable({
   fixedHeader: false,
   scrollY:'35vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false, 
   order:[[0, "asc"]],
  });
 }); 
</script>