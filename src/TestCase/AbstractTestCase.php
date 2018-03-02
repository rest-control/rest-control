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

use RestControl\TestCase\ExpressionLanguage\ContainsString;
use RestControl\TestCase\ExpressionLanguage\EachItems;
use RestControl\TestCase\ExpressionLanguage\EndsWith;
use RestControl\TestCase\ExpressionLanguage\EqualsTo;
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\ExpressionLanguage\LessThan;
use RestControl\TestCase\ExpressionLanguage\MoreThan;
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
     * @return Request
     */
    public function send()
    {
        return new Request();
    }

    /**
     * @param mixed $value
     * @param bool  $exactlyTheSame
     *
     * @return Expression
     */
    public function equalsTo($value, $exactlyTheSame = false)
    {
        return new Expression(EqualsTo::FILTER_NAME, [$value, $exactlyTheSame]);
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
     * @param mixed $moreThan
     * @param bool  $orEqual
     *
     * @return Expression
     */
    public function moreThan($moreThan, $orEqual = false)
    {
        return new Expression(MoreThan::FILTER_NAME, [$moreThan, $orEqual]);
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
}
