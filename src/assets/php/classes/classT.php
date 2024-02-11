<?php
require_once "connexionPDO.php";
class T
{
    public static function llistaTonalitats()
    {
        $c = new Connexion();
        $q = "SELECT * FROM bp_tonalitats;";

        try {
            return $c->query($q, Connexion::FETCH_ALL);
        } finally {
            $c = null;
        }
    }
}
