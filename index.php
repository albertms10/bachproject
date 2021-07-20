<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inici · Bach’s Name Project</title>

    <?php define("ROOT", $_SERVER["DOCUMENT_ROOT"] . "/bachproject/") ?>
    <?php require_once ROOT . "assets/php/incs/top.php" ?>
    <?php require_once ROOT . "assets/php/classes/classAparicio.php" ?>
    <?php require_once ROOT . "assets/php/classes/classObra.php" ?>
    <style>

    </style>
</head>

<body>
    <div class="page container">
        <?php include ROOT . "assets/php/comps/nav-menu.php" ?>

        <main class="ui container">
            <h1 class="ui center aligned header" style="font-size:41px; margin: 2em 0 2em 0">
                <div id="subtitol" class="sub header">Sigueu benvinguts al</div>
                <div id="titol">Bach’s Name Project</div>
            </h1>
            <div class="ui three statistics">
                <div class="statistic">
                    <div id="aparicions" class="hidden value">
                        <?php echo Aparicio::countAparicions() ?>
                    </div>
                    <div class="label">
                        Aparicions
                    </div>
                </div>
                <div class="statistic">
                    <div id="percent" class="hidden value">
                        <?php echo number_format(Aparicio::percentAparicions(), "4", ",", ".") ?>&thinsp;<span class="sup">&percnt;</span>
                    </div>
                </div>
                <div class="statistic">
                    <div id="obres" class="hidden value">
                        <?php echo Obra::countObres() ?>
                    </div>
                    <div class="label">
                        Obres registrades
                    </div>
                </div>
            </div>

            <div class="ui grid" style="padding-top:7em">
                <div class="sixteen wide column">
                    <h3 class="ui dividing header">Activitat</h3>
                    <div class="ui feed">
                        <?php if ($activitats = Aparicio::llistaActivitat()) :
                            include ROOT . "assets/php/comps/feed-activitat.php";
                        else : ?>
                            <div>Sense activitat.</div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </main>

        <?php require_once ROOT . "assets/php/incs/bottom.php" ?>
        <script>
            $('#subtitol, #titol')
                .transition('toggle')
                .transition({
                    animation: 'scale',
                    duration: 500,
                    delay: 200
                });
            $('#aparicions, #percent, #obres')
                .transition('toggle')
                .transition({
                    animation: 'fade down',
                    duration: 500,
                    delay: 200
                });
            $('.popup-hover')
                .popup({
                    inline: true,
                    hoverable: true
                });
        </script>
</body>

</html>