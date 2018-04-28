<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase;

use RestControl\TestCase\ExpressionLanguage\AfterDate;
use RestControl\TestCase\ExpressionLanguage\BeforeDate;
use RestControl\TestCase\ExpressionLanguage\Between;
use RestControl\TestCase\ExpressionLanguage\ContainsString;
use RestControl\TestCase\ExpressionLanguage\EachItems;
use RestControl\TestCase\ExpressionLanguage\EndsWith;
use RestControl\TestCase\ExpressionLanguage\EqualsTo;
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\ExpressionLanguage\LessThan;
use RestControl\TestCase\ExpressionLanguage\GreaterThan;
use RestControl\TestCase\ExpressionLanguage\OneOf;
use RestControl\TestCase\ExpressionLanguage\Regex;
use RestControl\TestCase\ExpressionLanguage\StartsWith;
use RestControl\TestCasePipeline\TestPipelineConfiguration;

abstract class AbstractTestCase
{
    /**
     * @var TestPipelineConfiguration
     */
    protected $testPipelineConfiguration;

    /**
     * AbstractTestCase constructor.
     *
     * @param TestPipelineConfiguration $testPipelineConfiguration
     */
    public function __construct(TestPipelineConfiguration $testPipelineConfiguration)
    {
        $this->testPipelineConfiguration = $testPipelineConfiguration;
    }

    /**
     * @param string $varPath
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getVar($varPath, $default = null)
    {
        $data = $this->testPipelineConfiguration->getVariable($varPath);

        if(empty($data)) {
            return $default;
        }

        return $data[0];
    }

    /**
     * @return Request
     */
    public function send()
    {
        return new Request();
    }

    /**
     * @param mixed $value
     * @param bool  $identical
     *
     * @return Expression
     */
    public function equalsTo($value, $identical = false)
    {
        return new Expression(EqualsTo::FILTER_NAME, [$value, $identical]);
    }

    /**
     * @param string $string
     *
     * @return Expression
     */
    public function containsString($string)
    {
        return new Expression(ContainsString::FILTER_NAME, [$string]);
    }

    /**
     * @param string $string
     *
     * @return Expression
     */
    public function startsWith($string)
    {
        return new Expression(StartsWith::FILTER_NAME, [$string]);
    }

    /**
     * @param string $string
     *
     * @return Expression
     */
    public function endsWith($string)
    {
        return new Expression(EndsWith::FILTER_NAME, [$string]);
    }

    /**
     * @param mixed $lessThan
     * @param bool  $orEqual
     *
     * @return Expression
     */
    public function lessThan($lessThan, $orEqual = false)
    {
        return new Expression(LessThan::FILTER_NAME, [$lessThan, $orEqual]);
    }

    /**
     * @param mixed $greaterThan
     * @param bool  $orEqual
     *
     * @return Expression
     */
    public function greaterThan($greaterThan, $orEqual = false)
    {
        return new Expression(GreaterThan::FILTER_NAME, [$greaterThan, $orEqual]);
    }

    /**
     * @param array|Expression $expression
     *
     * @return Expression
     */
    public function each($expression)
    {
        return new Expression(EachItems::FILTER_NAME, [$expression]);
    }

    /**
     * @param string $dateTime
     *
     * @return Expression
     */
    public function beforeDate($dateTime)
    {
        return new Expression(BeforeDate::FILTER_NAME, [$dateTime]);
    }

    /**
     * @param string $dateTime
     *
     * @return Expression
     */
    public function afterDate($dateTime)
    {
        return new Expression(AfterDate::FILTER_NAME, [$dateTime]);
    }

    /**
     * @param string $regexString
     *
     * @return Expression
     */
    public function regex($regexString)
    {
        return new Expression(Regex::FILTER_NAME, [$regexString]);
    }

    /**
     * @param mixed $minValue
     * @param mixed $maxValue
     *
     * @return Expression
     */
    public function between($minValue = null, $maxValue = null)
    {
        return new Expression(Between::FILTER_NAME, [$minValue, $maxValue]);
    }

    /**
     * @param array ...$expressions
     *
     * @return Expression
     */
    public function oneOf(...$expressions)
    {
        return new Expression(OneOf::FILTER_NAME, $expressions);
    }
}
