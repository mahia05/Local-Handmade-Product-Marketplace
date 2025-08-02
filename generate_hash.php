<?php
$plainPassword = "Audimahitonu@068";
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
echo "Hashed password: " . $hashedPassword;
