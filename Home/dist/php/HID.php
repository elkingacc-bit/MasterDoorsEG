<?php 
date_default_timezone_set("Africa/Cairo");
function getMachineId() {
    $fingerprint = [php_uname(), disk_total_space('.'), filectime('/'), phpversion()];
    return hash('sha256', json_encode($fingerprint));
}
$HID =  getMachineId();

echo $HID;


?>