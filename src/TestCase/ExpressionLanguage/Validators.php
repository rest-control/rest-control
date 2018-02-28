<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase\ExpressionLanguage;

/**
 * Class Validators
 *
 * @package RestControl\TestCase
 */
class Validators
{
    /**
     * @var array
     */
    protected static $validators = [];

    /**
     * @var bool
     */
    private static $autoloaded = false;

    /**
     * @var array
     */
    private static $defaultValidators = [
        EqualsTo::class,
        ContainsString::class,
        StartsWith::class,
        EndsWith::class,
        LessThan::class,
        EachItems::class,
        MoreThan::class,
    ];

    /**
     * @param ExpressionValidatorInterface $validator
     */
    public static function registerValidator(ExpressionValidatorInterface $validator)
    {
        self::$validators[$validator->getName()] = $validator;
    }

    /**
     * @param Expression $expression
     * @param mixed      $value
     *
     * @return bool
     */
    public static function checkExpressionValidator(Expression $expression, $value)
    {
        self::__autoload();

        $validatorName = $expression->getName();

        if(!isset(self::$validators[$validatorName])) {
            throw new \InvalidArgumentException('Validator '.$validatorName . ' does not exits.');
        }

        return self::$validators[$validatorName]->check($expression, $value);
    }

    /**
     * Autoload Validators bag.
     */
    protected static function __autoload()
    {
        if(self::$autoloaded) {
            return;
        }

        foreach(self::$defaultValidators as $validator) {
            self::registerValidator(new $validator);
        }

        self::$autoloaded = true;
    }
}