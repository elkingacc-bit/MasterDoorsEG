<?php
echo "<option value=''>Choose</option>";
for($SF=10; $SF<=300; $SF+=5)
{
	$factor = round(($SF/100), 2);
	echo "
			<option value='$factor'>$SF%</option>
		 ";
}
?>