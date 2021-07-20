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
        <canvas id="cronologia" height="<?php echo count($obres) * 3.8 + 20 ?>px"></canvas>
        <div class="ui buttons" style="margin-top:1rem">
          <a <?php echo $limit > 1 ? 'href="compositors/cronologia/?id=' . $id_compositor . '&limit=' . ($limit - 1) . '"' : "" ?> class="ui <?php echo $limit <= 1 ? "disabled " : "" ?>icon button">
            <i class="left chevron icon"></i>
          </a>
          <div class="ui button"><?php
                                    $anys_inici = $anys_final = [];
                                    foreach ($obres as $obra) {
                                      array_push($anys_inici, $obra["any_inici"]);
                                      array_push($anys_final, $obra["any_final"]);
                                    }
                                    echo (min($anys_inici) - $compositor["any_naixement"]) . "–" . (max($anys_final) - $compositor["any_naixement"])
                                    ?> anys</div>
          <a <?php echo $limit < $count_obres / 30 ? 'href="compositors/cronologia/?id=' . $id_compositor . '&limit=' . ($limit + 1) . '"' : "" ?> class="ui <?php echo $limit >= $count_obres / 30 ? "disabled " : "" ?>icon button">
            <i class="right chevron icon"></i>
          </a>
        </div>
      <?php endif ?>
    </main>

    <?php require_once ROOT . "assets/php/incs/bottom.php" ?>
    <?php if ($obres) : ?>
      <link rel="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
      <script>
        window.chartColors = {
          red: 'rgb(219, 40, 40)',
          orange: 'rgb(242, 113, 28)',
          yellow: 'rgb(251, 189, 8)',
          olive: 'rgb(181, 204, 24)',
          green: 'rgb(33, 186, 69)',
          teal: 'rgb(0, 181, 173)',
          blue: 'rgb(33, 133, 208)',
          violet: 'rgb(100, 53, 201)',
          purple: 'rgb(163, 51, 200)',
          pink: 'rgb(224, 57, 151)',
          brown: 'rgb(165, 103, 63)',
          grey: 'rgb(118, 118, 118)',
          black: 'rgb(27, 28, 29)'
        };

        Chart.defaults.global.defaultFontFamily = 'Lato';
        Chart.defaults.timeline = Chart.defaults.horizontalBar;
        Chart.controllers.timeline = Chart.controllers.horizontalBar.extend({
          initialize: function() {
            return Chart.controllers.bar.prototype.initialize.apply(this, arguments);
          }
        });

        Chart.pluginService.register({
          beforeInit: function(chart) {
            if (chart.config.type === 'timeline') {
              var config = chart.config;

              var data = config.data.datasets[0].data;

              var min = <?php echo $compositor["any_naixement"] ?>;
              var max = <?php echo ceil(($compositor["any_defuncio"] + 1) / 10) * 10 ?>;

              config.options.scales.xAxes[0].ticks.min = min;
              config.options.scales.xAxes[0].ticks.max = max;

              // creates a dummy dataset with background color transparent ending at the start time
              config.data.datasets.unshift({
                backgroundColor: 'rgba(0, 0, 0, 0)',
                data: data.map(function(e) {
                  return e[0];
                })
              });

              config.data.datasets[1].data = data.map(function(e) {
                return e[1] - e[0];
              });
            }
          }
        });
      </script>
      <script>
        var config = {
          type: 'timeline',
          data: {
            labels: [<?php
                        foreach ($obres as $key => $obra) {
                          $anys = json_decode($obra["anys"], true);
                          echo '"' . $obra["titol_complet"] . '"' . ($key < count($obres) - 1 ? ", " : "");
                        }
                        ?>],
            datasets: [{
              backgroundColor: [<?php
                                  foreach ($obres as $key => $obra) {
                                    echo "window.chartColors." . Colors::$colors[$obra["id_genere"] - 1] . ($key < count($obres) - 1 ? ", " : "");
                                  }
                                  ?>],
              data: [<?php
                        foreach ($obres as $key => $obra) {
                          $anys = json_decode($obra["anys"], true);
                          echo "[" . $anys[0]["any_inici"] . ", " . $anys[0]["any_final"] . "]" . ($key < count($obres) - 1 ? ", " : "");
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
                stacked: true
              }],
              yAxes: [{
                stacked: true,
                categoryPercentage: .5,
                barPercentage: 1
              }]
            }
          }
        };

        var ctx = document.getElementById("cronologia").getContext("2d");
        new Chart(ctx, config);
      </script>
    <?php endif ?>
</body>

</html>