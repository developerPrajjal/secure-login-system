<?php
$ip = $_SERVER['REMOTE_ADDR'];
$log = date("Y-m-d H:i:s") . " - Failed login from $ip for $username\n";
file_put_contents("failed_logins.txt", $log, FILE_APPEND);
?>
