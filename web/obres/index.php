<!DOCTYPE html>
<html lang="ca">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Obres · Bach’s Name Project</title>

  <?php define("ROOT", $_SERVER["DOCUMENT_ROOT"] . "/bachproject/") ?>
  <?php require_once ROOT . "assets/php/incs/top.php" ?>
  <?php require_once ROOT . "assets/php/classes/classGenere.php" ?>
  <?php require_once ROOT . "assets/php/classes/classObra.php" ?>
  <?php require_once ROOT . "assets/php/classes/classCompositor.php" ?>
  <?php require_once ROOT . "assets/php/classes/classLlibre.php" ?>

  <?php
  $id_compositor = isset($_GET["compositor"]) ? $_GET["compositor"] : 1;
  $compositor = Compositor::infoCompositor($id_compositor);
  $altres_obres = Obra::llistaAltresObres($id_compositor);

  if (isset($_GET["subgenere"])) {
    $id_subgenere = $_GET["subgenere"];
    $info_subgenere = Genere::infoSubgenere($id_subgenere);
    $id_genere = $info_subgenere["id_genere"];
  } else {
    $id_genere = isset($_GET["genere"]) ? $_GET["genere"] : $compositor["primer_genere"];
  }
  if (isset($_GET["volum"])) {
    $id_volum = $_GET["volum"];
    $info_volum = Llibre::infoVolum($id_volum);
  } ?>
</head>

<body>
  <div class="page container">
    <main class="ui container">
      <?php include ROOT . "assets/php/comps/nav-menu.php" ?>
      <?php $header = 3;
      include ROOT . "assets/php/comps/header-compositor.php" ?>

      <div id="nav-menu" class="ui secondary pointing menu" style="margin-bottom:0; width:100%">
        <?php
        $generes = Genere::llistaGeneres($id_compositor);
        foreach ($generes as $key => $genere) : ?>
          <a href="obres/?compositor=<?php echo $id_compositor ?>&genere=<?php echo $genere["id_genere"] ?>" class="<?php echo Colors::$colors[$genere["id_genere"] - 1] ?><?php echo $genere["id_genere"] == $id_genere ? " active" : "" ?> item">
            <?php echo $genere["titol_genere"] ?>
          </a>
        <?php endforeach ?>
        <?php if ($altres_obres) : ?>
          <a href="obres/?compositor=<?php echo $id_compositor ?>&genere=a" class="grey<?php echo $id_genere == "a" ? " active" : "" ?> item">
            Altres obres
          </a>
        <?php endif ?>
      </div>

      <div class="ui grid stackable">
        <div class="four wide column">
          <?php include ROOT . "assets/php/comps/obres-sidebar.php" ?>
        </div>
        <div class="twelve wide column" style="margin-top:1.5rem; padding-left:2rem">
          <?php if ($id_genere == "a") : ?>
            <h1 id="titol" class="ui header" style="margin-bottom:1em">Altres obres</h1>
          <?php else : ?>
            <?php if (isset($id_volum)) : ?>
              <h1 id="titol" class="ui header" style="margin-bottom:1em"><?php echo $info_volum["titol_llibre"] . ($info_volum["num_volum"] ? " " . int_to_roman($info_volum["num_volum"]) : "") ?>
                <div class="sub header">
                  <?php if ($info_volum["llista_num_cataleg"])
                        echo "BWV " . compactize(json_decode($info_volum["llista_num_cataleg"]));
                      else echo "Sense obres" ?>
                </div>
              </h1>
            <?php else : ?>
              <h1 id="titol" class="ui header" style="margin-bottom:1em"><?php echo $info_subgenere["titol_subgenere"] ?>
                <div class="sub header">
                  <?php if ($info_subgenere["llista_num_cataleg"])
                        echo "BWV " . compactize(json_decode($info_subgenere["llista_num_cataleg"]));
                      else echo "Sense obres" ?>
                </div>
              </h1>
            <?php endif ?>
          <?php endif ?>
          <div class="ui three stackable cards">
            <?php if ($id_genere == "a") {
              $obres = $altres_obres;
            } else {
              if (isset($id_volum)) $obres = Obra::llistaObresVolum($id_volum);
              else $obres = Obra::llistaObresSubgenere($id_subgenere);
            }
            foreach ($obres as $obra) {
              $id_obra = $obra["id_obra"];
              $titol_obra = $obra["titol_obra"];
              $titol_alt = $obra["titol_alt"];
              $num_obra = $obra["num_obra"];
              $inicials_cataleg = $obra["inicials_cataleg"];
              $cataleg_complet = $obra["cataleg_complet"];
              $tonalitat = $obra["tonalitat"];
              $count_aparicions = $obra["count_aparicions"];
              $estat = $obra["estat"];
              include ROOT . "assets/php/comps/card-obra.php";
            } ?>
          </div>
        </div>
      </div>
    </main>

    <?php require_once ROOT . "assets/php/incs/bottom.php" ?>
    <script>
      $('#nav-menu.ui .item').on('click', function() {
        $('#nav-menu.ui .item').removeClass('active')
        $(this).addClass('active')
      });
    </script>
    <script>
      $('select.dropdown')
        .dropdown();

      $('.browse.more.item')
        .popup({
          inline: true,
          on: 'click',
          hoverable: true,
          position: 'bottom left'
        });

      $('.ui.sticky')
        .sticky();

      $('#titol')
        .transition('toggle')
        .transition({
          animation: 'fade right',
          duration: 200,
        });
    </script>
</body>

</html>