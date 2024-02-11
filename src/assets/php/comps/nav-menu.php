<div class="ui light top borderless fixed menu">
  <div class="ui container">
    <a href="" class="item<?php
                            echo BASENAME == "bachproject"
                              ? " active" : ""
                            ?>" style="font-weight:bold">Bach’s Name Project</a>
    <a href="compositors/" class="item<?php
                                      echo BASENAME == "compositors" ||
                                        BASENAME_1 == "compositors" ||
                                        BASENAME_2 == "compositors"
                                        ? " active" : ""
                                      ?>">Compositors</a>
    <a href="obres/" class="item<?php
                                echo BASENAME == "obres" ||
                                  BASENAME_1 == "obres" ||
                                  BASENAME_2 == "obres"
                                  ? " active" : ""
                                ?>">Obres</a>
    <a href="usuaris/" class="item<?php
                                echo BASENAME == "usuaris" ||
                                  BASENAME_1 == "usuaris" ||
                                  BASENAME_2 == "usuaris"
                                  ? " active" : ""
                                ?>">Usuaris</a>
    <div class="right menu">
      <div class="item">
        <div class="ui transparent icon category search input">
          <input type="text" class="prompt" placeholder="Cerca…">
          <i class="search link icon"></i>
          <div class="results" style="max-height:82vh"></div>
        </div>
      </div>
      <?php if (!isset($_SESSION["id"])) : ?>
        <a href="sign-in/" class="item">Inicia sessió</a>
      <?php else : ?>
        <div class="ui dropdown <?php
                                  echo BASENAME == "usuaris" ||
                                    BASENAME_1 == "usuaris" ||
                                    BASENAME_2 == "usuaris"
                                    ? "active " : "" ?>icon item">
          <?php if (file_exists(ROOT . "assets/images/users/" . $_SESSION["id"] . ".jpg")) : ?>
            <img src="assets/images/users/<?php echo $_SESSION["id"] ?>.jpg" style="margin-right:.5rem"></i>
          <?php else : ?>
            <i class="user circle icon" style="margin-right:.5rem"></i>
          <?php endif ?>
          <?php echo $_SESSION["nom_complet"] ?>
          <div class="menu">
            <a href="usuaris/perfil/?id=<?php echo $_SESSION["id"] ?>" class="item">Perfil</a>
            <a href="sign-out/" class="item">Sortir</a>
          </div>
        </div>
      <?php endif ?>
    </div>
  </div>
</div>