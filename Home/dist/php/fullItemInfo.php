<?php
@session_start();
date_default_timezone_set('Africa/Cairo');
 include_once("connection.php");
 if(!empty($_SESSION['username']))
 {
 
 $DescCode = $_POST['Descripid'];
 
 $sqlGetItem="SELECT `descriptionname`, `partnumber`, `technicalsheet` FROM `stockitems` 
 WHERE `description` = $DescCode";
$queryGetItem=mysqli_query($link,$sqlGetItem)or die("ERROR :01AU_AU_S");
$resGetItem = mysqli_fetch_assoc($queryGetItem);


	if($resGetItem['technicalsheet'] == NULL || $resGetItem['technicalsheet'] == "")
		{
			$techinaclData = "No data available";
		}
	else
		{
			$techinaclData = $resGetItem['technicalsheet'];
		}
		
$catgLenght=substr($DescCode,0,4);
$subCatgLenght=substr($DescCode,0,6);
$subSubCatgLenght=substr($DescCode,0,8);

$sqlGetCatg=" SELECT `categoryname` FROM `stockitems` WHERE `category` = $catgLenght";
$queryCatg=mysqli_query($link,$sqlGetCatg)or die("ERROR :01_1-GCS_IWHDA_S".mysqli_error($link));
$resCatg=mysqli_fetch_assoc($queryCatg);

$group = $resCatg['categoryname'];
								
$sqlGetSubcatg=" SELECT `subcategoryname` FROM `stockitems`	WHERE `subcategory` = $subCatgLenght";
$querySubcatg=mysqli_query($link,$sqlGetSubcatg)or die("ERROR :01_2-GCS_IWHDA_S".mysqli_error($link));
$resSubcatg=mysqli_fetch_assoc($querySubcatg);

$subGroup = $resSubcatg['subcategoryname'];

$sqlGetSubSubcatg=" SELECT `subSCatgName`, `subSCatg` FROM `stockitems` WHERE `subSCatg` = $subSubCatgLenght";
$querySubSubcatg=mysqli_query($link,$sqlGetSubSubcatg)or die("ERROR :01_3-GCS_IWHDA_S".mysqli_error($link));
$resSubSubcatg=mysqli_fetch_assoc($querySubSubcatg);

$sSubGroup = $resSubSubcatg['subSCatgName'];	

$asKitNames = array();
$sqlGetKitID=" SELECT `assemplyRowId` FROM `kitscomponents` WHERE `descripcode` = $DescCode";
$queryKitID=mysqli_query($link,$sqlGetKitID)or die("ERROR :01_4-GCS_IWHDA_S".mysqli_error($link));
while($resKitID=mysqli_fetch_assoc($queryKitID))
{
	$sqlGetKitName=" SELECT `kitName` FROM `assemblykits` WHERE `id` = $resKitID[assemplyRowId]";
	$queryKitName=mysqli_query($link,$sqlGetKitName)or die("ERROR :01_5-GCS_IWHDA_S".mysqli_error($link));
	$resKitName=mysqli_fetch_assoc($queryKitName);
	
	array_push($asKitNames, $resKitName['kitName']);
}
	
$sqlGetStock=" SELECT `warehouse` FROM `lookupstock` WHERE `descriptioncode` = $DescCode";
$queryGetStock=mysqli_query($link,$sqlGetStock)or die("ERROR :03-GCS_IWHDA_S".mysqli_error($link));
$resGetStock=mysqli_fetch_assoc($queryGetStock);	

$WHStock = $resGetStock['warehouse'];
	

 }
 
 ?>
 <style>
 	h5{
	text-align:center;
	}
 </style>
 
  <div class="modal-header bg-primary">
       <h5 class="modal-title text-center">Item General Information Screen </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
 
 <div class="modal-body">
 	<div class="table-responsive">
    	<table class="table table-bordered ">
    		
            <tr class="bg-warning">
            	<th>Part No</th>
                <th>Name</th>
                <th data-toggle='tooltip' data-placement='bottom' data-html='true'
					title='Wearhouse Stock'>Stock</th>
                
                <th colspan="3">Technical Data</th>       
            </tr>
            <tr>
            	<td><?php echo $resGetItem['partnumber'];?></td>
                <td><?php echo $resGetItem['descriptionname'];?></td>
                <td><?php echo $WHStock;?></td>
                <td colspan="3"><?php echo $techinaclData;?></td>
            </tr>
            <tr class="bg-warning">
            	<th colspan="3">Grouping</th>
                <th colspan="3">Member Of</th>
            </tr>
            <tr>
            	<td colspan="3">
                <?php 
					echo "
							$group <br> $subGroup <br> $sSubGroup
						";
				?>
                </td>
                <td colspan="3">
                <?php
						$countAsKit = count($asKitNames);
						for($No = 0 ; $No < $countAsKit; $No++)
						{
							echo "
							     $asKitNames[$No] <br> 
								";
						}
						
				?>
                </td>
            </tr>
        </table>
    </div>     
 </div>
 
 <script type="text/javascript">
 $(document).ready(function() {

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

});
 
 </script>