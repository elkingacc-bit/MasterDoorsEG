<!doctype html>
<html>

<script type="text/javascript" src="dist/js/AddItems.js"></script>
<div class="panel panel-primary" style="width:99%">
<div class="panel-heading">
<p></p>
</div>
    <div class="panel-body">
    	<form id="addItemForm" enctype="multipart/form-data" method="post">
        	
            <table class="table">
             <tr class="bg-warning">	
                <th>Machines</th>
                <th>Part Number</th>
                <th>Item Name</th>
                <!--<th>Manufctuer</th>
                <th>Supplier</th>-->
                <th data-toggle="tooltip" data-placement="top" title="Warehouse stock">WH Stock</th>
                <th data-toggle="tooltip" data-placement="top" title="Workshop stock">WS Stock</th>
            	<th>Image</th>
             </tr>
              <tr>
             	<!--<td>
              		<input type="text" id="allMachine" name="allMachine" class="form-control" 
                     autocomplete="off" list="AllMachineList"/>
                     <datalist id="AllMachineList">
                     </datalist>	
				</td>-->
                 <td>
              		<select id="allMachine" name="allMachine[]" class="allMachines" multiple="multiple"
					data-live-seacrh="true" style="width: 100%;">
<?php
date_default_timezone_set('Africa/Cairo');
include_once("connection.php");

$sqlGetMachines="SELECT `machinename`, `id`, `lineidref` FROM `allmachines`";
	$queryGetMachines=mysqli_query($link,$sqlGetMachines)or die("ERROR :01-AU_AU_S".mysqli_error($link));
	while($resGetMachines = mysqli_fetch_assoc($queryGetMachines))
	{
		$sqlGetLine="SELECT `linename`, `factorynum` FROM `alllines` WHERE `id` = $resGetMachines[lineidref]";
	$queryGetLines=mysqli_query($link,$sqlGetLine)or die("ERROR :02-AU_AU_S".mysqli_error($link));
	$resGetLine = mysqli_fetch_assoc($queryGetLines);
		
		echo "<option value='$resGetMachines[id]' data-toggle='tooltip' data-placement='top' 
		title='$resGetLine[factorynum] -> $resGetLine[linename]'>$resGetMachines[machinename]</option>";
	}
					
?>
                    
                	</select>
                </td>
                <td>
              		<input type="text" id="partNo" name="partNo" class="form-control" 
                    placeholder="Part No" autocomplete="off"/>
				</td>
                 <td>
              		<input type="text" id="ItemName" name="ItemName" class="form-control" 
                    placeholder="Item Name" autocomplete="off"/>
				</td>
                <td>
              		<input type="number" id="WHStock" name="WHStock" class="form-control" 
                    min="0" autocomplete="off"/>
				</td>
                 <td>
              		<input type="number" id="WShStock" name="WShStock" class="form-control" 
                    min="0" autocomplete="off"/>
				</td>
                <td>
                    <input class="form-control-file" type="file" id="ItemPhoto" name="sourceFile">
                </td>
              </tr>  
              <tr class="bg-warning">
              	<td data-toggle="tooltip" data-placement="top" title="Number of months"><b>Life Time</b></td>
                <td colspan="5"><b>Description</b></td>
              </tr>
              <tr>
              	<td>
                	<input type="number" min="1" name="lTime" id="lTime" class="form-control" 
                    autocomplete="off" placeholder="per month"/>
                </td>
              	<td colspan="5">
                	<textarea type="text" id="ItemDesc" name="ItemDesc" class="form-control" 
                    placeholder="Insert the description of this Item " autocomplete="off"></textarea>
                </td>
              </tr>
              <tr>
              	<td colspan="6" align="center">
                	<input type="submit" value="Save" id="saveItemBtn" class="btn btn-success" />
                </td>
              </tr>
            </table>
            
        </form>
                
    </div>
</div>
</html>
