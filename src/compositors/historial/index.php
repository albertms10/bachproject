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
  $compositor = Compositor::infoCompositor($id_compositor)
  ?>
</head>

<body>
  <div class="page container">
    <main class="ui container">
      <?php include ROOT . "assets/php/comps/nav-menu.php" ?>
      <?php include ROOT . "assets/php/comps/nav-compositor.php" ?>

      <?php if ($obres) : ?>
        <canvas id="obresAnys"></canvas>
      <?php endif ?>
    </main>

    <?php require_once ROOT . "assets/php/incs/bottom.php" ?>
    <?php if ($obres) : ?>
      <link rel="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
      <script>
        var ctx = document.getElementById("obresAnys");
        var scatterChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: [<?php
                        $anys_range = range($compositor["any_naixement"], $compositor["any_defuncio"]);
                        foreach ($anys_range as $key => $any) {
                          echo $any . ($key < $compositor["any_defuncio"] - $compositor["any_naixement"] ? ", " : "");
                        }
                        ?>],
            datasets: [{
              data: [<?php
                        foreach ($anys_range as $key_1 => $any) {
                          foreach ($obres_anys as $key_2 => $obra_any) {
                            if ($any == $obra_any["any_inici"]) {
                              echo $obra_any["num_obres"] . ($key_1 < count($anys_range) - 1 ? ", " : "");
                              break;
                            } elseif ($key_2 == count($obres_anys) - 1) {
                              echo 0 . ($key_1 < count($anys_range) - 1 ? ", " : "");
                            }
                          }
                        }
                        ?>]
            }]
          },
          options: {
            legend: {
              display: false,
            },
            scales: {
              xAxes: [{
                ticks: {
                  min: <?php echo $compositor["any_naixement"] ?>,
                  max: <?php echo $compositor["any_defuncio"] ?>
                }
              }]
            }
          }
        });
      </script>
    <?php endif ?>
</body>

</html>