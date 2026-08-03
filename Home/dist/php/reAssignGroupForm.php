<?php
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$itemRowID = $_POST['itemRowIdGrouping'];

$sqlGetItemData="SELECT `description`, `descriptionname`, `partnumber`
FROM `stockitems` WHERE `description` IS NOT NULL AND `itemsid` = $itemRowID";
$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :01-AU_AU_S");
$resGetItemData = mysqli_fetch_assoc($queryGetItemData);
$descripId = $resGetItemData['description'];

$catgLenght=substr($descripId,0,4);
$subCatgLenght=substr($descripId,0,6);
$subSubCatgLenght=substr($descripId,0,8);

$sqlGetCatg=" SELECT `categoryname` FROM `stockitems` WHERE `category` = $catgLenght";
$queryCatg=mysqli_query($link,$sqlGetCatg)or die("ERROR :01_1-GCS_IWHDA_S".mysqli_error($link));
$resCatg=mysqli_fetch_assoc($queryCatg);

$group = $resCatg['categoryname'];
								
$sqlGetSubcatg=" SELECT `subcategoryname` FROM `stockitems`	WHERE `subcategory` = $subCatgLenght";
$querySubcatg=mysqli_query($link,$sqlGetSubcatg)or die("ERROR :01_2-GCS_IWHDA_S".mysqli_error($link));
$resSubcatg=mysqli_fetch_assoc($querySubcatg);

$subGroup = $resSubcatg['subcategoryname'];

$sqlGetSubSubcatg=" SELECT `subSCatgName`, `subSCatg` FROM `stockitems` WHERE `subSCatg` = $subSubCatgLenght";
$querySubSubcatg=mysqli_query($link,$sqlGetSubSubcatg)or die("ERROR :01_2-GCS_IWHDA_S".mysqli_error($link));
$resSubSubcatg=mysqli_fetch_assoc($querySubSubcatg);

$sSubGroup = $resSubSubcatg['subSCatgName'];
//
?>

<script type="text/javascript" src="dist/js/reAssigingItemGroup.js"></script>
<div class="modal-head">
<center><h4>Edit stock grouping for Item/s
<br>
 name:<span style="color:blue">
<?php echo $resGetItemData['descriptionname']?></span> | Part No : <span style="color:blue">
<?php echo $resGetItemData['partnumber']?></span>
</h4></center>
</div>
<div>
<input type="number" id="ItemRowId" style="display:none" value="<?php echo $itemRowID;?>"/>
	<table class="table" style="width:auto">
    	<th>Group</th>
        <th>Sub Group</th>
        <th>Sub Sub Group</th>
        <tr>
        <td>
        	<input type="text" class="form-control Groupinput" id="Groupinput" list="allGroupName" 
            value="<?php echo $group; ?>" />
            <datalist id="allGroupName" class="allGroupName">
            <?php
				$sqlGetGroup="SELECT `categoryname`, `category` FROM `stockitems` WHERE `category` 
				IS NOT NULL ORDER BY `categoryname` ASC";
				$queryGetGroup=mysqli_query($link,$sqlGetGroup)or die("ERROR :01-AU_AU_S");
				while($resGetGroup = mysqli_fetch_assoc($queryGetGroup))
				{
					echo "<option data-value='$resGetGroup[category]' 
					value='$resGetGroup[categoryname]'>";
				}
			?>
            
            </datalist>
        </td>
         <td>
        	<input type="text" class="form-control SubGroupInput" id="SubGroupInput" list="allSubGroupName" 
            value="<?php echo $subGroup; ?>"/>
            <datalist id="allSubGroupName" class="allSubGroupName">
            <?php 
			$sqlGetSGroup="SELECT `subcategoryname`, `subcategory` FROM `stockitems` WHERE 
			`subcategory` IS NOT NULL ORDER BY `subcategoryname` ASC";
			$queryGetSGroup=mysqli_query($link,$sqlGetSGroup)or die("ERROR :01-AU_AU_S");
			while($resGetSGroup = mysqli_fetch_assoc($queryGetSGroup))
			{
				echo "<option data-value='$resGetSGroup[subcategory]' value='$resGetSGroup[subcategoryname]'>";
			}
			?>
            </datalist>
        </td>
         <td>
        	<input type="text" class="form-control SSGroupInput" id="SSGroupInput" list="allSSGroupName" 
            value="<?php echo $sSubGroup; ?>"/>
            <datalist id="allSSGroupName" class="allSSGroupName">
            <?php
			$sqlGetSSGroup="SELECT `subSCatgName`, `subSCatg`  FROM `stockitems` WHERE 
			`subSCatg` IS NOT NULL ORDER BY `subSCatgName` ASC";
			$queryGetSSGroup=mysqli_query($link,$sqlGetSSGroup)or die("ERROR :01-AU_AU_S");
			while($resGetSSGroup = mysqli_fetch_assoc($queryGetSSGroup))
			{
				echo "<option data-value='$resGetSSGroup[subSCatg]' value='$resGetSSGroup[subSCatgName]'>";
			}
			?>
            </datalist>
        </td>
        </tr>
        <tr>
        	<td align="center" colspan="3">
            	<button class="btn btn-success btn-sm" id="saveEditItemGroupBTN">Save</button>
            </td>
        </tr>
    </table>

</div>


