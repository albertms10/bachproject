<?php
$obres_anys = Compositor::countObresAnysCompositor($id_compositor);
$count_obres = Obra::countObresAnys($id_compositor);
$obres = Obra::llistaObresAnys($id_compositor, ($limit - 1) * 30);

include "header-compositor.php" ?>

<a href="obres/?compositor=<?php echo $id_compositor ?>" class="ui right floated button" style="position:relative; bottom:3.5rem">
  <i class="file icon"></i>
  Totes les obres
</a>

<div class="ui secondary pointing menu" style="width:100%">
  <a href="<?php echo HTML_PATH ?>compositors/general/?id=<?php echo $id_compositor ?>" class="item<?php echo BASENAME == "general" ? " active" : "" ?>">General</a>
  <?php if (count($obres_anys) > 1) : ?>
    <a href="<?php echo HTML_PATH ?>compositors/historial/?id=<?php echo $id_compositor ?>" class="item<?php echo BASENAME == "historial" ? " active" : "" ?>">Historial</a>
  <?php endif;
  if ($count_obres) : ?>
    <a href="<?php echo HTML_PATH ?>compositors/cronologia/?id=<?php echo $id_compositor ?>" class="item<?php echo BASENAME == "cronologia" ? " active" : "" ?>">Cronologia</a>
  <?php endif ?>
</div>