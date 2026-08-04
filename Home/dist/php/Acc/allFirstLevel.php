<div class="table-responsive-lg">
 <center>
  <table class="table table-sm table-bordered table-striped tableFirstCode text-center" width="70%">
   <thead  class="bg-secondary">
    <th>Account Name</th>
    <th>Account Code</th>
   </thead>
   <tbody>
    <?php
     include_once("../connection.php");
     $sqlFirstCode="SELECT `accountant_code_Id`,`accountCode`,`accountName` FROM `accountantcode` WHERE `codeLen` = 1";  
     $queryFirstCode=mysqli_query($link,$sqlFirstCode);
     while($resFirstCode=mysqli_fetch_assoc($queryFirstCode)){
      echo"<tr>
       <td class='col-sm-6'>$resFirstCode[accountName]</td>
       <td class='col-sm-4'>$resFirstCode[accountCode]</td>
      </tr>";
     }
    ?>
   </tbody>
  </table>
 </center>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  var table = $('.tableFirstCode').DataTable({
   fixedHeader: false,
   scrollY:'35vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false, 
   order:[[0, "asc"]],
  });
 }); 
</script>