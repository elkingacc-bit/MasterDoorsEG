<div class="table-responsive-lg text-center">
 <center>
  <table class="table table-sm table-bordered table-striped tableFullCode text-center" width="70%">
   <thead  class="bg-secondary">
    <th>Account Name</th>
    <th>Account Code</th>
   </thead>
   <tbody>
    <?php
     include_once("../connection.php");
     $sqlFullCode="SELECT `accountant_code_Id`,`accountCode`,`accountName` FROM `accountantcode` WHERE `codeLen` = 12";  
     $queryFullCode=mysqli_query($link,$sqlFullCode);
     while($resFullCode=mysqli_fetch_assoc($queryFullCode)){
      echo"<tr>
       <td class='col-sm-6'>$resFullCode[accountName]</td>
       <td class='col-sm-4'>$resFullCode[accountCode]</td>
      </tr>";
     }
    ?>
   </tbody>
  </table>
 </center>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  var table = $('.tableFullCode').DataTable({
   fixedHeader: false,
   scrollY:'35vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false, 
   order:[[0, "asc"]],
  });
 }); 
</script>