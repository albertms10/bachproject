<?php
class Colors
{
    public static $colors = [
        "red",
        "orange",
        "yellow",
        "olive",
        "green",
        "teal",
        "blue",
        "violet",
        "purple",
        "pink",
        "brown",
        "grey",
        "black"
    ];
}

function ordinals($num)
{
    $sufix = "";
    if ($num == 1 || $num == 3) {
        $sufix = "r";
    } elseif ($num == 2) {
        $sufix = "n";
    } elseif ($num == 4) {
        $sufix = "t";
    } elseif ($num >= 5) {
        $sufix = "è";
    }

    return $num . $sufix;
}

function int_to_roman($number)
{
    $map = [
        'M'  => 1000,
        'CM' => 900,
        'D'  => 500,
        'CD' => 400,
        'C'  => 100,
        'XC' => 90,
        'L'  => 50,
        'XL' => 40,
        'X'  => 10,
        'IX' => 9,
        'V'  => 5,
        'IV' => 4,
        'I'  => 1
    ];
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if ($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}

function int_to_roman_alt($N)
{
    $c = 'IVXLCDM';
    for (
        $a = 5, $b = $s = '';
        $N;
        $b++, $a ^= 7
    ) {
        for (
            $o = $N % $a, $N /= $a ^ 0;
            $o--;
            $s = $c[$o > 2 ? $b + $N - ($N &= -2) + $o = 1 : $b] . $s
        );
    }
    return $s;
}

function time_ago($date)
{
    $timestamp = strtotime($date);
    $strTime = ["segon", "minut", "hora", "dia", "mes", "any"];
    $strTimes = ["segons", "minuts", "hores", "dies", "mesos", "anys"];
    $length = ["60", "60", "24", "30", "12", "10"];
    if (time() >= $timestamp) {
        $diff = time() - $timestamp;
        for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
            $diff /= $length[$i];
        }
        $diff = round($diff);
        return $diff . " " . ($diff == 1 ? $strTime[$i] : $strTimes[$i]);
    }
}

function compactize($llista)
{
    function range_to_string($ranges)
    {
        $results = [];
        foreach ($ranges as $range) {
            $start = $range[0];
            $stop = $range[count($range) - 1];

            array_push($results, $start . ($stop != $start ? "–$stop" : ""));
        }
        $result = implode(", ", $results);
        return $result;
    }

    $rangs = [];
    $llista = array_unique($llista);

    $inici = $llista[0];
    if (count($llista) > 1) {
        for ($i = 0; $i < count($llista) - 1; ++$i) {
            $a = $llista[$i];
            $b = $llista[$i + 1];
            if ($b != $a + 1) {
                array_push($rangs, range($inici, $a));
                $inici = $b;
            }
        }
    } else {
        $b = $llista[0];
    }

    array_push($rangs, range($inici, $b));
    return range_to_string($rangs);
}
