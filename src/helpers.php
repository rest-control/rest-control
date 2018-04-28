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
use RestControl\TestCase\ExpressionLanguage\Regex;
use RestControl\TestCase\ExpressionLanguage\Between;
use RestControl\TestCase\ExpressionLanguage\OneOf;

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

if(!function_exists('regex')) {
    function regex($regexString){
        return new Expression(Regex::FILTER_NAME, [$regexString]);
    }
}

if(!function_exists('between')) {
    function between($minValue = null, $maxValue = null){
        return new Expression(Between::FILTER_NAME, [$minValue, $maxValue]);
    }
}

if(!function_exists('oneOf')) {
    function oneOf(...$expressions){
        return new Expression(OneOf::FILTER_NAME, $expressions);
    }
}