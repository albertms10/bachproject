<?php
require_once "../classes/classObra.php";

Obra::insertMoviment(
    $_GET["id_obra"],
    $_GET["num_moviment"],
    $_GET["titol_moviment"],
    $_GET["subtitol_moviment"],
    $_GET["llibret"],
    $_GET["num_compassos"],
    $_GET["id_tipus_moviment"],
    $_GET["id_tonalitat"]
);

define("ROOT", $_SERVER["DOCUMENT_ROOT"] . "/bachproject/");
define("HTML_PATH", "/bachproject/");
$count_moviments = true;
$id_obra = $_GET["id_obra"];
include "../comps/continguts-obra.php";
