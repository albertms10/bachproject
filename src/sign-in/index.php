<!DOCTYPE html>
<html lang="ca">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Inicia sessió · Bach’s Name Project</title>

  <?php define("ROOT", $_SERVER["DOCUMENT_ROOT"] . "/bachproject/") ?>
  <?php require_once ROOT . "assets/php/incs/top.php" ?>

  <style type="text/css">
    body>.grid {
      height: 100%;
    }

    .image {
      margin-top: -100px;
    }

    .column {
      max-width: 450px;
    }
  </style>
</head>

<body>
  <main class="ui middle aligned center aligned grid">
    <div class="column">
      <h2 class="ui image header">
        Inicia sessió
        <div class="sub header">Bach’s Name Project</div>
      </h2>
      <form action="assets/php/queries/sign-in-access.php" class="ui large form" method="post">
        <div class="ui stacked segment">
          <div class="field">
            <div class="ui left icon input">
              <i class="user icon"></i>
              <input type="text" name="username" value="<?php echo isset($_GET["username"]) ? $_GET["username"] : "" ?>" placeholder="Nom d’usuari">
            </div>
          </div>
          <div class="field">
            <div class="ui left icon input">
              <i class="lock icon"></i>
              <input type="password" name="contrasenya" placeholder="Contrasenya">
            </div>
          </div>
          <button type="submit" class="ui fluid large submit button">Login</button>
        </div>

        <div class="ui error message"></div>

      </form>

      <div class="ui message">
        Ets un nou usuari? <a href="sign-up/">Registra’t!</a>
      </div>
    </div>
  </main>

  <?php require_once ROOT . "assets/php/incs/bottom.php" ?>
  <script>
    $('.ui.form')
      .form({
        fields: {
          username: {
            identifier: 'username',
            rules: [{
              type: 'empty',
              prompt: 'Si us plau, introdueixi un nom d’usuari (nickname).'
            }]
          },
          contrasenya: {
            identifier: 'contrasenya',
            rules: [{
                type: 'empty',
                prompt: 'Si us plau, introdueixi una contrasenya.'
              },
              {
                type: 'minLength[6]',
                prompt: 'La contrasenya ha de tenir un mínim de {ruleValue} caràcters.'
              }
            ]
          }
        }
      });
  </script>
</body>

</html>