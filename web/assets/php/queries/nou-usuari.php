<?php
require_once "../classes/classUsuari.php";

$usuari = new Usuari(
    $_POST["nom_usuari"],
    $_POST["cognom_usuari"],
    $_POST["username"],
    $_POST["contrasenya"]
);
$result = $usuari->newUser();
header("location:../../sign-in.php?message=" . $result . "&username=" . $_POST["username"]);
