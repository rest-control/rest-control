<?php

use RestControl\TestCase\Request;
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\ExpressionLanguage\EqualsTo;
use RestControl\TestCase\ExpressionLanguage\ContainsString;
use RestControl\TestCase\ExpressionLanguage\StartsWith;
use RestControl\TestCase\ExpressionLanguage\EndsWith;
use RestControl\TestCase\ExpressionLanguage\LessThan;
use RestControl\TestCase\ExpressionLanguage\MoreThan;
use RestControl\TestCase\ExpressionLanguage\Each;

if(!function_exists('send')) {
    function send() { return new Request(); }
}

if(!function_exists('equalsTo')) {
    function equalsTo($value, $exactlyTheSame = false){
        return new Expression(EqualsTo::FILTER_NAME, [$value, $exactlyTheSame]);
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

if(!function_exists('moreThan')) {
    function moreThan($moreThan, $orEqual = false){
        return new Expression(MoreThan::FILTER_NAME, [$moreThan, $orEqual]);
    }
}

if(!function_exists('eachItems')) {
    function eachItems($expression){
        return new Expression(Each::FILTER_NAME, [$expression]);
    }
}