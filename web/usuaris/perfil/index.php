<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Usuari · Bach’s Name Project</title>

    <?php define("ROOT", $_SERVER["DOCUMENT_ROOT"] . "/bachproject/") ?>
    <?php require_once ROOT . "assets/php/incs/top.php" ?>
    <?php require_once ROOT . "assets/php/classes/classUsuari.php" ?>
    <?php require_once ROOT . "assets/php/classes/classObra.php" ?>
    <?php require_once ROOT . "assets/php/classes/classAparicio.php" ?>
    <?php $id_usuari = isset($_GET["id"]) ? $_GET["id"] : 1 ?>
    <?php $usuari = Usuari::infoUsuari($id_usuari) ?>
</head>

<body>
    <div class="page container">
        <main class="ui container">
            <?php include ROOT . "assets/php/comps/nav-menu.php" ?>
            <h1 class="ui header" style="margin-bottom:4rem">
                <?php echo $usuari["nom_usuari"] . " " . $usuari["cognom_usuari"] ?>
                <img class="left floated ui avatar image" src="<?php
                                                                $path = "assets/images/users/" . $usuari["id_usuari"] . ".jpg";
                                                                if (file_exists(ROOT . $path)) echo HTML_PATH . $path;
                                                                else echo HTML_PATH . "assets/images/placeholder.png" ?>" alt="<?php echo $usuari["nom_complet"] ?>" alt="<?php echo $usuari["nom_complet"] ?>">
                <div class="sub header">@<?php echo $usuari["username"] ?></div>
            </h1>
            <h3 class="ui dividing header">Activitat</h3>
            <div class="ui feed">
                <?php if ($activitats = Aparicio::llistaActivitat()) :
                    include ROOT . "assets/php/comps/feed-activitat.php";
                else : ?>
                    <div>Sense activitat.</div>
                <?php endif ?>
            </div>
        </main>

        <?php require_once ROOT . "assets/php/incs/bottom.php" ?>
        <script>
            $('.popup-hover')
                .popup({
                    inline: true,
                    hoverable: true
                });
        </script>
</body>

</html>