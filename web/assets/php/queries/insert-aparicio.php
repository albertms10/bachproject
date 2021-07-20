<?php
require_once "../classes/classAparicio.php";

Aparicio::insertAparicio(
    $_GET["temps_inici"],
    $_GET["compas_inici"],
    $_GET["temps_final"],
    $_GET["compas_final"],
    $_GET["veu"],
    $_GET["transposicio"],
    $_GET["tipus"],
    $_GET["comentaris"],
    $_GET["id_obra"],
    $_GET["id_moviment"],
    $_GET["id_usuari"]
);

// define("ROOT", $_SERVER["DOCUMENT_ROOT"] . "/bachproject/");
// define("HTML_PATH", "/bachproject/");
// include "../comps/descripcio-relacions.php";
