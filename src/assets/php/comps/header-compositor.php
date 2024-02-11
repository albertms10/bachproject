<?php if (!isset($header) || $header == 1) : ?>
<h1 class="ui header">
  <?php elseif ($header == 3) : ?>
  <h3 class="ui pointer header" onclick='window.location = "../../../compositors/general/?id=<?php echo $compositor["'>
    <?php endif ?>
    <?php echo $compositor["nom_compositor"] . " " . $compositor["cognom_compositor"] ?>
    <img class="left floated ui avatar image" src="<?php
                                                    $path = "assets/images/composers/" . strtolower(str_replace(" ", "-", $compositor["cognom_compositor"] . " " . $compositor["nom_compositor"])) . ".jpg";
                                                    if (file_exists(ROOT . $path)) echo HTML_PATH . $path;
                                                    else echo HTML_PATH . "assets/images/placeholder.png" ?>" alt="<?php echo $compositor["nom_complet"] ?>" alt="<?php echo $compositor["nom_complet"] ?>">
    <div class="sub header"><?php echo strftime("%-e %B %Y", strtotime($compositor["naixement"])) . "&nbsp;–&nbsp;" . strftime("%-e %B %Y", strtotime($compositor["defuncio"])) ?></div>
    <?php if ($header == 3) : ?>
  </h3>
  <?php elseif (!isset($header) || $header == 1) : ?>
</h1>
<?php endif ?>