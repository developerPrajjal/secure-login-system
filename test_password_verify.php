<?php
$hash = '$2y$10$R9u.y4blEcuWdeM7lmBCNeG5DOuv08RDR1d6g64pNL0DTSb6V.SWa'; // hash for 'admin123'
$input_password = 'admin123';

if (password_verify($input_password, $hash)) {
    echo "Password verified successfully!";
} else {
    echo "Password verification failed!";
}
?>
