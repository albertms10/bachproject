<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Compositors · Bach’s Name Project</title>

    <?php define("ROOT", $_SERVER["DOCUMENT_ROOT"] . "/bachproject/") ?>
    <?php require_once ROOT . "assets/php/incs/top.php" ?>
    <?php require_once ROOT . "assets/php/classes/classCompositor.php" ?>
</head>

<body>
    <div class="page container">
        <main class="ui container">
            <?php include ROOT . "assets/php/comps/nav-menu.php" ?>
            <h1 class="ui header">Compositors</h1>
            <div class="ui link three cards">
                <?php
                $compositors = Compositor::llistaCompositors();
                foreach ($compositors as $compositor) : ?>
                    <a href="compositors/general/?id=<?php echo $compositor["id_compositor"] ?>" class="ui fluid card">
                        <div class="content">
                            <div class="header">
                                <?php echo $compositor["nom_compositor"] . " " . $compositor["cognom_compositor"] ?>
                                <img class="left floated ui avatar image" src="<?php
                                                                                    $path = "assets/images/composers/" . strtolower(str_replace(" ", "-", $compositor["cognom_compositor"] . " " . $compositor["nom_compositor"])) . ".jpg";
                                                                                    if (file_exists(ROOT . $path)) echo HTML_PATH . $path;
                                                                                    else echo HTML_PATH . "assets/images/placeholder.png" ?>" alt="<?php echo $compositor["nom_complet"] ?>">
                            </div>
                            <div class="meta">
                                <div class="ui horizontal list">
                                    <div class="item"><?php echo $compositor["anys"] ?></div>
                                    <div class="item"><?php echo $compositor["edat"] ?> anys</div>
                                </div>
                            </div>
                        </div>
                        <div class="extra content">
                            <span>
                                <i class="file icon"></i>
                                <?php
                                    $count_obres = Compositor::countObresCompositor($compositor["id_compositor"]);
                                    echo $count_obres . " " . ($count_obres != 1 ? "obres" : "obra") ?>
                            </span>
                        </div>
                    </a>
                <?php endforeach ?>
            </div>
            <div class="ui divider"></div>
            <?php $compositors = Compositor::anysCompositors() ?>
            <canvas id="cronologia" height="<?php echo count($compositors) * 3.8 * 1.5 + 20 ?>px"></canvas>
        </main>

        <?php require_once ROOT . "assets/php/incs/bottom.php" ?>

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

                        var min = 1450
                        var max = 1850;

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
                                foreach ($compositors as $key => $compositor) {
                                    $anys = json_decode($compositor["anys"], true);
                                    echo '"' . $compositor["nom_complet"] . '"' . ($key < count($compositors) - 1 ? ", " : "");
                                }
                                ?>],
                    datasets: [{
                        backgroundColor: [<?php
                                            foreach ($compositors as $key => $compositor) {
                                                echo "window.chartColors." . Colors::$colors[$key % count(Colors::$colors)] . ($key < count($compositors) - 1 ? ", " : "");
                                            }
                                            ?>],
                        data: [<?php
                                foreach ($compositors as $key => $compositor) {
                                    $anys = json_decode($compositor["anys"], true);
                                    echo "[" . $anys[0]["any_naixement"] . ", " . $anys[0]["any_defuncio"] . "]" . ($key < count($compositors) - 1 ? ", " : "");
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
</body>

</html>