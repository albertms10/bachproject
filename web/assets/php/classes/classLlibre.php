<?php
require_once "connexionPDO.php";
class Llibre
{
    public static function infoVolum($id_volum)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
      SELECT *, (
        SELECT JSON_ARRAYAGG(num_cataleg)
        FROM (
          SELECT DISTINCT num_cataleg
          FROM bp_obres
            INNER JOIN bp_obres_volums ON bp_obres_volums.id_obra = bp_obres.id_obra
            INNER JOIN bp_volums ON bp_obres_volums.id_volum = bp_volums.id_volum
            INNER JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
            INNER JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
            INNER JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
          WHERE bp_volums.id_volum = :i
            AND bp_catalegs_compositors.alternative = 0
        ) t
      ) AS llista_num_cataleg
      FROM bp_volums
        INNER JOIN bp_llibres ON bp_volums.id_llibre = bp_llibres.id_llibre
      WHERE id_volum = :i;
    ");
        $result->execute([":i" => $id_volum]);
        $connexion = null;
        return $result->fetch();
    }
}
