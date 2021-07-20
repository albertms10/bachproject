<a href="<?php echo HTML_PATH ?>obres/obra/?id=<?php echo $id_obra ?>" class="ui card" <?php
                                                              if ($estat == "F") echo 'style="background-color:#a5673f10"';
                                                              else if ($estat == "P") echo 'style="opacity:.5"';
                                                              ?>>
  <div class="content">
    <h3 class="ui header"><?php echo $titol_obra . ($num_obra ? " núm. " . $num_obra : "") ?>
      <?php if ($titol_alt) : ?>
        <div class="sub header"><?php echo $titol_alt ?></div>
      <?php endif ?>
    </h3>
  </div>
  <div class="extra content">
    <div class="ui label"><?php echo $inicials_cataleg ?>
      <div class="detail"><?php echo $cataleg_complet ?></div>
    </div>
    <?php if ($tonalitat) : ?>
      <div class="ui basic label"><?php echo $tonalitat ?></div>
    <?php endif ?>
    <?php if ($count_aparicions) : ?>
      <div class="ui right floated yellow circular label" style="margin: .1rem auto .1rem auto">
        <i class="bullseye icon"></i>
        <div class="detail" style="margin: 0 .2rem 0 0"><?php echo $count_aparicions ?></div>
      </div>
    <?php endif ?>
  </div>
</a>