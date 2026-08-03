<?php 
date_default_timezone_set("Africa/Cairo");

$filename = "../../../../sysLicense.lic";
require_once("../../../../dist/php/lok.php");
		
function decrypt_from_file($key, $filename) {
  // Read encrypted data from file
  $encrypted_data = file_get_contents($filename);

  $c = base64_decode($encrypted_data);
  $ivlen = openssl_cipher_iv_length('aes-256-cbc');
  $iv = substr($c, 0, $ivlen);
  $ciphertext = substr($c, $ivlen);

  return openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
}
$decrypted_data = decrypt_from_file($key, $filename);

if($decrypted_data == $data)
{
	echo 1;
}
else
{
	echo 0;
}
?>