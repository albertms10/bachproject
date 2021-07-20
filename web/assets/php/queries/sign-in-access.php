<?php
require_once "../classes/classUsuari.php";

define("HTML_PATH", "/bachproject/");
$signin = Usuari::checkLogin($_POST["username"], $_POST["contrasenya"]);

if ($signin == 1)
    header("location:" . HTML_PATH);
else
    header("location:" . HTML_PATH . "sign-in/?message=" . $signin);
