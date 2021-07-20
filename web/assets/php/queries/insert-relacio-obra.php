<?php
require_once "../classes/classObra.php";
require_once "../incs/functions.php";

Obra::insertRelacioObra(
    $_GET["id_obra_1"],
    $_GET["id_tipus_relacio_obra"],
    $_GET["is_parcialment"],
    $_GET["id_obra_2"]
);

$id_obra = $_GET["id_obra_1"];
define("ROOT", $_SERVER["DOCUMENT_ROOT"] . "/bachproject/");
define("HTML_PATH", "/bachproject/");
include "../comps/descripcio-relacions.php";
