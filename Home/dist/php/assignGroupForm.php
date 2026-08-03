<?php
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$itemRowID = $_POST['idGrouping'];

$sqlGetItemData="SELECT `description`, `descriptionname`, `partnumber`
FROM `stockitems` WHERE `description` IS NOT NULL AND `itemsid` = $itemRowID";
$queryGetItemData=mysqli_query($link,$sqlGetItemData)or die("ERROR :01-AU_AU_S");
$resGetItemData = mysqli_fetch_assoc($queryGetItemData);

//
?>

<script type="text/javascript" src="dist/js/assigingItemGroup.js"></script>
<div class="modal-head">
<center><h4>Create stock grouping for Item/s
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
        	<input type="text" class="form-control" id="Group" list="allGroupName" />
            <datalist id="allGroupName">
            <?php
				$sqlGetGroup="SELECT `typeName` FROM `whtype` WHERE `typeRef` = 'Group'
				 ORDER BY `typeName` ASC";
				$queryGetGroup=mysqli_query($link,$sqlGetGroup)or die("ERROR :01-AU_AU_S");
				while($resGetGroup = mysqli_fetch_assoc($queryGetGroup))
				{
					echo "<option value='$resGetGroup[typeName]'>";
				}
			?>
            
            </datalist>
        </td>
         <td>
        	<input type="text" class="form-control" id="SubGroup" list="allSubGroupName" />
            <datalist id="allSubGroupName">
             <?php
				$sqlGetSGroup="SELECT `typeName` FROM `whtype` WHERE `typeRef` = 'Sub Group'
				 ORDER BY `typeName` ASC";
				$queryGetSGroup=mysqli_query($link,$sqlGetSGroup)or die("ERROR :01-AU_AU_S");
				while($resGetSGroup = mysqli_fetch_assoc($queryGetSGroup))
				{
					echo "<option value='$resGetSGroup[typeName]'>";
				}
			?>
            </datalist>
        </td>
         <td>
        	<input type="text" class="form-control" id="SSGroup" list="allSSGroupName" />
            <datalist id="allSSGroupName">
             <?php
				$sqlGetSSGroup="SELECT `typeName` FROM `whtype` WHERE `typeRef` = 'S-Sub Group'
				 ORDER BY `typeName` ASC";
				$queryGetSSGroup=mysqli_query($link,$sqlGetSSGroup)or die("ERROR :01-AU_AU_S");
				while($resGetSSGroup = mysqli_fetch_assoc($queryGetSSGroup))
				{
					echo "<option value='$resGetSSGroup[typeName]'>";
				}
			?>
            </datalist>
        </td>
        </tr>
        <tr>
        	<td align="center" colspan="3">
            	<button class="btn btn-success btn-sm" id="saveCreateNewGroupBTN">Save</button>
            </td>
        </tr>
    </table>

</div>
