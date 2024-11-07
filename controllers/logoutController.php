<?php
session_start();
session_destroy();

// Vai para a página de login
header("Location: ../views/login.php");
exit;
?>
