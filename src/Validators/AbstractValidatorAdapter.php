<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Validators;

use Zend\Validator\ValidatorInterface;

/**
 * Class AbstractValidator
 */
abstract class AbstractValidatorAdapter
{
    /**
     * @var null|ValidatorInterface
     */
    protected $cache = null;

    /**
     * @param mixed $value
     * @param array $options
     *
     * @return bool
     */
    abstract public function isValid($value, array $options = []);

    /**
     * @param string $class
     * @param bool   $force
     * @param array  $constructorParams
     *
     * @return ValidatorInterface
     */
    protected function getValidator($class, $force = false, array $constructorParams = [])
    {
        if($this->cache && !$force) {
            return $this->cache;
        }

        $reflection = new \ReflectionClass($class);

        if(!$reflection->implementsInterface(ValidatorInterface::class)) {
            throw new \InvalidArgumentException('Validator must implements ValidatorInterface.');
        }

        $this->cache = $reflection->newInstanceArgs($constructorParams);

        return $this->cache;
    }
}