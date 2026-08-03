<style>
.img-Ak {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
  width: 60px;
  height:40px;
  
}
.toast {
  position: absolute;
  bottom: 0;
  right: 0;
}
</style>
<script type="text/javascript" src="dist/js/saveassignToAsKit.js"></script>
    <div class="panel-body">
    
    	<table>
        	<th>Choose</th>
            <td width="3%"></td>
            <td>
            	<input type="text" id="allAskKitNames" class="form-control" list="AsKitList"/>
                <datalist id="AsKitList">
<?php
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");
$sqlGetAsKits="SELECT `id`, `kitName` FROM `assemblykits` WHERE `Ref` = 0 ORDER BY `kitName` ASC";
$queryGetAsKits=mysqli_query($link,$sqlGetAsKits)or die("ERROR :01-AU_AU_S");
while($resGetAsKits = mysqli_fetch_assoc($queryGetAsKits))
	{
		echo "<option data-value='$resGetAsKits[id]' value='$resGetAsKits[kitName]'>";
	}
?>
                
                </datalist>
            </td>
        </table>
      
      <div class="StockAsKit" style="display:none">
      	<table class="table">
           <thead class="bg-warning">
        	<th>Part No.</th>
            <th>Name</th>
            <th>QTY</th>
            <th>Image</th>
           </thead>
           <tbody>
           		<td>
					<input type='text' id='partNo' name='partNo' class='form-control' 
                     autocomplete='off' style='font-weight:bold' value='' list="AllPartNum"/>
                     <datalist id="AllPartNum">
                     </datalist>
				</td>
				<td>
					<input type='text' id='ItemName' name='ItemName' class='form-control' 
                     autocomplete='off' style='font-weight:bold' value='' list="showAllItems"/>
                     <datalist id="showAllItems">
                     </datalist>
				</td>
                 <td>
              		<input type="number" id="requierQTY" name="requierQTY" class="form-control" 
                    min="0" autocomplete="off" data-toggle="tooltip" data-placement="top" value="0"/>
				</td>
                <td><img data-enlargeable alt='Item' src='dist/img/items/defaultItem.jpg' id="itemImage" 
                class='img-thumbnail img-Ak' style='cursor: zoom-in;'/>
                </td>
        <tr>
        	<td colspan="6" align="center">
            	<button class="btn btn-sm btn-success" id="AddToAsKitBTN">Save</button>
            </td>
        </tr>
           </tbody> 
        </table>
      </div>    
    <div class="AddedItems">
    
    </div>      
	</div>
