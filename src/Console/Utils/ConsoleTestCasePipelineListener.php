<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Console\Utils;

use RestControl\TestCasePipeline\Events\AfterTestCaseEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use RestControl\TestCase\ResponseFilters\FilterException;
use RestControl\TestCasePipeline\TestObject;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\TableCell;

/**
 * Class ConsoleTestCasePipelineListener
 *
 * @package RestControl\Console\Utils
 */
class ConsoleTestCasePipelineListener implements EventSubscriberInterface
{
    const TABLE_WIDTH = 12;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * ConsoleTestCasePipelineListener constructor.
     *
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            AfterTestCaseEvent::NAME => 'onAfterTestCaseResult',
        ];
    }

    /**
     * @param AfterTestCaseEvent $event
     */
    public function onAfterTestCaseResult(AfterTestCaseEvent $event)
    {
        $testCase = $event->getTestObject();

        $table = new Table($this->output);

        $this->setTableHeader($table, $testCase);
        $this->setTableInfo($table, $testCase);
        $this->setTableTestsErrors($table, $testCase);

        $table->render();
    }

    /**
     * @param Table      $table
     * @param TestObject $testObject
     */
    protected function setTableTestsErrors(Table $table, TestObject $testObject)
    {
        if(!$testObject->hasErrors()) {
            return;
        }

        $exceptions = $testObject->getExceptions();

        $table->addRows([
            [new TableSeparator(['colspan' => self::TABLE_WIDTH])],
            [$this->getFullLine('Exceptions(' . count($exceptions) . '):')]
        ]);

        foreach($exceptions as $iException => $exception){

            if($exception instanceof FilterException) {

                $expectedValue = $exception->getExpected();
                $givenValue = $exception->getGiven();

                if(is_array($expectedValue)) {
                    $expectedValue = print_r($expectedValue, true);
                }

                if(is_array($givenValue)) {
                    $givenValue = print_r($givenValue, true);
                }

                $table->addRows([
                    [new TableSeparator(['colspan' => self::TABLE_WIDTH])],
                    [$this->getFullLine('#'.$iException)],
                    [$this->getFullLine('Filter class: ' . get_class($exception->getFilter()))],
                    [$this->getFullLine('Filter name: ' . $exception->getFilter()->getName())],
                    [$this->getFullLine('Filter error code: ' . $exception->getErrorType())],
                    [$this->getFullLine('Expected value: ' . $expectedValue)],
                    [$this->getFullLine('Given value: ' . $givenValue)],

                ]);
            } else if ($exception instanceof \Exception){
                $table->addRows([
                    [new TableSeparator(['colspan' => self::TABLE_WIDTH])],
                    [$this->getFullLine('#'.$iException)],
                    [$this->getFullLine('Exception class: ' . get_class($exception))],
                    [$this->getFullLine('Exception: '. $exception->getMessage())]
                ]);
            } else {
                $table->addRows([
                    [new TableSeparator(['colspan' => self::TABLE_WIDTH])],
                    [$this->getFullLine('#'.$iException)],
                    [$this->getFullLine('Exception class: ' . get_class($exception))],
                    [$this->getFullLine('Cannot display information about exception')],
                ]);
            }
        }
    }

    /**
     * @param string $value
     * @param array  $options
     *
     * @return TableCell
     */
    protected function getFullLine($value, array $options = [])
    {
        return new TableCell($value, array_merge(['colspan' => self::TABLE_WIDTH], $options));
    }

    /**
     * @param Table      $table
     * @param TestObject $testObject
     */
    protected function setTableInfo(Table $table, TestObject $testObject)
    {
        $testDelegate = $testObject->getDelegate();

        $table->addRows([
            [$this->getFullLine('Title: <options=bold>' . $testDelegate->getTitle() . '</>')],
            [$this->getFullLine('Tags: <options=bold>' . implode(',', $testDelegate->getTags()) . '</>')],
            [$this->getFullLine('Description: <options=bold>' . $testDelegate->getDescription() . '</>')],
            [$this->getFullLine('Request Time: '.$testObject->getRequestTime())],
        ]);
    }

    /**
     * @param Table      $table
     * @param TestObject $testObject
     */
    protected function setTableHeader(Table $table, TestObject $testObject)
    {
        $testDelegate = $testObject->getDelegate();
        $testIndex = '#'.$testObject->getQueueIndex();

        $headLine = [];

        if(!$testObject->hasErrors()) {
            $headLine []= '<fg=white;bg=green> ' . $testIndex . ' SUCCESS </>';
        } else {
            $headLine []= '<fg=white;bg=red> ' . $testIndex . ' ERROR </>';
        }

        $headLine []= ' <options=bold>'.$testDelegate->getClassName() . '@' . $testDelegate->getMethodName() . '</>';

        $table->setHeaders([
            [$this->getFullLine(implode('', $headLine))]
        ]);
    }
}
