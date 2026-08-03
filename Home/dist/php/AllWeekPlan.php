<!doctype html>
<html>

 <table class="table table-sm" style="width:100%">
  	<thead class="bg-warning">
    	<th>Project</th>
        <th>Customer</th>
        <th>Type</th>
        <th>Date</th>
    </thead>
    <tbody>
<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");


$sqlGetAllPlan = "SELECT * FROM `installplan` WHERE  YEARWEEK(`dateplan`, 1) = YEARWEEK(CURDATE(), 1)";
$queryGetAllPlan=mysqli_query($link,$sqlGetAllPlan)or die("ERROR :03-AU_AU_S");
while($resGetAllPlan= mysqli_fetch_assoc($queryGetAllPlan))
{
	$sqlGetAllPO="SELECT `PoNum`,`projectName`, `jobtype`, `custCode` FROM `customerpo`, 
	`job` WHERE (`jobidref` = `jobId` AND `poId` = $resGetAllPlan[poId])";
	$queryGetAllPO=mysqli_query($link,$sqlGetAllPO)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	$resGetAllPO= mysqli_fetch_assoc($queryGetAllPO);
	
		$sqlGetCustomer="SELECT `customername` FROM `customers` WHERE `customercode` 
		= $resGetAllPO[custCode]";
		$queryGetCustomer=mysqli_query($link,$sqlGetCustomer)or die("ERROR :03-AU_AU_S");
		$resGetCustomer= mysqli_fetch_assoc($queryGetCustomer);
	
	echo "
		<tr>
			<td>$resGetAllPO[projectName]</td>
			<td>$resGetCustomer[customername]</td>
			<td>$resGetAllPO[jobtype]</td>
			<td>".date("d/m/Y",strtotime($resGetAllPlan['dateplan']))."</td>
		
		</tr>
	
	
	";
}

?>    
    </tbody>
  </table>  


</html>