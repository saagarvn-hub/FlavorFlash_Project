<?php
ob_start();
session_start();
session_destroy();
setcookie('remember_user', '', time() - 3600, "/");
setcookie('shopping_cart', '', time() - 3600, "/");
setcookie('cart_count', '', time() - 3600, "/");
header("Location: index.php");
exit();
?>