<?php
// Password to be encrypted for a .htpasswd file
$clearTextPassword = 'some password';

// Encrypt password
$password = crypt($clearTextPassword, base64_encode($clearTextPassword));

// Print encrypted password
echo $password . " asdawda";

echo system('htpasswd -nb admin admin');
?>