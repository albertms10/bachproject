<?php
require_once "connexionPDO.php";
class Aparicio
{
    public static function countAparicions()
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
      SELECT COUNT(*)
      FROM bp_aparicions
    ");
        $result->execute();
        $connexion = null;
        return $result->fetchColumn();
    }

    public static function percentAparicions()
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
      SELECT IFNULL(
        (
          SELECT COUNT(*)
          FROM bp_aparicions
        ) / (
          SELECT COUNT(*)
          FROM bp_obres
        ), 0) AS percent
    ");
        $result->execute();
        $connexion = null;
        return $result->fetchColumn();
    }

    public static function llistaActivitat($id_usuari = null)
    {
        $u = $id_usuari ? " AND bp_usuaris_protected.id_usuari = :i" : "";
        $connexion = new Connexion();
        $result = $connexion->prepare("
      SELECT * FROM (
        (
        SELECT bp_usuaris_protected.id_usuari, nom_usuari, bp_obres.id_obra, titol_obra, num_obra, bp_subgeneres.id_subgenere, titol_genere, titol_subgenere, bp_generes.id_genere, timestamp, inicials_cataleg, cataleg_complet, 'apa' AS tipus, bp_compositors.id_compositor AS id_comp
        FROM bp_aparicions
          INNER JOIN bp_aparicions_usuaris ON bp_aparicions_usuaris.id_aparicio = bp_aparicions.id_aparicio
          INNER JOIN bp_moviments ON bp_aparicions.id_moviment = bp_moviments.id_moviment
          INNER JOIN bp_obres ON bp_moviments.id_obra = bp_obres.id_obra
          LEFT JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
          LEFT JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
          LEFT JOIN bp_compositors ON bp_catalegs_compositors.id_compositor = bp_compositors.id_compositor
          LEFT JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
          LEFT JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
          LEFT JOIN bp_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
          LEFT JOIN bp_generes ON bp_subgeneres.id_genere = bp_generes.id_genere
          INNER JOIN bp_usuaris_protected ON bp_aparicions_usuaris.id_usuari = bp_usuaris_protected.id_usuari
        WHERE bp_catalegs.id_cataleg IN (
            SELECT bp_catalegs.id_cataleg
            FROM bp_catalegs
              INNER JOIN bp_catalegs_compositors ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
            WHERE id_compositor = 1
              AND alternative = 0
          )$u
        )
      UNION
        (
        SELECT bp_usuaris_protected.id_usuari, nom_usuari, bp_obres.id_obra, titol_obra, num_obra, bp_subgeneres.id_subgenere, titol_genere, titol_subgenere, bp_generes.id_genere, timestamp, inicials_cataleg, cataleg_complet, 'fav' AS tipus, bp_compositors.id_compositor AS id_comp
        FROM bp_obres_favorites
          INNER JOIN bp_obres ON bp_obres_favorites.id_obra = bp_obres.id_obra
          LEFT JOIN bp_obres_catalegs ON bp_obres_catalegs.id_obra = bp_obres.id_obra
          LEFT JOIN bp_catalegs_compositors ON bp_obres_catalegs.id_cataleg_compositor = bp_catalegs_compositors.id_cataleg_compositor
          LEFT JOIN bp_compositors ON bp_catalegs_compositors.id_compositor = bp_compositors.id_compositor
          LEFT JOIN bp_catalegs ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
          LEFT JOIN bp_obres_subgeneres ON bp_obres_subgeneres.id_obra = bp_obres.id_obra
          LEFT JOIN bp_subgeneres ON bp_obres_subgeneres.id_subgenere = bp_subgeneres.id_subgenere
          LEFT JOIN bp_generes ON bp_subgeneres.id_genere = bp_generes.id_genere
          INNER JOIN bp_usuaris_protected ON bp_obres_favorites.id_usuari = bp_usuaris_protected.id_usuari
        WHERE bp_catalegs.id_cataleg IN (
            SELECT bp_catalegs.id_cataleg
            FROM bp_catalegs
              INNER JOIN bp_catalegs_compositors ON bp_catalegs_compositors.id_cataleg = bp_catalegs.id_cataleg
            WHERE id_compositor = 1
              AND alternative = 0
          )$u
        )
      ) t
      ORDER BY timestamp DESC
      LIMIT 5;
    ");
        $params = $id_usuari ? [":i" => $id_usuari] : [];
        $result->execute($params);
        $connexion = null;
        return $result->fetchAll();
    }

    public static function mostraNomTema($id_tema, $transposicio = 0)
    {
        $c = new Connexion();
        if ($transposicio == 0) {
            $q = "SELECT id_semito, cromatica_de
        FROM bp_temes_cromatiques
        INNER JOIN bp_temes ON bp_temes_cromatiques.id_tema = bp_temes.id_tema
        INNER JOIN bp_t_cromatiques ON bp_temes_cromatiques.id_cromatica = bp_t_cromatiques.id_cromatica
      WHERE bp_temes.id_tema = :i
      ORDER BY ordre";
            try {
                return $c->query($q, Connexion::FETCH_ALL, [":i" => $id_tema]);
            } finally {
                $c = null;
            }
        } else {
            $q = "SELECT id_semito, ANY_VALUE(cromatica_de) AS cromatica_de
      FROM bp_t_cromatiques
      WHERE id_semito IN (
        SELECT CASE WHEN MOD(id_semito + :t, 12) <= 0 THEN MOD(id_semito + :t, 12) + 12 ELSE MOD(id_semito + :t, 12) END
        FROM bp_temes_cromatiques
        INNER JOIN bp_temes ON bp_temes_cromatiques.id_tema = bp_temes.id_tema
        INNER JOIN bp_t_cromatiques ON bp_temes_cromatiques.id_cromatica = bp_t_cromatiques.id_cromatica
        WHERE bp_temes.id_tema = :i
      )
      GROUP BY id_semito
      ORDER BY NULL;";
            try {
                return $c->query($q, Connexion::FETCH_ALL, [":i" => $id_tema, ":t" => $transposicio]);
            } finally {
                $c = null;
            }
        }
    }

    public static function insertAparicio(
        $temps_inici,
        $compas_inici,
        $temps_final,
        $compas_final,
        $veu,
        $transposicio,
        $tipus,
        $comentaris,
        $id_obra,
        $id_moviment,
        $id_usuari
    ) {
        $temps_inici = $temps_inici ? $temps_inici : null;
        $compas_inici = $compas_inici ? $compas_inici : null;
        $temps_final = $temps_final ? $temps_final : null;
        $compas_final = $compas_final ? $compas_final : null;
        $comentaris = $comentaris ? $comentaris : null;
        $veu = $veu ? $veu : null;

        $c = new Connexion();
        $q = "INSERT INTO bp_aparicions (temps_inici, compas_inici, temps_final, compas_final, veu, transposicio, tipus, comentaris, id_obra, id_moviment)
    VALUES (:ti, :ci, :tf, :cf, :v, :tp, :t, :c, :o, :m)";
        $c->query($q, Connexion::FETCH, [
            ":ti" => $temps_inici,
            ":ci" => $compas_inici,
            ":tf" => $temps_final,
            ":cf" => $compas_final,
            ":v" => $veu,
            ":tp" => $transposicio,
            ":t" => $tipus,
            ":c" => $comentaris,
            ":o" => $id_obra,
            ":m" => $id_moviment
        ]);

        $q = "INSERT INTO bp_aparicions_usuaris (id_aparicio, id_usuari)
    VALUES (:a, :u)";
        $c->query($q, Connexion::FETCH, [
            ":a" => $c->lastInsertId(),
            ":u" => $id_usuari
        ]);

        try {
            return $c;
        } finally {
            $c = null;
        }
    }
}
