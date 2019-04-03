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
            self::_7_SAME_IN_ROW => '/^(?=.*([0-9])\1{6}.*=?).{7}$/',
            self::_6_SAME_IN_ROW => '/^(?=.*([0-9])\1{5}.*=?).{7}$/',
            self::_5_SAME_IN_ROW => '/^(?=.*([0-9])\1{4}.*=?).{7}$/',
            self::_4_SAME_IN_ROW => '/^(?=.*([0-9])\1{3}.*=?).{7}$/',
            self::_2_RADAR => '/^(?=.*([0-9])\1{1}.*([0-9])\2{1}.*([0-9])\3{1}.*=?).{7}$/',
            self::_3_RADAR => '/^(?=.*([0-9])\1{2}.*([0-9])\2{2}.*=?).{7}$/',

            self::_100 => '/^100[0-9]{4}$/',
            self::_200 => '/^200[0-9]{4}$/',
            self::_500 => '/^500[0-9]{4}$/',
            self::_1000 => '/^1000[0-9]{3}$/',

            self::_1 => '/^[0|1]{7}|[0|2]{7}|[0|3]{7}|[0|4]{7}|[0|5]{7}|[0|6]{7}|[0|7]{7}|[0|8]{7}|[0|9]{7}$/',

            self::_6_SAME => '/^([0-9])\1{3}$/u',
            self::_5_SAME => '/^([0-9])\1{3}$/u',

        ],
        'regex_label' => [
            self::_7_SAME_IN_ROW => 'String contains only one different digit (pattern #1) aka 7 in row',
            self::_6_SAME_IN_ROW => 'String contains only one different digit (pattern #1) aka 7 in row',
            self::_5_SAME_IN_ROW => 'String contains only one different digit (pattern #1) aka 7 in row',
            self::_4_SAME_IN_ROW => 'String contains only one different digit (pattern #1) aka 7 in row',

        ],
        'regex_numismatic_termin' => [

        ],
    ];

    const _7_SAME_IN_ROW = '_7_SAME_IN_ROW';
    const _6_SAME_IN_ROW = '_6_SAME_IN_ROW';
    const _5_SAME_IN_ROW = '_5_SAME_IN_ROW';
    const _4_SAME_IN_ROW = '_4_SAME_IN_ROW';
    const _3_SAME_IN_ROW = '_3_SAME_IN_ROW';
    const _2_RADAR = '_2_RADAR';
    const _3_RADAR = '_3_RADAR';

    const _100 = '_100';
    const _200 = '_200';
    const _500 = '_500';
    const _1000 = '_1000';

    const _1 = '_1';


    const _6_SAME = '_6_SAME';
    const _5_SAME = '_5_SAME';

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
        $nums = RegexController::randomNumString(7, 100000);

        foreach ($nums as $key => $num) {
            $match = false;
            $output = $num;
            foreach (self::itemAlias('regex') as $k => $pattern) {
                if (preg_match($pattern, $num)) {
                    $match = true;
                    $output .= $tab;
                    $output .= self::itemAlias('regex', $k);
                }
            }
            if ($match) {
                $output .= "<br>";
                echo $output;
            }
            $match = false;
        }
    }
}
