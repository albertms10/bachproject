<?php
require_once "connexionPDO.php";
class Cataleg
{
    public static function mostraCatalegsObra($id_obra)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
      SELECT *
      FROM bp_obres_catalegs
        INNER JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
        INNER JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
      WHERE bp_obres_catalegs.id_obra = :i;
    ");
        $result->execute([":i" => $id_obra]);
        $connexion = null;
        return $result->fetchAll();
    }
}
