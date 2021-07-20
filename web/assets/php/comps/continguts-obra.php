<?php if ($count_moviments) :
    $capitols = json_decode(Obra::movimentsObra($id_obra), true);

    foreach ($capitols as $capitol) :
        $seccions = $capitol["seccions"] ?>
        <?php if ($capitol["titol_capitol"]) : ?>
            <h3 class="ui header"><?php echo $capitol["titol_capitol"] ?></h3>
        <?php endif ?>

        <?php foreach ($seccions as $seccio) :
                    $moviments = $seccio["moviments"] ?>
            <?php if ($seccio["titol_seccio"]) : ?>
                <h4 class="ui header"><?php echo $seccio["titol_seccio"] ?></h4>
            <?php endif ?>
            <div class="ui styled fluid accordion">

                <?php foreach ($moviments as $moviment) :
                                $submoviments = $moviment["submoviments"] ?>
                    <div class="title">
                        <i class="dropdown icon"></i>
                        <?php
                                        echo $moviment["num_moviment"] ? $moviment["num_moviment"] . ".&ensp;" : "";
                                        echo $moviment["titol_moviment"] ? $moviment["titol_moviment"] : ($seccio["titol_seccio"] ? $seccio["titol_seccio"] : ($capitol["titol_capitol"] ? $capitol["titol_capitol"] : "Moviment sense títol")) ?>
                    </div>
                    <div class="content">
                        <div class="ui grid">
                            <div class="twelve wide column">
                                <?php echo $moviment["subtitol_moviment"] ?>
                                <?php if ($moviment["llibret"]) : ?>
                                    <h5>Llibret</h5>
                                    <p style="white-space:pre-line"><?php echo $moviment["llibret"] ?></p>
                                <?php endif ?>
                                <?php if ($moviment["num_compassos"]) : ?>
                                    <span class="ui basic grey label"><?php echo $moviment["num_compassos"] ?> compassos</span>
                                <?php endif ?>
                                <?php if ($moviment["num_aparicions"]) : ?>
                                    <span class="ui basic yellow label"><?php echo $moviment["num_aparicions"] ?> aparicions</span>
                                <?php endif ?>
                                <?php if ($submoviments) : ?>
                                    <div class="accordion" style="margin-top:0">
                                        <?php foreach ($submoviments as $submoviment) : ?>
                                            <div class="title">
                                                <i class="dropdown icon"></i>
                                                <?php
                                                                        $num_submoviment = int_to_roman($submoviment["num_submoviment"]);
                                                                        if ($submoviment["titol_submoviment"]) {
                                                                            echo $num_submoviment . ".&ensp;" .  $submoviment["titol_submoviment"];
                                                                        } else {
                                                                            echo $moviment["titol_moviment"] . " " . $num_submoviment;
                                                                        } ?>
                                            </div>
                                            <div class="content">&nbsp;</div>
                                        <?php endforeach ?>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="four wide right aligned column">
                                <button class="ui button aparicio" onclick='
                                $("#id_moviment").val(<?php echo $moviment["id_moviment"] ?>)
                                <?php if ($moviment["num_compassos"]) : ?>
                                $("#compas_inici, #compas_final").attr({ "max": <?php echo $moviment["num_compassos"] ?> })
                                <?php endif ?>
                                '>
                                    <i class="ui bullseye icon"></i>
                                    Afegir aparició
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
    <?php endforeach;
        endforeach ?>
<?php else : ?>
    <div class="ui basic segment" style="padding-top:0">
        Sense continguts.
    </div>
<?php endif ?>