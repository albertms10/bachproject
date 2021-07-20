<?php
require_once "connexionPDO.php";
class Obra
{
    public static function infoObra($id_obra)
    {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $connexion = new Connexion();
        if (isset($_SESSION["id"])) {
            $result = $connexion->prepare("
      SELECT bp_obres.*, bp_generes.*, bp_subgeneres.*, bp_atributs.*, tonalitat, (
        SELECT is_favorita
        FROM bp_obres_favorites
        WHERE id_obra = :i
          AND bp_obres_favorites.id_usuari = :u
      ) AS is_favorita
      FROM bp_obres
        LEFT JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
        LEFT JOIN bp_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
        LEFT JOIN bp_generes ON bp_subgeneres.id_genere = bp_generes.id_genere
        LEFT JOIN bp_atributs ON bp_obres_subgeneres.id_atribut = bp_atributs.id_atribut
        LEFT JOIN bp_tonalitats ON bp_obres.id_tonalitat = bp_tonalitats.id_tonalitat
      WHERE bp_obres.id_obra = :i
    ");
            $result->execute([":i" => $id_obra, ":u" => $_SESSION["id"]]);
        } else {
            $result = $connexion->prepare("
      SELECT bp_obres.*, bp_generes.*, bp_subgeneres.*, bp_atributs.*, tonalitat
      FROM bp_obres
        LEFT JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
        LEFT JOIN bp_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
        LEFT JOIN bp_generes ON bp_subgeneres.id_genere = bp_generes.id_genere
        LEFT JOIN bp_atributs ON bp_obres_subgeneres.id_atribut = bp_atributs.id_atribut
        LEFT JOIN bp_tonalitats ON bp_obres.id_tonalitat = bp_tonalitats.id_tonalitat
      WHERE bp_obres.id_obra = :i
    ");
            $result->execute([":i" => $id_obra]);
        }
        $connexion = null;
        return $result->fetch();
    }

    public static function marcarObra($id_obra)
    {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $connexion = new Connexion();
        $result = $connexion->prepare("
      SELECT is_favorita
      FROM bp_obres_favorites
      WHERE id_obra = :o
        AND id_usuari = :u;
    ");
        $result->execute([":o" => $id_obra, ":u" => $_SESSION["id"]]);

        if ($result->fetchColumn() == 1) {
            $result = $connexion->prepare("
        DELETE FROM bp_obres_favorites
        WHERE id_obra = :o
          AND id_usuari = :u;
      ");
            $result->execute([":o" => $id_obra, ":u" => $_SESSION["id"]]);
        } else {
            $result = $connexion->prepare("
        INSERT INTO bp_obres_favorites (id_obra, id_usuari, is_favorita)
        VALUES (:o, :u, 1);
      ");
            $result->execute([":o" => $id_obra, ":u" => $_SESSION["id"]]);
        }
        $connexion = null;
        return $result;
    }

    public static function compositorsObra($id_obra)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
      SELECT bp_compositors.*, bp_compositors.id_compositor AS id_comp, CONCAT(nom_compositor, ' ', cognom_compositor) AS nom_complet, bp_tipus_relacions_compositors.*, bp_tipus_autories.*, (
        SELECT JSON_ARRAYAGG(
            JSON_OBJECT(
                'id_genere', bp_generes.id_genere, 
                'titol_genere', titol_genere, 
                'id_subgenere', bp_subgeneres.id_subgenere, 
                'titol_subgenere', titol_subgenere, 
                'titol_atribut', titol_atribut
            )
        )
        FROM bp_subgeneres
          INNER JOIN bp_generes ON bp_subgeneres.id_genere = bp_generes.id_genere
          INNER JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
          LEFT JOIN bp_atributs ON bp_atributs.id_atribut = bp_obres_subgeneres.id_atribut
        WHERE id_obra = :i
        AND bp_generes.id_compositor = id_comp
      ) AS subgeneres_obra, (
        SELECT JSON_ARRAYAGG(
            JSON_OBJECT(
                'alternative', alternative,
                'id_cataleg', bp_catalegs.id_cataleg, 
                'inicials_cataleg', inicials_cataleg, 
                'cataleg_complet', cataleg_complet
            )
        )
        FROM bp_obres_catalegs
            INNER JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
            INNER JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
        WHERE bp_obres_catalegs.id_obra = :i
          AND bp_catalegs_compositors.id_compositor = id_comp
        ORDER BY alternative DESC
      ) AS catalegs_obra
      FROM bp_compositors
        INNER JOIN bp_obres_compositors ON bp_obres_compositors.id_compositor = bp_compositors.id_compositor
        INNER JOIN bp_tipus_relacions_compositors ON bp_obres_compositors.id_tipus_relacio_compositor = bp_tipus_relacions_compositors.id_tipus_relacio_compositor
        INNER JOIN bp_tipus_autories ON bp_obres_compositors.id_tipus_autoria = bp_tipus_autories.id_tipus_autoria
        INNER JOIN bp_obres ON bp_obres_compositors.id_obra = bp_obres.id_obra
      WHERE bp_obres.id_obra = :i;
    ");
        $result->execute([":i" => $id_obra]);
        $connexion = null;
        return $result->fetchAll();
    }

    public static function searchObres($s)
    {
        $c = new Connexion();
        $q = "SELECT IFNULL(JSON_OBJECTAGG(id_subgenere, results), '[]') AS results
      FROM (
        SELECT bp_subgeneres.id_subgenere, JSON_OBJECT(
          'name', CONCAT(LEFT(titol_subgenere, 20), IFNULL(CASE WHEN CHAR_LENGTH(titol_subgenere) > 20 THEN '…' END, '')),
          'results', JSON_ARRAYAGG(
            JSON_OBJECT('title', titol_obra, 
            'description', CONCAT(inicials_cataleg, ' ', cataleg_complet),
            'url', CONCAT('obres/obra?id=', bp_obres.id_obra))
          )
        ) AS results
        FROM bp_obres
          LEFT JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
          LEFT JOIN bp_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere      
          LEFT JOIN bp_generes ON bp_subgeneres.id_genere = bp_generes.id_genere
          LEFT JOIN bp_atributs ON bp_obres_subgeneres.id_atribut = bp_atributs.id_atribut
          LEFT JOIN bp_tonalitats ON bp_obres.id_tonalitat = bp_tonalitats.id_tonalitat
          LEFT JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
          LEFT JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
          LEFT JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
          WHERE bp_catalegs_compositors.alternative = 0
          AND (titol_obra LIKE '%$s%'
               OR titol_alt LIKE '%$s%'
               OR CONCAT(inicials_cataleg, ' ', cataleg_complet) LIKE '%$s%'
               OR notes LIKE '%$s%'
              )
        GROUP BY bp_subgeneres.id_subgenere
      ) t;";

        try {
            return $c->query($q, Connexion::FETCH);
        } finally {
            $c = null;
        }
    }

    public static function dropdownObres($s)
    {
        $c = new Connexion();
        $q = "SELECT JSON_OBJECT('results', JSON_ARRAYAGG(
            JSON_OBJECT('name', CONCAT(titol_obra, ', ', inicials_cataleg, ' ', cataleg_complet),
                'value', bp_obres.id_obra
            )
          )
        ) AS results
      FROM bp_obres
        LEFT JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
        LEFT JOIN bp_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
        LEFT JOIN bp_generes ON bp_subgeneres.id_genere = bp_generes.id_genere
        LEFT JOIN bp_atributs ON bp_obres_subgeneres.id_atribut = bp_atributs.id_atribut
        LEFT JOIN bp_tonalitats ON bp_obres.id_tonalitat = bp_tonalitats.id_tonalitat
        LEFT JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
        LEFT JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
        LEFT JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
      WHERE bp_catalegs_compositors.alternative = 0
        AND (titol_obra LIKE '%$s%'
             OR titol_alt LIKE '%$s%'
             OR CONCAT(inicials_cataleg, ' ', cataleg_complet) LIKE '%$s%'
             OR notes LIKE '%$s%'
            );";

        try {
            return $c->query($q, Connexion::FETCH);
        } finally {
            $c = null;
        }
    }

    public static function mostraAnysObra($id_obra)
    {
        $c = new Connexion();
        $q = "SELECT *
      FROM bp_obres_anys
      WHERE id_obra = :i;";

        try {
            return $c->query($q, Connexion::FETCH_ALL, [":i" => $id_obra]);
        } finally {
            $c = null;
        }
    }

    public static function countObres()
    {
        $c = new Connexion();
        $q = "SELECT COUNT(*)
      FROM bp_obres
        LEFT JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
        LEFT JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
        LEFT JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
      WHERE bp_catalegs_compositors.alternative = 0
        AND prefix_cataleg IS NULL
        AND sufix_cataleg IS NULL;";

        try {
            return $c->query($q, Connexion::FETCH_COLUMN);
        } finally {
            $c = null;
        }
    }

    public static function llistaObresSubgenere($id_subgenere)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
        SELECT bp_obres.*, inicials_cataleg, cataleg_complet, COUNT(bp_aparicions.id_aparicio) AS count_aparicions, tonalitat, bp_catalegs_compositors.id_compositor AS id_comp, bp_catalegs.id_cataleg
        FROM bp_obres
          LEFT JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
          LEFT JOIN bp_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
          LEFT JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
          LEFT JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
          LEFT JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
          LEFT JOIN bp_moviments ON bp_moviments.id_obra = bp_obres.id_obra
          LEFT JOIN bp_aparicions ON bp_aparicions.id_moviment = bp_moviments.id_moviment
          LEFT JOIN bp_tonalitats ON bp_obres.id_tonalitat = bp_tonalitats.id_tonalitat
          WHERE bp_subgeneres.id_subgenere = :i
            AND prefix_cataleg IS NULL
            AND sufix_cataleg IS NULL
        GROUP BY seccio, separador_seccio, prefix_cataleg, sufix_cataleg, separador_sufix, bp_obres_catalegs.num_cataleg, bp_obres.id_obra, inicials_cataleg, cataleg_complet, bp_catalegs_compositors.id_compositor, bp_catalegs.id_cataleg
        HAVING bp_catalegs.id_cataleg IN (
            SELECT id_cataleg
            FROM bp_catalegs_compositors
            WHERE id_compositor = id_comp
        ) 
        ORDER BY bp_catalegs.id_cataleg, bp_obres_catalegs.num_cataleg, prefix_cataleg, sufix_cataleg;
        ");
        $result->execute([":i" => $id_subgenere]);
        $connexion = null;
        return $result->fetchAll();
    }

    public static function llistaObresVolum($id_volum)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
        SELECT bp_obres.*, inicials_cataleg, cataleg_complet, COUNT(bp_aparicions.id_aparicio) AS count_aparicions, tonalitat, bp_catalegs_compositors.id_compositor AS id_comp, bp_catalegs.id_cataleg
        FROM bp_obres
            LEFT JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
            LEFT JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
            LEFT JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
            LEFT JOIN bp_moviments ON bp_moviments.id_obra = bp_obres.id_obra
            LEFT JOIN bp_aparicions ON bp_aparicions.id_moviment = bp_moviments.id_moviment
            LEFT JOIN bp_tonalitats ON bp_obres.id_tonalitat = bp_tonalitats.id_tonalitat
            INNER JOIN bp_obres_volums ON bp_obres_volums.id_obra = bp_obres.id_obra
            INNER JOIN bp_volums ON bp_obres_volums.id_volum = bp_volums.id_volum
        WHERE bp_volums.id_volum = :i
            AND prefix_cataleg IS NULL
            AND sufix_cataleg IS NULL
        GROUP BY seccio, separador_seccio, prefix_cataleg, sufix_cataleg, separador_sufix, bp_obres_catalegs.num_cataleg, bp_obres.id_obra, inicials_cataleg, cataleg_complet, bp_catalegs_compositors.id_compositor, bp_catalegs.id_cataleg, num_obra_volum
        HAVING bp_catalegs.id_cataleg IN (
                SELECT id_cataleg
                FROM bp_catalegs_compositors
                WHERE id_compositor = id_comp
            ) 
        ORDER BY bp_catalegs.id_cataleg, num_obra_volum, bp_obres_catalegs.num_cataleg, prefix_cataleg, sufix_cataleg;
    ");
        $result->execute([":i" => $id_volum]);
        $connexion = null;
        return $result->fetchAll();
    }

    public static function llistaAltresObres($id_compositor)
    {
        $c = new Connexion();
        $q = "SELECT bp_obres.*, inicials_cataleg, cataleg_complet, COUNT(bp_aparicions.id_aparicio) AS count_aparicions, tonalitat
    FROM bp_obres
      LEFT JOIN bp_obres_compositors ON bp_obres_compositors.id_obra = bp_obres.id_obra
      LEFT JOIN bp_compositors ON bp_obres_compositors.id_compositor = bp_compositors.id_compositor
      LEFT JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
      LEFT JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
      LEFT JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
      LEFT JOIN bp_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
      LEFT JOIN bp_generes ON bp_subgeneres.id_genere = bp_generes.id_genere
      LEFT JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
      LEFT JOIN bp_moviments ON bp_moviments.id_obra = bp_obres.id_obra
      LEFT JOIN bp_aparicions ON bp_aparicions.id_moviment = bp_moviments.id_moviment
      LEFT JOIN bp_tonalitats ON bp_obres.id_tonalitat = bp_tonalitats.id_tonalitat
      LEFT JOIN bp_atributs ON bp_obres_subgeneres.id_atribut = bp_atributs.id_atribut
    WHERE bp_catalegs_compositors.alternative = 0
      AND prefix_cataleg IS NULL
      AND sufix_cataleg IS NULL
      AND bp_compositors.id_compositor = :i
      AND bp_obres.id_obra NOT IN (
        SELECT bp_obres.id_obra
        FROM bp_obres
          LEFT JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
          LEFT JOIN bp_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
          LEFT JOIN bp_generes ON bp_subgeneres.id_genere = bp_generes.id_genere
        WHERE bp_generes.id_compositor = :i
      )
    GROUP BY seccio, separador_seccio, prefix_cataleg, sufix_cataleg, separador_sufix, bp_obres_catalegs.num_cataleg, bp_obres.id_obra, inicials_cataleg
    ORDER BY bp_obres_catalegs.num_cataleg, prefix_cataleg, sufix_cataleg;";

        try {
            return $c->query($q, Connexion::FETCH_ALL, [":i" => $id_compositor]);
        } finally {
            $c = null;
        }
    }

    public static function countMovimentsObra($id_obra)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
      SELECT COUNT(*)
      FROM bp_moviments
      WHERE id_obra = :i
    ");
        $result->execute([":i" => $id_obra]);
        $connexion = null;
        return $result->fetchColumn();
    }

    public static function movimentsObra($id_obra)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
        SELECT JSON_ARRAYAGG(
            JSON_OBJECT(
              'id_capitol', id_capitol,
              'titol_capitol', titol_capitol,
              'seccions', seccions
            )
          ) AS capitols
          FROM (
            SELECT JSON_ARRAYAGG(
              JSON_OBJECT(
                'id_seccio', id_seccio,
                'titol_seccio', titol_seccio,
                'moviments', moviments
              )
            ) AS seccions,
              id_capitol, 
              titol_capitol
              FROM (
                SELECT
                  id_capitol,
                  id_seccio,
                  titol_capitol,
                  titol_seccio,
                  JSON_ARRAYAGG(
                    JSON_OBJECT(
                      'id_moviment', id_moviment,
                      'num_moviment', num_moviment,
                      'titol_moviment', titol_moviment,
                      'subtitol_moviment', subtitol_moviment,
                      'llibret', llibret,
                      'num_compassos', num_compassos,
                      'submoviments', submoviments
                    )
                  ) AS moviments
                FROM (
                  SELECT 
                    IFNULL(bp_capitols.id_capitol, 'C') AS id_capitol,
                    IFNULL(bp_seccions.id_seccio, 'S') AS id_seccio,
                    bp_moviments.id_moviment,
                    titol_capitol,
                    titol_seccio,
                    num_moviment,
                    llibret,
                    bp_moviments.num_compassos,
                    IFNULL(titol_moviment, titol_tipus_moviment) AS titol_moviment, subtitol_moviment,
                    IF(COUNT(bp_submoviments.id_submoviment) = 0, JSON_ARRAY(), JSON_ARRAYAGG(
                      JSON_OBJECT(
                        'id_submoviment', bp_submoviments.id_submoviment,
                        'titol_submoviment', titol_submoviment,
                        'num_submoviment', num_submoviment,
                        'num_compassos', bp_submoviments.num_compassos
                      )
                    ))
                     AS submoviments
                  FROM bp_moviments
                    INNER JOIN bp_obres ON bp_obres.id_obra = bp_moviments.id_obra
                    LEFT JOIN bp_tipus_moviments ON bp_tipus_moviments.id_tipus_moviment = bp_moviments.id_tipus_moviment
                    LEFT JOIN bp_submoviments ON bp_submoviments.id_moviment = bp_moviments.id_moviment
                    LEFT JOIN bp_seccions ON bp_seccions.id_seccio = bp_moviments.id_seccio
                    LEFT JOIN bp_capitols ON bp_capitols.id_capitol = bp_seccions.id_capitol
                    LEFT JOIN bp_aparicions ON bp_aparicions.id_moviment = bp_moviments.id_moviment
                  WHERE bp_obres.id_obra = :i
                  GROUP BY id_capitol, titol_capitol, id_seccio, bp_moviments.id_moviment
                ) m
                GROUP BY id_capitol, titol_capitol, id_seccio, titol_seccio
              ) s 
              GROUP BY id_capitol, titol_capitol
          ) c;
        ");
        $result->execute([":i" => $id_obra]);
        $connexion = null;
        return $result->fetchColumn();
    }

    public static function relacionsObra($id_obra, $direccionalitat)
    {
        $d = $direccionalitat == 1 ? 2 : 1;
        $connexion = new Connexion();
        $result = $connexion->prepare("
      SELECT *
      FROM bp_obres_relacions
        INNER JOIN bp_tipus_relacions_obres ON bp_obres_relacions.id_tipus_relacio_obra = bp_tipus_relacions_obres.id_tipus_relacio_obra
        INNER JOIN bp_obres ON bp_obres_relacions.id_obra_$d = bp_obres.id_obra
        INNER JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
        INNER JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
        INNER JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
        INNER JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
        INNER JOIN bp_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere       
        INNER JOIN bp_generes ON bp_subgeneres.id_genere = bp_generes.id_genere
      WHERE id_obra_$direccionalitat = :i
        AND bp_catalegs_compositors.alternative = 0
      ORDER BY num_cataleg;
    ");
        $result->execute([":i" => $id_obra]);
        $connexion = null;
        return $result->fetchAll();
    }

    public static function countObresAnys($id_compositor)
    {
        $c = new Connexion();
        $q = "SELECT COUNT(*)
    FROM (
      SELECT COUNT(*)
        FROM bp_obres
          INNER JOIN bp_obres_anys ON bp_obres_anys.id_obra = bp_obres.id_obra
          INNER JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
          INNER JOIN bp_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
          INNER JOIN bp_generes ON bp_subgeneres.id_genere = bp_generes.id_genere
          INNER JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
          INNER JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
          INNER JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
          INNER JOIN bp_obres_compositors ON bp_obres_compositors.id_obra = bp_obres.id_obra
          INNER JOIN bp_compositors ON bp_obres_compositors.id_compositor = bp_compositors.id_compositor
        WHERE bp_catalegs_compositors.alternative = 0
          AND any_inici IS NOT NULL
          AND bp_compositors.id_compositor = :i
          AND bp_obres_compositors.id_tipus_relacio_compositor = 1
        GROUP BY seccio, separador_seccio, prefix_cataleg, separador_sufix, sufix_cataleg, bp_obres_catalegs.num_cataleg, bp_obres.id_obra
      ) t";

        try {
            return $c->query($q, Connexion::FETCH_COLUMN, [":i" => $id_compositor]);
        } finally {
            $c = null;
        }
    }

    public static function llistaObresAnys($id_compositor, $limit)
    {
        $c = new Connexion();
        $q = "SELECT CONCAT(titol_obra, ', ', inicials_cataleg, ' ', cataleg_complet) AS titol_complet, bp_generes.id_genere,
        MIN(any_inici) AS any_inici,
        IFNULL(MAX(any_final), MAX(any_inici) + 1) AS any_final,
        JSON_ARRAYAGG(
          JSON_OBJECT(
            'any_inici', any_inici, 
            'any_final', IFNULL(any_final, any_inici + 1), 
            'is_revisio', is_revisio
          )
        ) AS anys
      FROM bp_obres
        INNER JOIN bp_obres_anys ON bp_obres_anys.id_obra = bp_obres.id_obra
        INNER JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
        INNER JOIN bp_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
        INNER JOIN bp_generes ON bp_subgeneres.id_genere = bp_generes.id_genere
        INNER JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
        INNER JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
        INNER JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
        INNER JOIN bp_obres_compositors ON bp_obres_compositors.id_obra = bp_obres.id_obra
        INNER JOIN bp_compositors ON bp_obres_compositors.id_compositor = bp_compositors.id_compositor
      WHERE bp_catalegs_compositors.alternative = 0
        AND any_inici IS NOT NULL
        AND bp_compositors.id_compositor = :i
        AND bp_obres_compositors.id_tipus_relacio_compositor = 1
        AND (is_revisio IS NULL OR is_revisio = 0)
      GROUP BY bp_generes.id_genere, seccio, separador_seccio, prefix_cataleg, separador_sufix, sufix_cataleg, bp_obres_catalegs.num_cataleg, bp_obres.id_obra
      ORDER BY any_inici
      LIMIT $limit, 30;";

        try {
            return $c->query($q, Connexion::FETCH_ALL, [":i" => $id_compositor]);
        } finally {
            $c = null;
        }
    }

    public static function llistaObresVariants($id_obra)
    {
        $c = new Connexion();
        $q = "SELECT bp_obres.*, inicials_cataleg, cataleg_complet, COUNT(bp_aparicions.id_aparicio) AS count_aparicions, tonalitat
      FROM bp_obres
        LEFT JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
        LEFT JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
        LEFT JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
        LEFT JOIN bp_moviments ON bp_moviments.id_obra = bp_obres.id_obra
        LEFT JOIN bp_aparicions ON bp_aparicions.id_moviment = bp_moviments.id_moviment
        LEFT JOIN bp_tonalitats ON bp_obres.id_tonalitat = bp_tonalitats.id_tonalitat
      WHERE bp_catalegs_compositors.alternative = 0
        AND bp_obres.id_obra <> :i
        AND num_cataleg = (
          SELECT num_cataleg
          FROM bp_obres
            INNER JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
            INNER JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
            INNER JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
          WHERE bp_obres.id_obra = :i
            AND bp_catalegs_compositors.alternative = 0
        )
      GROUP BY seccio, separador_seccio, prefix_cataleg, separador_sufix, sufix_cataleg, bp_obres_catalegs.num_cataleg, bp_obres.id_obra
      ORDER BY bp_obres_catalegs.num_cataleg, prefix_cataleg, sufix_cataleg;";

        try {
            return $c->query($q, Connexion::FETCH_ALL, [":i" => $id_obra]);
        } finally {
            $c = null;
        }
    }

    public static function insertRelacioObra($id_obra_1, $id_tipus_relacio_obra, $is_parcialment, $id_obra_2)
    {
        $is_parcialment = $is_parcialment == "true" ? 1 : 0;
        $c = new Connexion();
        $q = "INSERT INTO bp_obres_relacions (id_obra_1, id_tipus_relacio_obra, is_parcialment, id_obra_2)
      VALUES (:a, :r, :p, :b)";

        try {
            return $c->query($q, Connexion::FETCH, [
                ":a" => $id_obra_1,
                ":r" => $id_tipus_relacio_obra,
                ":p" => $is_parcialment,
                ":b" => $id_obra_2
            ]);
        } finally {
            $c = null;
        }
    }

    public static function llistaTipusRelacions()
    {
        $c = new Connexion();
        $q = "SELECT *
    FROM bp_tipus_relacions_obres";

        try {
            return $c->query($q, Connexion::FETCH_ALL);
        } finally {
            $c = null;
        }
    }

    public static function countAparicions($id_obra)
    {
        $c = new Connexion();
        $q = "SELECT COUNT(*)
    FROM bp_aparicions
      INNER JOIN bp_obres ON bp_aparicions.id_obra = bp_obres.id_obra
    WHERE bp_obres.id_obra = :i;";

        try {
            return $c->query($q, Connexion::FETCH_COLUMN, [":i" => $id_obra]);
        } finally {
            $c = null;
        }
    }

    public static function llistaAparicions($id_obra)
    {
        $c = new Connexion();
        $q = "SELECT *
    FROM bp_aparicions
      INNER JOIN bp_obres ON bp_aparicions.id_obra = bp_obres.id_obra
    WHERE bp_obres.id_obra = :i;";

        try {
            return $c->query($q, Connexion::FETCH_ALL, [":i" => $id_obra]);
        } finally {
            $c = null;
        }
    }

    public static function llistaVolumsObra($id_obra)
    {
        $c = new Connexion();
        $q = "SELECT *
    FROM bp_volums
      INNER JOIN bp_llibres ON bp_llibres.id_llibre = bp_volums.id_llibre
      INNER JOIN bp_obres_volums ON bp_obres_volums.id_volum = bp_volums.id_volum
      INNER JOIN bp_obres ON bp_obres.id_obra = bp_obres_volums.id_obra
    WHERE bp_obres.id_obra = :i;";

        try {
            return $c->query($q, Connexion::FETCH_ALL, [":i" => $id_obra]);
        } finally {
            $c = null;
        }
    }

    public static function llistaTipusMoviments()
    {
        $c = new Connexion();
        $q = "SELECT * FROM bp_tipus_moviments;";

        try {
            return $c->query($q, Connexion::FETCH_ALL);
        } finally {
            $c = null;
        }
    }

    public static function insertMoviment(
        $id_obra,
        $num_moviment,
        $titol_moviment,
        $subtitol_moviment,
        $llibret,
        $num_compassos,
        $id_tipus_moviment,
        $id_tonalitat
    ) {
        $num_moviment = $num_moviment ? $num_moviment : null;
        $titol_moviment = $titol_moviment ? $titol_moviment : null;
        $llibret = $llibret ? $llibret : null;
        $num_compassos = $num_compassos ? $num_compassos : null;
        $id_tipus_moviment = $id_tipus_moviment ? $id_tipus_moviment : null;
        $id_tonalitat = $id_tonalitat ? $id_tonalitat : null;

        $c = new Connexion();
        $q = "INSERT INTO bp_moviments (id_obra, num_moviment, titol_moviment, subtitol_moviment, llibret, num_compassos, id_tipus_moviment, id_tonalitat)
        VALUES (:o, :n, :ti, :s, :ll, :c, :tm, :to);";

        try {
            return $c->query($q, Connexion::FETCH, [
                ":o" => $id_obra,
                ":n" => $num_moviment,
                ":ti" => $titol_moviment,
                ":s" => $subtitol_moviment,
                ":ll" => $llibret,
                ":c" => $num_compassos,
                ":tm" => $id_tipus_moviment,
                ":to" => $id_tonalitat
            ]);
        } finally {
            $c = null;
        }
    }
}
