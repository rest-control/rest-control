<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Utils;

use Psr\Log\InvalidArgumentException;

/**
 * Class AbstractResponseItem
 *
 * @package RestControl\Utils
 */
abstract class AbstractResponseItem
{
    /**
     * @var array|null
     */
    protected $requiredValues = null;

    /**
     * AbstractResponseItem constructor.
     *
     * @param array|null $requiredValues
     */
    public function __construct($requiredValues = null)
    {
        if(!is_array($requiredValues) && !is_null($requiredValues)) {
            throw new InvalidArgumentException('RequiredValues must be an array or null.');
        }

        $this->requiredValues = $requiredValues;
    }

    /**
     * @return array
     */
    abstract public function getStructure();

    /**
     * @return array|null
     */
    public function getRequiredValues()
    {
        return $this->requiredValues;
    }
}