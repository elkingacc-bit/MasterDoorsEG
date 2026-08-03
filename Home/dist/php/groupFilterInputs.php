<?php
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
?>

<table class="table table-sm" style="width:auto">
   <thead>
   		<th>Group</th>
        <th>Sub-Group</th>
        <th>Sup-Sup-Group</th>
        <th>Assembly Kit</th>
   </thead>
   <tbody >
      <td>
        	<input type="text" class="form-control input-sm Groupinput" id="Groupinput" list="allGroupName" 
            />
            <datalist id="allGroupName" class="allGroupName">
            <?php
				$sqlGetGroup="SELECT `categoryname`, `category` FROM `stockitems` WHERE `category` 
				IS NOT NULL AND `category` != 1611 ORDER BY `categoryname` ASC";
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
        	<input type="text" class="form-control input-sm SubGroupInput" id="SubGroupInput" list="allSubGroupName" 
            />
            <datalist id="allSubGroupName" class="allSubGroupName">
            <?php 
			$sqlGetSGroup="SELECT `subcategoryname`, `subcategory` FROM `stockitems` WHERE 
			`subcategory` IS NOT NULL AND `category` != 161111 ORDER BY `subcategoryname` ASC";
			$queryGetSGroup=mysqli_query($link,$sqlGetSGroup)or die("ERROR :02-AU_AU_S");
			while($resGetSGroup = mysqli_fetch_assoc($queryGetSGroup))
			{
				echo "<option data-value='$resGetSGroup[subcategory]' value='$resGetSGroup[subcategoryname]'>";
			}
			?>
            </datalist>
        </td>
         <td>
        	<input type="text" class="form-control input-sm SSGroupInput" id="SSGroupInput" list="allSSGroupName" 
            />
            <datalist id="allSSGroupName" class="allSSGroupName">
            <?php
			$sqlGetSSGroup="SELECT `subSCatgName`, `subSCatg`  FROM `stockitems` WHERE 
			`subSCatg` IS NOT NULL AND `category` != 16111111 ORDER BY `subSCatgName` ASC";
			$queryGetSSGroup=mysqli_query($link,$sqlGetSSGroup)or die("ERROR :03-AU_AU_S");
			while($resGetSSGroup = mysqli_fetch_assoc($queryGetSSGroup))
			{
				echo "<option data-value='$resGetSSGroup[subSCatg]' value='$resGetSSGroup[subSCatgName]'>";
			}
			?>
            </datalist>
        </td>    
         <td>
        	<input type="text" class="form-control AsKitNames" id="AsKitNames" list="AllAsKitList" 
            />
            <datalist id="AllAsKitList" class="AllAsKitList">
            <?php
			$sqlGetAsKitNames="SELECT `id`, `kitName` FROM `assemblykits` ORDER BY `kitName` ASC";
			$queryGetAsKitNames=mysqli_query($link,$sqlGetAsKitNames)or die("ERROR :04-AU_AU_S");
			while($resGetAsKitNames = mysqli_fetch_assoc($queryGetAsKitNames))
			{
				echo "<option data-value='$resGetAsKitNames[id]' value='$resGetAsKitNames[kitName]'>";
			}
			?>
            </datalist>
        </td>    		
   </tbody>
</table>
<script type="text/javascript" src="dist/js/multiFilterRpt.js"></script>