<?php
date_default_timezone_set("Africa/Cairo");

function hash_password($usps,$usps2 ) {
    $hash = password_hash($usps, PASSWORD_BCRYPT);
    return $hash;
}
$hashed_password = hash_password($usps, PASSWORD_ARGON2ID);

?>