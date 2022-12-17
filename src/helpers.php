<?php

if(!function_exists('convert_dollars_to_cents')){
    function convert_dollars_to_cents(float $dollars):int {
        return (int) (round($dollars,2) * 100);
    }
}

if(!function_exists('convert_cents_to_dollar_string')){
    function convert_cents_to_dollar_string(?int $cents=0):string {
        return number_format(($cents /100), 2);
    }
}

if(!function_exists('convert_to_percentage_string')){
    function convert_to_percentage_string(?int $integer=0):string {
        return number_format(($integer /100), 2) .' %';
    }
}