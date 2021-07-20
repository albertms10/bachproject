<?php
require_once "../classes/classObra.php";

header("content-type: application/json");
echo "{
  \"results\": " . Obra::searchObres($_GET["q"])["results"] . "}";
