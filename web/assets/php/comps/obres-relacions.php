<?php
$d = $direccionament == 1 ? 2 : 1;
foreach ($relacions as $key => $relacio) :
  if ($prev_rel != $relacio["id_tipus_relacio_obra"]) :
    if ($key != 0) echo "</div>" ?>
<h5 class="ui header" style="margin: .75rem 0 .25rem 0"><?php echo ucfirst($relacio["tipus_relacio_obra_$direccionament"]) ?></h5>
<div style="line-height: 2.4rem;">
  <?php endif;

    $id_obra_p = $relacio["id_obra_$d"];
    $inicials_cataleg = $relacio["inicials_cataleg"];
    $cataleg_complet = $relacio["cataleg_complet"];
    $titol_obra = $relacio["titol_obra"];
    $num_obra = $relacio["num_obra"];
    $id_subgenere = $relacio["id_subgenere"];
    $titol_subgenere = $relacio["titol_subgenere"];
    $id_genere = $relacio["id_genere"];
    $titol_genere = $relacio["titol_genere"];

    include "popup-obra.php";
    $prev_rel = $relacio["id_tipus_relacio_obra"];
    if ($key == count($relacions) - 1) echo "</div>";
  endforeach;
