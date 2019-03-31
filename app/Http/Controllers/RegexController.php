<?php

namespace App\Http\Controllers;

class RegexController extends Controller
{
    public function randomNumString($length, $amount)
    {
        $stringNums = array();
        for ($i = 0; $i < $amount; $i++) {
            $a = '';
            for ($j = 0; $j < $length; $j++) {
                $a .= mt_rand(0, 9);
            }
            array_push($stringNums, $a);
        }
        return $stringNums;
    }

    public static $_items = [
        'regex' => [
            self::_7_IN_ROW => '/^(.)\1*$/u',

        ],
        'regex_label' => [
            self::_7_IN_ROW => 'String contains only one different digit (pattern №1) aka 7 in row'

        ],
        'regex_numismatic_termin' => [
            self::_7_IN_ROW => '',

        ],


    ];

    const _7_IN_ROW = '_7_IN_ROW';

    public static function itemAlias($type, $code = null)
    {
        if (isset($code)) {
            return isset(self::$_items[$type][$code]) ? self::$_items[$type][$code] : false;
        } else {
            return isset(self::$_items[$type]) ? self::$_items[$type] : false;
        }
    }

    public function index()
    {
        $tab = "&nbsp; &nbsp; &nbsp;";
//
//        $pattern1 = '/^(.)\1*$/u';
//        $pattern11 = '#^100#';
//        $pattern12 = '#^200#';
//        $pattern13 = '#^500#';
//        $pattern14 = '#^1000#';

        $nums = RegexController::randomNumString(7, 1000);

        foreach ($nums as $key => $num) {
            echo $num;
            foreach (self::itemAlias('regex') as $k => $pattern) {
                if (preg_match($pattern, $num)) {
                    echo $tab;
                    echo self::itemAlias('regex_label', $k);
                }
            }
            echo "<br>";
        }
    }
}
