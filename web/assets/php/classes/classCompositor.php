<?php
require_once "connexionPDO.php";
class Compositor
{
    public static function llistaCompositors()
    {
        $c = new Connexion();
        $q = "SELECT *,
      CONCAT(YEAR(naixement), '–', YEAR(defuncio)) AS anys,
      FLOOR(DATEDIFF(defuncio, naixement) / 365) AS edat
    FROM bp_compositors
    ORDER BY naixement;";

        return $c->query($q, Connexion::FETCH_ALL);
    }

    public static function countObresCompositor($id_compositor)
    {
        $c = new Connexion();
        $q = "SELECT COUNT(*)
    FROM bp_obres
      INNER JOIN bp_obres_compositors ON bp_obres_compositors.id_obra = bp_obres.id_obra
      INNER JOIN bp_compositors ON bp_obres_compositors.id_compositor = bp_compositors.id_compositor
    WHERE bp_compositors.id_compositor = :i;";

        try {
            return $c->query($q, Connexion::FETCH_COLUMN, [":i" => $id_compositor]);
        } finally {
            $c = null;
        }
    }

    public static function infoCompositor($id_compositor, $show_counts = null)
    {
        $ext = $show_counts == 1 ? ", (
      SELECT COUNT(*)
      FROM bp_obres
        INNER JOIN bp_obres_compositors ON bp_obres_compositors.id_obra = bp_obres.id_obra
        INNER JOIN bp_compositors ON bp_obres_compositors.id_compositor = bp_compositors.id_compositor
      WHERE bp_compositors.id_compositor = :i
        AND id_tipus_relacio_compositor = 1
    ) AS obres_genuines, (
      SELECT COUNT(*)
      FROM bp_obres
        INNER JOIN bp_obres_compositors ON bp_obres_compositors.id_obra = bp_obres.id_obra
        INNER JOIN bp_compositors ON bp_obres_compositors.id_compositor = bp_compositors.id_compositor
      WHERE bp_compositors.id_compositor = :i
        AND id_tipus_relacio_compositor = 2
    ) AS obres_dubtoses, (
      SELECT COUNT(*)
      FROM bp_obres
        INNER JOIN bp_obres_compositors ON bp_obres_compositors.id_obra = bp_obres.id_obra
        INNER JOIN bp_compositors ON bp_obres_compositors.id_compositor = bp_compositors.id_compositor
      WHERE bp_compositors.id_compositor = :i
        AND id_tipus_relacio_compositor = 3
    ) AS obres_falses" : "";
        $c = new Connexion();
        $q = "SELECT *$ext, (
      SELECT IFNULL(MIN(bp_generes.id_genere), 'a')
      FROM bp_generes
      WHERE id_compositor = :i
    ) AS primer_genere
    FROM bp_compositors
    WHERE bp_compositors.id_compositor = :i;";

        try {
            return $c->query($q, Connexion::FETCH, [":i" => $id_compositor]);
        } finally {
            $c = null;
        }
    }

    public static function countObresAnysCompositor($id_compositor)
    {
        $c = new Connexion();
        $q = "SELECT any_inici, COUNT(*) AS num_obres
      FROM bp_obres
        INNER JOIN bp_obres_compositors ON bp_obres_compositors.id_obra = bp_obres.id_obra
        INNER JOIN bp_compositors ON bp_obres_compositors.id_compositor = bp_compositors.id_compositor
        INNER JOIN bp_obres_anys ON bp_obres_anys.id_obra = bp_obres.id_obra
      WHERE bp_compositors.id_compositor = :i
        AND any_inici IS NOT NULL
      GROUP BY any_inici;";

        try {
            return $c->query($q, Connexion::FETCH_ALL, [":i" => $id_compositor]);
        } finally {
            $c = null;
        }
    }

    public static function llistaVolumsCompositor($id_compositor)
    {
        $c = new Connexion();
        $q = "SELECT bp_llibres.*, bp_volums.*, ANY_VALUE(bp_generes.id_genere) AS id_genere
    FROM bp_volums
      INNER JOIN bp_llibres ON bp_llibres.id_llibre = bp_volums.id_llibre
      INNER JOIN bp_obres_volums ON bp_obres_volums.id_volum = bp_volums.id_volum
      INNER JOIN bp_obres ON bp_obres.id_obra = bp_obres_volums.id_obra
      INNER JOIN bp_obres_compositors ON bp_obres_compositors.id_obra = bp_obres.id_obra
      INNER JOIN bp_compositors ON bp_compositors.id_compositor = bp_obres_compositors.id_compositor
      INNER JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
      INNER JOIN bp_subgeneres ON bp_subgeneres.id_subgenere = bp_obres_subgeneres.id_subgenere
      INNER JOIN bp_generes ON bp_generes.id_genere = bp_subgeneres.id_genere
    WHERE bp_compositors.id_compositor = :i
    GROUP BY bp_volums.id_volum;";

        try {
            return $c->query($q, Connexion::FETCH_ALL, [":i" => $id_compositor]);
        } finally {
            $c = null;
        }
    }

    public static function anysCompositors()
    {
        $c = new Connexion();
        $q = "SELECT id_compositor, CONCAT(nom_compositor, ' ', cognom_compositor) AS nom_complet, naixement, defuncio,
        JSON_ARRAYAGG(
          JSON_OBJECT(
            'any_naixement', YEAR(naixement), 
            'any_defuncio', YEAR(defuncio)
          )
        ) AS anys
        FROM bp_compositors
        GROUP BY id_compositor
        ORDER BY naixement;";

        try {
            return $c->query($q, Connexion::FETCH_ALL);
        } finally {
            $c = null;
        }
    }
}
