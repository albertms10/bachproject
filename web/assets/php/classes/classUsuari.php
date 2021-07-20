<?php
require_once "connexionPDO.php";
class Usuari
{
    private $nom;
    private $cognom;
    private $username;
    private $contrasenya;
    private $id;

    public function __construct(
        $nom = null,
        $cognom = null,
        $username = null,
        $contrasenya = null,
        $id = null
    )
    {
        $this->nom = $nom;
        $this->cognom = $cognom;
        $this->username = $username;
        $this->contrasenya = $contrasenya;
        $this->id = $id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function newUser()
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
      INSERT INTO bp_usuaris_protected (nom_usuari, cognom_usuari, username, contrasenya)
      VALUES (:n, :c, :u, :p);
    ");
        $total = $result->execute([":n" => $this->nom, ":c" => $this->cognom, ":u" => $this->username, ":p" => sha1($this->contrasenya)]);

        $connexion = null;
        if ($total) return 6;
        else return 0;
    }

    public static function checkLogin($username, $contrasenya)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
      SELECT COUNT(*)
      FROM bp_usuaris_protected
      WHERE username = :u
        AND contrasenya = :c;
    ");
        $result->execute([":u" => $username, ":c" => sha1($contrasenya)]);
        $total = $result->fetchColumn();

        if ($total == 1) {
            $result = $connexion->prepare("
        SELECT *
        FROM bp_usuaris_protected
        WHERE username = :u
          AND contrasenya = :c;
      ");
            $result->execute([":u" => $username, ":c" => sha1($contrasenya)]);
            $connexion = null;

            if ($result) {
                session_start();
                $user = $result->fetchObject();
                $_SESSION["nom_complet"] = $user->nom_usuari . " " . $user->cognom_usuari;
                $_SESSION["username"] = $user->username;
                $_SESSION["id"] = $user->id_usuari;
                return 1;
            } else {
                return 3;
            }
        } else {
            $connexion = null;
            return 2;
        }
    }

    public static function llistaUsuaris()
    {
        $c = new Connexion();
        $q = "SELECT *
    FROM bp_usuaris";

        try {
            return $c->query($q, Connexion::FETCH_ALL);
        } finally {
            $c = null;
        }
    }

    public static function infoUsuari($id_usuari)
    {
        $c = new Connexion();
        $q = "SELECT *, (
      SELECT COUNT(*)
      FROM bp_obres_favorites 
      WHERE bp_obres_favorites.id_usuari = :i
    ) AS count_favorites, (
      SELECT COUNT(*)
      FROM bp_aparicions_usuaris
      WHERE bp_aparicions_usuaris.id_usuari = :i
    ) AS count_aparicions
    FROM bp_usuaris
    WHERE bp_usuaris.id_usuari = :i";

        try {
            return $c->query($q, Connexion::FETCH, [":i" => $id_usuari]);
        } finally {
            $c = null;
        }
    }

    public static function countObresFavorites($id_usuari)
    {
        $c = new Connexion();
        $q = "SELECT COUNT(*)
    FROM bp_obres_favorites
    WHERE id_usuari = :i";

        try {
            return $c->query($q, Connexion::FETCH_COLUMN, [":i" => $id_usuari]);
        } finally {
            $c = null;
        }
    }

    public static function countAparicions($id_usuari)
    {
        $c = new Connexion();
        $q = "SELECT COUNT(*)
    FROM bp_aparicions
      INNER JOIN bp_aparicions_usuaris ON bp_aparicions_usuaris.id_aparicio = bp_aparicions.id_aparicio
    WHERE id_usuari = :i";

        try {
            return $c->query($q, Connexion::FETCH_COLUMN, [":i" => $id_usuari]);
        } finally {
            $c = null;
        }
    }
}
