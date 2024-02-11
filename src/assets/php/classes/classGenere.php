<?php
require_once "connexionPDO.php";
class Genere
{
    public static function llistaGeneres($id_compositor)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
      SELECT *
      FROM bp_generes
      WHERE id_compositor = :i;
    ");
        $result->execute([":i" => $id_compositor]);
        $connexion = null;
        return $result->fetchAll();
    }

    public static function llistaSubgeneres($id_genere)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
      SELECT bp_generes.*, bp_subgeneres.*, bp_subgeneres.id_subgenere AS id_s, COUNT(bp_obres.id_obra) AS count_obres
      FROM bp_generes
        INNER JOIN bp_subgeneres ON bp_subgeneres.id_genere = bp_generes.id_genere
        LEFT JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
        LEFT JOIN bp_obres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
        LEFT JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
      WHERE bp_generes.id_genere = :i
        AND prefix_cataleg IS NULL
        AND sufix_cataleg IS NULL
      GROUP BY bp_generes.id_genere, bp_subgeneres.id_subgenere;
    ");
        $result->execute([":i" => $id_genere]);
        $connexion = null;
        return $result->fetchAll();
    }

    public static function llistaVolumsGenere($id_genere)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
      SELECT bp_llibres.*, bp_volums.*, COUNT(bp_obres.id_obra) AS count_obres
      FROM bp_volums
        INNER JOIN bp_llibres ON bp_volums.id_llibre = bp_llibres.id_llibre
        INNER JOIN bp_obres_volums ON bp_obres_volums.id_volum = bp_volums.id_volum
        INNER JOIN bp_obres ON bp_obres_volums.id_obra = bp_obres.id_obra
        LEFT JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
        LEFT JOIN bp_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
        LEFT JOIN bp_generes ON bp_subgeneres.id_genere = bp_generes.id_genere
        INNER JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
      WHERE bp_generes.id_genere = :i
        AND prefix_cataleg IS NULL
        AND sufix_cataleg IS NULL
      GROUP BY bp_volums.id_volum;
    ");
        $result->execute([":i" => $id_genere]);
        $connexion = null;
        return $result->fetchAll();
    }

    public static function infoSubgenere($id_subgenere)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
        SELECT *, (
            SELECT JSON_ARRAYAGG(num_cataleg)
            FROM (
            SELECT DISTINCT num_cataleg
            FROM bp_obres
                INNER JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
                INNER JOIN bp_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
                INNER JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
                INNER JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
                INNER JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
            WHERE bp_subgeneres.id_subgenere = :i
                AND bp_catalegs_compositors.alternative = 0
            ) t
        ) AS llista_num_cataleg
        FROM bp_subgeneres
        WHERE bp_subgeneres.id_subgenere = :i;
        AND bp_catalegs_compositors.alternative = 0
      ");
        $result->execute([":i" => $id_subgenere]);
        $connexion = null;
        return $result->fetch();
    }

    public static function mostraSubgeneresObra($id_obra)
    {
        $c = new Connexion();
        $q = "SELECT *
    FROM bp_subgeneres
      INNER JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
    WHERE id_obra = :i;";

        try {
            return $c->query($q, Connexion::FETCH_ALL, [":i" => $id_obra]);
        } finally {
            $c = null;
        }
    }
}
