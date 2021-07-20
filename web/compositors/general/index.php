<!DOCTYPE html>
<html lang="ca">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Compositor · Bach’s Name Project</title>

  <?php define("ROOT", $_SERVER["DOCUMENT_ROOT"] . "/bachproject/") ?>
  <?php require_once ROOT . "assets/php/incs/top.php" ?>
  <?php require_once ROOT . "assets/php/classes/classObra.php" ?>
  <?php require_once ROOT . "assets/php/classes/classCompositor.php" ?>
  <?php
  $id_compositor = isset($_GET["id"]) ? $_GET["id"] : 1;
  $limit = isset($_GET["limit"]) ? $_GET["limit"] : 1;
  $compositor = Compositor::infoCompositor($id_compositor, 1)
  ?>
</head>

<body>
  <div class="page container">
    <main class="ui container">
      <?php include ROOT . "assets/php/comps/nav-menu.php" ?>
      <?php include ROOT . "assets/php/comps/nav-compositor.php" ?>
      <h2 class="ui dividing header" style="margin-top:2rem; margin-bottom:2rem">Autoria d’obres</h2>
      <div class="ui three statistics">
        <div class="green statistic">
          <div class="value"><?php echo $compositor["obres_genuines"] ?></div>
          <div class="label">Genuïna</div>
        </div>
        <div class="orange statistic">
          <div class="value"><?php echo $compositor["obres_dubtoses"] ?></div>
          <div class="label">Dubtosa</div>
        </div>
        <div class="red statistic">
          <div class="value"><?php echo $compositor["obres_falses"] ?></div>
          <div class="label">Falsa</div>
        </div>
      </div>
      <?php if ($volums = Compositor::llistaVolumsCompositor($id_compositor)) : ?>
        <h2 class="ui dividing header" style="margin-top:2rem; margin-bottom:2rem">Volums</h2>
        <div class="ui four cards">
          <?php include ROOT . "assets/php/comps/cards-volums.php" ?>
        </div>
      <?php endif ?>
    </main>

    <?php require_once ROOT . "assets/php/incs/bottom.php" ?>
</body>

</html>