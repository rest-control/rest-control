<?php

use RestControl\TestCase\Request;
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\ExpressionLanguage\EqualsTo;
use RestControl\TestCase\ExpressionLanguage\ContainsString;
use RestControl\TestCase\ExpressionLanguage\StartsWith;
use RestControl\TestCase\ExpressionLanguage\EndsWith;
use RestControl\TestCase\ExpressionLanguage\LessThan;
use RestControl\TestCase\ExpressionLanguage\GreaterThan;
use RestControl\TestCase\ExpressionLanguage\EachItems;

if(!function_exists('send')) {
    function send() { return new Request(); }
}

if(!function_exists('equalsTo')) {
    function equalsTo($value, $identical = false){
        return new Expression(EqualsTo::FILTER_NAME, [$value, $identical]);
    }
}

if(!function_exists('containsString')) {
    function containsString($string){
        return new Expression(ContainsString::FILTER_NAME, [$string]);
    }
}

if(!function_exists('startsWith')) {
    function startsWith($string){
        return new Expression(StartsWith::FILTER_NAME, [$string]);
    }
}

if(!function_exists('endsWith')) {
    function endsWith($string){
        return new Expression(EndsWith::FILTER_NAME, [$string]);
    }
}

if(!function_exists('lessThan')) {
    function lessThan($lessThan, $orEqual = false){
        return new Expression(LessThan::FILTER_NAME, [$lessThan, $orEqual]);
    }
}

if(!function_exists('greaterThan')) {
    function greaterThan($greaterThan, $orEqual = false){
        return new Expression(GreaterThan::FILTER_NAME, [$greaterThan, $orEqual]);
    }
}

if(!function_exists('eachItems')) {
    function eachItems($expression){
        return new Expression(EachItems::FILTER_NAME, [$expression]);
    }
}