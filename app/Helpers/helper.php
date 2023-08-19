<?php

use Morilog\Jalali\Jalalian;


function randomNumber($count = 5): string
{
    return substr(rand(0, microtime(true)), 0, $count);
}

function result($ajaxResponse, $redirectResponse)
{
    return \Illuminate\Support\Facades\Request::ajax() ? $ajaxResponse : $redirectResponse;
}


function jalaliDate($date, $format = '%A %d %B %Y')
{
    return Jalalian::forge($date)->format($format);
}

function convertNumbersToEnglish($string) {
    $newNumbers = range(0, 9);
    // 1. Persian HTML decimal
    $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
    // 2. Arabic HTML decimal
    $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
    // 3. Arabic Numeric
    $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
    // 4. Persian Numeric
    $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

    $string =  str_replace($persianDecimal, $newNumbers, $string);
    $string =  str_replace($arabicDecimal, $newNumbers, $string);
    $string =  str_replace($arabic, $newNumbers, $string);
    return str_replace($persian, $newNumbers, $string);
}

//function convertPersianToEnglish($number): array|string
//{
//    $numbers = str_replace(['۰', '۱', '۲', '', '', '', '', '', '', ''], []);
//    $number = str_replace('۰', '0', $number);
//    $number = str_replace('۱', '1', $number);
//    $number = str_replace('۲', '2', $number);
//    $number = str_replace('۳', '3', $number);
//    $number = str_replace('۴', '4', $number);
//    $number = str_replace('۵', '5', $number);
//    $number = str_replace('۶', '6', $number);
//    $number = str_replace('۷', '7', $number);
//    $number = str_replace('۸', '8', $number);
//    $number = str_replace('۹', '9', $number);
//
//    return $number;
//}
//
//function convertArabicToEnglish($number): array|string
//{
//    $number = str_replace('٠', '0', $number);
//    $number = str_replace('١', '1', $number);
//    $number = str_replace('٢', '2', $number);
//    $number = str_replace('٣', '3', $number);
//    $number = str_replace('٤', '4', $number);
//    $number = str_replace('٥', '5', $number);
//    $number = str_replace('٦', '6', $number);
//    $number = str_replace('٧', '7', $number);
//    $number = str_replace('٨', '8', $number);
//    $number = str_replace('٩', '9', $number);
//
//    return $number;
//}

function convertEnglishToPersian($number): array|string
{
    $number = str_replace('0', '۰', $number);
    $number = str_replace('1', '۱', $number);
    $number = str_replace('2', '۲', $number);
    $number = str_replace('3', '۳', $number);
    $number = str_replace('4', '۴', $number);
    $number = str_replace('5', '۵', $number);
    $number = str_replace('6', '۶', $number);
    $number = str_replace('7', '۷', $number);
    $number = str_replace('8', '۸', $number);
    $number = str_replace('9', '۹', $number);

    return $number;
}


function priceFormat($price): array|string
{
    $price = number_format($price, 0, '/', '،');
    $price = convertEnglishToPersian($price);
    return $price;
}


function validateNationalCode($nationalCode): bool
{
    $nationalCode = trim($nationalCode, ' .');
    $nationalCode = convertArabicToEnglish($nationalCode);
    $nationalCode = convertPersianToEnglish($nationalCode);
    $bannedArray = ['0000000000', '1111111111', '2222222222', '3333333333', '4444444444', '5555555555', '6666666666', '7777777777', '8888888888', '9999999999'];

    if (empty($nationalCode)) {
        return false;
    } else if (count(str_split($nationalCode)) != 10) {
        return false;
    } else if (in_array($nationalCode, $bannedArray)) {
        return false;
    } else {

        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += (int)$nationalCode[$i] * (10 - $i);
        }

        $divideRemaining = $sum % 11;

        if ($divideRemaining < 2) {
            $lastDigit = $divideRemaining;
        } else {
            $lastDigit = 11 - ($divideRemaining);
        }

        if ((int)$nationalCode[9] == $lastDigit) {
            return true;
        } else {
            return false;
        }

    }
}
