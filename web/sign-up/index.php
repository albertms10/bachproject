<!DOCTYPE html>
<html lang="ca">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Registra’t · Bach’s Name Project</title>

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
        Registra’t
        <div class="sub header">Bach’s Name Project</div>
      </h2>
      <form action="php/queries/nou-usuari.php" class="ui large form" method="post">
        <div class="ui stacked segment">
          <div class="two fields">
            <div class="field">
              <div class="ui left icon input">
                <i class="user icon"></i>
                <input type="text" name="nom_usuari" placeholder="Nom">
              </div>
            </div>
            <div class="field">
              <div class="ui left icon input">
                <i class="user icon"></i>
                <input type="text" name="cognom_usuari" placeholder="Cognom">
              </div>
            </div>
          </div>
          <div class="field">
            <div class="ui left icon input">
              <i class="at icon"></i>
              <input type="text" name="username" placeholder="Username">
            </div>
          </div>
          <div class="field">
            <div class="ui left icon input">
              <i class="lock icon"></i>
              <input type="password" name="contrasenya" placeholder="Contrasenya">
            </div>
          </div>
          <button type="submit" class="ui fluid large submit button">Registra’t</button>
        </div>

        <div class="ui error message"></div>

      </form>

      <div class="ui message">
        Ja tens un compte? <a href="sign-in/">Inicia sessió!</a>
      </div>
    </div>
  </main>

  <?php require_once ROOT . "assets/php/incs/bottom.php" ?>
  <script>
    $('.ui.form')
      .form({
        fields: {
          nom: {
            identifier: 'nom_usuari',
            rules: [{
              type: 'empty',
              prompt: 'Si us plau, introdueixi el nom com a mínim.'
            }]
          },
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