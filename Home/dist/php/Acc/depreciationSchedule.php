<style>
 h1{font-size: 12px;}
</style>
<?php
 include_once("../authCheck.php");
 date_default_timezone_set("Africa/Cairo");
 include_once("../connection.php");

 $assetId = (int)($_POST['assetId'] ?? 0);

 $stmt = mysqli_prepare($link, "SELECT `assetName`,`purchaseDate`,`purchaseCost`,`usefulLifeYears`,`salvageValue` FROM `fixedAssets` WHERE `fixedAssetId` = ?");
 mysqli_stmt_bind_param($stmt, "i", $assetId);
 mysqli_stmt_execute($stmt);
 $asset = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
 mysqli_stmt_close($stmt);

 if (!$asset) {
  echo "<div class='alert alert-danger'>Asset not found</div>";
  exit;
 }

 $cost = (float)$asset['purchaseCost'];
 $salvage = (float)$asset['salvageValue'];
 $usefulLife = (int)$asset['usefulLifeYears'];
 $totalMonths = $usefulLife * 12;
 $monthlyDepr = $totalMonths > 0 ? ($cost - $salvage) / $totalMonths : 0;

 $purchaseYear = (int)date('Y', strtotime($asset['purchaseDate']));
 $startMonth = (int)date('n', strtotime($asset['purchaseDate']));

 $schedule = [];
 $monthsUsed = 0;
 $accumulated = 0;
 $year = $purchaseYear;
 while ($monthsUsed < $totalMonths) {
  $monthsAvailableInYear = 13 - $startMonth;
  $monthsThisYear = min($monthsAvailableInYear, $totalMonths - $monthsUsed);
  $deprThisYear = $monthlyDepr * $monthsThisYear;
  $monthsUsed += $monthsThisYear;
  $accumulated += $deprThisYear;
  $schedule[] = ['year' => $year, 'months' => $monthsThisYear, 'depr' => $deprThisYear, 'accum' => $accumulated, 'book' => $cost - $accumulated];
  $year++;
  $startMonth = 1;
 }

 echo "<input value='Depreciation Schedule - " . htmlspecialchars($asset['assetName']) . "' class='reportTitel' hidden>";
?>
<div class="table-responsive-lg">
 <center>
  <button class="btn btn-secondary mb-3" id="dsBack"><i class="fas fa-arrow-left"></i> Back to Register</button>
  <h4><?php echo htmlspecialchars($asset['assetName']); ?> - Depreciation Schedule جدول الإهلاك</h4>
 </center>
 <table class="table table-sm table-bordered table-striped myTableDeprSchedule w-100 text-center">
  <thead class="bg-primary">
   <th>Year</th>
   <th>Months</th>
   <th>Depreciation Expense</th>
   <th>Accumulated Depreciation</th>
   <th>Book Value</th>
  </thead>
  <tbody>
   <tr><td>-</td><td>-</td><td>-</td><td>0.00</td><td><?php echo number_format($cost, 2); ?></td></tr>
   <?php foreach ($schedule as $s): ?>
    <tr>
     <td><?php echo $s['year']; ?></td>
     <td><?php echo $s['months']; ?></td>
     <td><?php echo number_format($s['depr'], 2); ?></td>
     <td><?php echo number_format($s['accum'], 2); ?></td>
     <td><?php echo number_format($s['book'], 2); ?></td>
    </tr>
   <?php endforeach; ?>
  </tbody>
 </table>
</div>
<script type="text/javascript">
 $(document).ready(function(){
  $("#dsBack").click(function(){
   $(".data_display").load("dist/php/Acc/allFixedAssetsData.php");
   return false;
  });
  $('.myTableDeprSchedule').DataTable({
   paging: false, searching: false, ordering: false, info: false,
  });
 });
</script>
