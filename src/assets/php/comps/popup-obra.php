<a href="<?php echo HTML_PATH ?>obres/obra/?id=<?php echo $id_obra_p ?>" class="ui label popup-hover"><?php echo $inicials_cataleg ?><span class="detail"><?php echo $cataleg_complet ?></span></a>
<div class="ui flowing popup">
    <div class="ui header" style="margin-bottom:.5em"><?php echo $titol_obra ?>
        <?php echo $num_obra ? "núm. " . $num_obra : "" ?>
        <div class="sub header">
            <div class="ui horizontal list">
                <?php
                $anys = Obra::mostraAnysObra($id_obra_p);
                foreach ($anys as $any) : ?>
                    <div class="item">
                        <?php
                            echo $any["is_revisio"] ? "<sc>rev.</sc>" : "";
                            echo $any["is_circa"] ? "<sc>ca.</sc>" : "";
                            if ($any["any_inici"]) {
                                echo $any["any_inici"];
                                if ($any["any_final"])
                                    echo "–" . $any["any_final"];
                            }
                            echo $any["is_dubtos"] ? '<div class="ui mini circular label" style="text-transform:auto; margin-top:-1px; position:relative; bottom:1px">?</div>' : "";
                            ?>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <a href="<?php echo HTML_PATH ?>obres/?subgenere=<?php echo $id_subgenere ?>" class="ui image <?php echo Colors::$colors[$id_genere - 1] ?> label">
        <?php echo $titol_genere ?>
        <div class="detail"><?php echo $titol_subgenere ?></div>
    </a>
</div>