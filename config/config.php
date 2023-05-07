<?php
define("CLIENT_ID", "Ab_qh4uWIjvwYJfiuSyWDPbY9KevULCEef1ymoBHEWmwPfHkf2EzhQ9iRa0eDBhIa4Znhagl0qNUD9Ja");
define("CURRENCY", "MXN");
define("KEY_TOKEN", "APR.wqc-354*");
define("MONEDA","$");

session_start();

$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}
?>


