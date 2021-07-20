<div class="ui sticky">
  <div id="obres-sidebar" class="ui vertical pointing menu" style="width:19.3rem!important; margin-top:1rem">
    <?php if ($id_genere == "a") : ?>
      <a href="obres/?compositor=<?php echo $id_compositor ?>&genere=a" class="active item">Altres obres</a>
      <?php else :
        $subgeneres = Genere::llistaSubgeneres($id_genere);
        foreach ($subgeneres as $key => $subgenere) :
          if ($key == 0 && !isset($id_subgenere) && !isset($id_volum)) {
            $id_subgenere = $subgenere["id_subgenere"];
            $info_subgenere = Genere::infoSubgenere($id_subgenere);
          } ?>
        <a href="obres/?compositor=<?php echo $id_compositor ?>&subgenere=<?php echo $subgenere["id_subgenere"] ?>" class="<?php echo $id_subgenere == $subgenere["id_subgenere"] ? "active " : "" ?>item">
          <?php echo substr($subgenere["titol_subgenere"], 0, 28) . (substr($subgenere["titol_subgenere"], 30, 1) ? "…" : "") ?>
          <div class="ui left <?php echo Colors::$colors[$subgenere["id_genere"] - 1] ?> label"><?php echo $subgenere["count_obres"] ?></div>
        </a>
    <?php endforeach;
    endif ?>
  </div>
  <?php if ($volums = Genere::llistaVolumsGenere($id_genere)) : ?>
    <h3 class="ui header">Llibres</h3>
    <div id="obres-sidebar" class="ui vertical pointing menu" style="width:19.3rem!important">
      <?php foreach ($volums as $volum) : ?>
        <a href="obres/?compositor=<?php echo $id_compositor ?>&genere=<?php echo $id_genere ?>&volum=<?php echo $volum["id_volum"] ?>" class="item <?php echo $id_volum == $volum["id_volum"] ? "active" : "" ?>">
          <?php echo substr($volum["titol_llibre"], 0, 28) . (substr($subgenere["titol_subgenere"], 30, 1) ? "…" : "") . ($volum["num_volum"] ? " " . int_to_roman($volum["num_volum"]) : "") ?>
          <div class="ui left label"><?php echo $volum["count_obres"] ?></div>
        </a>
      <?php endforeach ?>
    </div>
  <?php endif ?>
</div>

<script>
  window.onscroll = function() {
    if (window.scrollY > 195) {
      document.getElementById("obres-sidebar").style.marginTop = "4rem"
    } else {
      document.getElementById("obres-sidebar").style.marginTop = "1rem"
    }
  }
</script>