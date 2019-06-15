<?php


namespace App\Utils;


class CodeGenerator
{
    public static function generateNumberCode (int  $length = 6){
        $didgits = '0123456789';
        if ($length>($strlen = mb_strlen($didgits))){
            throw new \InvalidArgumentException(sprintf('Length should be less than - %d', $strlen));
        }

        return substr(str_shuffle(str_shuffle($didgits)), 0, $length);

    }

}