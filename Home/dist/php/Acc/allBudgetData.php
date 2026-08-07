<?php
 include_once("../authCheck.php");
 include_once("../connection.php");

 $monthNames = ["","January","February","March","April","May","June","July","August","September","October","November","December"];

 $sql = "SELECT b.`budgetId`, b.`accountCode`, b.`budgetYear`, b.`budgetMonth`, b.`budgetAmount`, ac.`accountName`
  FROM `budgetPlan` b
  LEFT JOIN `accountantcode` ac ON ac.`accountCode` = b.`accountCode`
  ORDER BY b.`budgetYear` DESC, b.`budgetMonth` DESC, b.`accountCode` ASC";
 $result = mysqli_query($link, $sql) or die("ERROR_BUD:02 - " . mysqli_error($link));
?>
<div class="table-responsive-lg">
 <table class="table table-sm table-bordered table-striped myTableBudgetList w-100 text-center">
  <thead class="bg-primary">
   <th>Year</th>
   <th>Month</th>
   <th>Account Code</th>
   <th>Account Name</th>
   <th>Budget Amount</th>
   <th>Delete</th>
  </thead>
  <tbody>
   <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
     <td><?php echo htmlspecialchars($row['budgetYear']); ?></td>
     <td><?php echo htmlspecialchars($monthNames[(int)$row['budgetMonth']]); ?></td>
     <td><?php echo htmlspecialchars($row['accountCode']); ?></td>
     <td><?php echo htmlspecialchars($row['accountName'] ?? ''); ?></td>
     <td><?php echo number_format((float)$row['budgetAmount'], 2); ?></td>
     <td><button class="btn btn-sm btn-danger deleteBudgetLine" data-id="<?php echo (int)$row['budgetId']; ?>"><i class="fas fa-trash"></i></button></td>
    </tr>
   <?php endwhile; ?>
  </tbody>
 </table>
</div>
<script type="text/javascript">
 $('.myTableBudgetList').DataTable({
  scrollY:'30vh',
  scrollX: true,
  scrollCollapse: true,
  paging: false,
  info: false,
 });
</script>
