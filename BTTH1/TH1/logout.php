<?php
session_start();

// Destroy all session data
$_SESSION = [];
session_destroy();

// Redirect to main page
header('Location: TH1.php');
exit;
?>