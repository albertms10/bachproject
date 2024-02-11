<?php
require_once "connexionPDO.php";
class Instrument
{
    public static function llistaInstrumentsMoviment($id_moviment)
    {
        $c = new Connexion();
        $q = "SELECT nom_instrument
      FROM bp_instruments
        INNER JOIN agrupacions_instruments ON agrupacions_instruments.id_instrument = bp_instruments.id_instrument
        INNER JOIN bp_agrupacions ON agrupacions_instruments.id_agrupacio = bp_agrupacions.id_agrupacio
        INNER JOIN bp_moviments_agrupacions ON bp_moviments_agrupacions.id_agrupacio = bp_agrupacions.id_agrupacio
        INNER JOIN bp_moviments ON bp_moviments_agrupacions.id_moviment = bp_moviments.id_moviment
      WHERE bp_moviments.id_moviment = :i;";

        try {
            return $c->query($q, Connexion::FETCH_ALL, [":i" => $id_moviment]);
        } finally {
            $c = null;
        }
    }
}
