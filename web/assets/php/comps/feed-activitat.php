<?php foreach ($activitats as $activitat) : ?>
    <div class="event">
        <div class="label">
            <i class="<?php echo $activitat["tipus"] == "apa" ? "bullseye" : ($activitat["tipus"] == "fav" ? "star" : "") ?> icon"></i>
        </div>
        <div class="content" style="margin-top:0">
            <div class="summary">
                <?php if (isset($_SESSION["id"])) :
                        if ($activitat["id_usuari"] == $_SESSION["id"]) : ?>
                        Has
                    <?php endif;
                        else : ?>
                    <a href="usuaris/perfil/?id=<?php echo $activitat["id_usuari"] ?>"><?php echo $activitat["nom_usuari"] ?></a> ha
                <?php endif ?>
                <?php if ($activitat["tipus"] == "apa") : ?>
                    marcat una aparició a
                <?php elseif ($activitat["tipus"] == "fav") : ?>
                    marcat com a favorita
                <?php endif ?>
                <em><?php echo $activitat["titol_obra"] ?></em>
                <span style="margin-left:.2rem">
                    <?php
                        $id_obra_p = $activitat["id_obra"];
                        $inicials_cataleg = $activitat["inicials_cataleg"];
                        $cataleg_complet = $activitat["cataleg_complet"];
                        $titol_obra = $activitat["titol_obra"];
                        $num_obra = $activitat["num_obra"];
                        $id_subgenere = $activitat["id_subgenere"];
                        $titol_subgenere = $activitat["titol_subgenere"];
                        $id_genere = $activitat["id_genere"];
                        $titol_genere = $activitat["titol_genere"];

                        include ROOT . "assets/php/comps/popup-obra.php" ?>
                </span>
            </div>
            <div class="date">
                <time datetime="<?php echo $activitat["timestamp"] ?>">fa <?php echo time_ago($activitat["timestamp"]) ?></time>
            </div>
        </div>
    </div>
<?php endforeach ?>