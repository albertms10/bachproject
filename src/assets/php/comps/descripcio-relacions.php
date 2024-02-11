<?php
if (!isset($relacions_1)) $relacions_1 = Obra::relacionsObra($id_obra, 1);
if (!isset($relacions_2)) $relacions_2 = Obra::relacionsObra($id_obra, 2);
$prev_rel = "";

if ($relacions = $relacions_1) {
  $direccionament = 1;
  include ROOT . "assets/php/comps/obres-relacions.php";
}

if ($relacions = $relacions_2) {
  $direccionament = 2;
  include ROOT . "assets/php/comps/obres-relacions.php";
}
