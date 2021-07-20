<?php foreach ($volums as $volum) : ?>
  <a href="obres/?compositor=<?php echo $id_compositor ?>&genere=<?php echo $volum["id_genere"] ? $volum["id_genere"] : $id_genere ?>&volum=<?php echo $volum["id_volum"] ?>" class="ui raised link card">
    <div class="image">
      <i class="book icon volum"></i>
    </div>
    <div class="extra">
      <span class="list" style="font-weight:bold">
        <?php echo $volum["titol_llibre"] . " " . int_to_roman($volum["num_volum"]) ?>
      </span><?php if ($volum["num_obra_volum"]) : ?><span class="list">núm. <?php echo $volum["num_obra_volum"] ?></span>
      <?php endif ?>
    </div>
  </a>
<?php endforeach ?>