<div class="table-responsive-lg">
 <center>   
  <table class="table table-sm table-bordered table-striped tableSecoundCode text-center" width="70%">
   <thead  class="bg-secondary">
    <th>Account Name</th>
    <th>Account Code</th>
   </thead>
   <tbody>
    <?php
     include_once("connection.php");
     $sqlSecoundCode="SELECT `accountCode`,`accountName` FROM `accountantcode` WHERE `codeLen` = 3";  
     $querySecoundCode=mysqli_query($link,$sqlSecoundCode);
     while($resSecoundCode=mysqli_fetch_assoc($querySecoundCode)){
      echo"<tr>
       <td>$resSecoundCode[accountName]</td>
       <td>$resSecoundCode[accountCode]</td>
      </tr>";
     }
    ?>
   </tbody>
  </table>
 </center>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  var table = $('.tableSecoundCode').DataTable({
   fixedHeader: false,
   scrollY:'35vh',
   scrollX: true,
   scrollCollapse: true,
   paging: false, 
   order:[[0, "asc"]],
  });
 }); 
</script>