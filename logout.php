<?php
session_start();
$_SESSION['username'] = '';
unset($_SESSION['username']);
?>
<script> window.location.href = "login.php";</script>