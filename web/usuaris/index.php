<!DOCTYPE html>
<html lang="ca">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Usuaris · Bach’s Name Project</title>

  <?php define("ROOT", $_SERVER["DOCUMENT_ROOT"] . "/bachproject/") ?>
  <?php require_once ROOT . "assets/php/incs/top.php" ?>
  <?php require_once ROOT . "assets/php/classes/classUsuari.php" ?>
</head>

<body>
  <div class="page container">
    <main class="ui container">
      <?php include ROOT . "assets/php/comps/nav-menu.php" ?>
      <h1 class="ui header">Usuaris</h1>
      <div class="ui link three cards">
        <?php
        $usuaris = Usuari::llistaUsuaris();
        foreach ($usuaris as $usuari) : ?>
          <a href="usuaris/perfil/?id=<?php echo $usuari["id_usuari"] ?>" class="ui fluid<?php echo $usuari["id_usuari"] == $_SESSION["id"] ? " red" : "" ?> card">
            <div class="content">
              <div class="header">
                <?php echo $usuari["nom_usuari"] . " " . $usuari["cognom_usuari"] ?>
                <img class="left floated ui avatar image" src="<?php
                                                                  $path = "assets/images/users/" . $usuari["id_usuari"] . ".jpg";
                                                                  if (file_exists(ROOT . $path)) echo HTML_PATH . $path;
                                                                  else echo HTML_PATH . "assets/images/placeholder.png" ?>" alt="<?php echo $usuari["nom_complet"] ?>">
              </div>
              <div class="meta">@<?php echo $usuari["username"] ?></div>
            </div>
            <div class="extra content">
              <span>
                <i class="star icon"></i>
                <?php
                  $count_favorites = Usuari::countObresFavorites($usuari["id_usuari"]);
                  echo $count_favorites . " " . ($count_favorites != 1 ? "favorites" : "favorita") ?>
              </span>
              <span class="right floated">
                <i class="bullseye icon"></i>
                <?php
                  $count_aparicions = Usuari::countAparicions($usuari["id_usuari"]);
                  echo $count_aparicions . " " . ($count_aparicions != 1 ? "aparicions trobades" : "aparició trobada") ?>
              </span>
            </div>
          </a>
        <?php endforeach ?>
      </div>
    </main>

    <?php require_once ROOT . "assets/php/incs/bottom.php" ?>
</body>

</html>