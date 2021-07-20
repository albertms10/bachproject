<?php
require_once "../classes/classObra.php";

header("content-type: application/json");
echo Obra::dropdownObres($_GET["q"])["results"];
