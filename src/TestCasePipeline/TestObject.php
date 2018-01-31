<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCasePipeline;

use RestControl\Loader\TestCaseDelegate;
use RestControl\TestCase\Request;
use Psr\Log\InvalidArgumentException;

/**
 * Class TestObject
 * @package RestControl\TestCasePipeline
 */
class TestObject
{
    /**
     * @var null|TestCaseDelegate
     */
    protected $delegate = null;

    /**
     * @var null|Request
     */
    protected $requestChain = null;

    /**
     * @var array
     */
    protected $exceptions = [];

    /**
     * @var float
     */
    protected $requestTime = 0.0;

    /**
     * @var int
     */
    protected $queueIndex = 0;

    public function __construct(TestCaseDelegate $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @param float $time
     */
    public function setRequestTime($time)
    {
        $this->requestTime = (float) $time;
    }

    /**
     * @return float
     */
    public function getRequestTime()
    {
        return $this->requestTime;
    }

    /**
     * @param int $i
     */
    public function setQueueIndex($i)
    {
        if(!is_int($i)) {
            throw new InvalidArgumentException('Queue index must be an integer.');
        }

        $this->queueIndex = $i;
    }

    /**
     * @return int
     */
    public function getQueueIndex()
    {
        return $this->queueIndex;
    }
    /**
     * @param Request $request
     */
    public function setRequestChain(Request $request)
    {
        $this->requestChain = $request;
    }

    /**
     * @return TestCaseDelegate|null
     */
    public function getDelegate()
    {
        return $this->delegate;
    }

    /**
     * @param \Exception $e
     */
    public function addException(\Exception $e)
    {
        $this->exceptions []= $e;
    }

    /**
     * @return null|Request
     */
    public function getRequestChain()
    {
        return $this->requestChain;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->exceptions);
    }

    /**
     * @return array
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }
}