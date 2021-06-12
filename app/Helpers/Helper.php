<?php
namespace App\Helpers;

class Helper
{
    public static function errorResponse($string): array
    {
        return ["error"=>true,"message"=>$string];
    }

    public static function successResponse($string): array
    {
        return ["error"=>false,"message"=>$string];
    }

    public static function getMonthsList(): array
    {
        return [
            '1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December'
        ];
    }

}
