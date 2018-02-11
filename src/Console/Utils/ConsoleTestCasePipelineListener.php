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
use RestControl\TestCasePipeline\Events\BeforeTestCaseEvent;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use RestControl\TestCase\ResponseFilters\FilterException;
use RestControl\TestCasePipeline\TestObject;

/**
 * Class ConsoleTestCasePipelineListener
 *
 * @package RestControl\Console\Utils
 */
class ConsoleTestCasePipelineListener implements EventSubscriberInterface
{
    const TABLE_WIDTH = 12;

    const MAIN_PADDING_COLOR = 'blue';

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var FormatterHelper
     */
    protected $formatter;

    /**
     * ConsoleTestCasePipelineListener constructor.
     *
     * @param OutputInterface $output
     * @param FormatterHelper $formatter
     */
    public function __construct(
        OutputInterface $output,
        FormatterHelper $formatter
    ){
        $this->output    = $output;
        $this->formatter = $formatter;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            AfterTestCaseEvent::NAME => 'afterTestCaseResult',
            BeforeTestCaseEvent::NAME => 'beforeTestCaseResult',
        ];
    }

    /**
     * @param BeforeTestCaseEvent $event
     */
    public function beforeTestCaseResult(BeforeTestCaseEvent $event)
    {
        $testCase = $event->getTestObject();
        $this->printHeader($testCase);
    }

    /**
     * @param TestObject $testObject
     */
    protected function printHeader(TestObject $testObject)
    {
        $delegate = $testObject->getDelegate();

        $this->output->writeln("<options=bold>\u{1F449} #" . $testObject->getQueueIndex() . " " . $delegate->getClassName() . '@'. $delegate->getMethodName() . '</>');
        $this->output->writeln("<bg=" . self::MAIN_PADDING_COLOR ."> </> - Title: " . $delegate->getTitle());
        $this->output->writeln("<bg=" . self::MAIN_PADDING_COLOR ."> </> - Description: " . $delegate->getDescription());
        $this->output->writeln("<bg=" . self::MAIN_PADDING_COLOR ."> </> - Tags: " . implode(', ', $delegate->getTags()));
        $this->output->writeln('<bg=' . self::MAIN_PADDING_COLOR .'> </> ');
        $this->output->writeln("<bg=" . self::MAIN_PADDING_COLOR ."> </> Starting...");
    }

    /**
     * @param AfterTestCaseEvent $event
     */
    public function afterTestCaseResult(AfterTestCaseEvent $event)
    {
        $testCase = $event->getTestObject();

        $this->printResponseTime($testCase);
        $this->printTestStatus($testCase);
        $this->printExceptions($testCase);

        $this->output->writeln('');

        return;
    }

    /**
     * @param TestObject $testObject
     */
    protected function printResponseTime(TestObject $testObject)
    {
        $this->output->writeln("<bg=" . self::MAIN_PADDING_COLOR ."> </> Parsing response...");
        $this->output->writeln('<bg=' . self::MAIN_PADDING_COLOR .'> </> ');
        $this->output->writeln("<bg=" . self::MAIN_PADDING_COLOR ."> </> \u{1F552} Response time: " . $testObject->getRequestTime());
    }

    /**
     * @param TestObject $testObject
     */
    protected function printTestStatus(TestObject $testObject)
    {
        $this->output->writeln('<bg=' . self::MAIN_PADDING_COLOR .'> </> ');

        if($testObject->hasErrors()) {
            $exceptionsCount = count($testObject->getExceptions());
            $this->output->writeln("<bg=" . self::MAIN_PADDING_COLOR ."> </> <bg=red;fg=white;options=bold> \u{1F5F8} ERRORS (" . $exceptionsCount . ") </>");

            return;
        }

        $this->output->writeln("<bg=" . self::MAIN_PADDING_COLOR ."> </> <bg=green;fg=white;options=bold> \u{1F5F8} SUCCESS </>");
    }

    /**
     * @param TestObject $testObject
     */
    protected function printExceptions(TestObject $testObject)
    {
        if(!$testObject->hasErrors()) {
            return;
        }

        $exceptions = $testObject->getExceptions();

        foreach($exceptions as $iException => $exception) {

            $this->output->writeln('<bg=' . self::MAIN_PADDING_COLOR .'> </> ');
            $this->output->writeln("<bg=" . self::MAIN_PADDING_COLOR ."> </> <fg=red>(" . ($iException + 1) . ") " . get_class($exception) . "</>");

            if(!$exception instanceof \Exception) {
                continue;
            }

            if($exception instanceof FilterException) {
                $this->displayFilterExceptionInfo($exception);
            } else {
                $this->output->writeln('Exception message: ' . $exception->getMessage());
                $this->_drawLineCallback($exception->getTraceAsString(), function($line) {
                    return "<bg=" . self::MAIN_PADDING_COLOR ."> </>    <bg=magenta> </> " . $line;
                });
            }
        }
    }

    /**
     * @param FilterException $exception
     */
    protected function displayFilterExceptionInfo(FilterException $exception)
    {
        $this->output->writeln('<bg=' . self::MAIN_PADDING_COLOR .'> </> ');
        $this->output->writeln('<bg=' . self::MAIN_PADDING_COLOR .'> </>   Filter class: ' . get_class($exception->getFilter()));
        $this->output->writeln('<bg=' . self::MAIN_PADDING_COLOR .'> </>   Filter name: ' . $exception->getFilter()->getName());
        $this->output->writeln('<bg=' . self::MAIN_PADDING_COLOR .'> </>   Error code: ' . $exception->getCode());

        $this->output->writeln('<bg=' . self::MAIN_PADDING_COLOR .'> </> ');
        $this->output->writeln('<bg=' . self::MAIN_PADDING_COLOR .'> </>   Expected value: ');
        $this->output->writeln('<bg=' . self::MAIN_PADDING_COLOR .'> </> ');

        $this->_drawLineCallback(print_r($exception->getExpected(), true), function($line) {
            return "<bg=" . self::MAIN_PADDING_COLOR ."> </>    <bg=cyan> </> " . $line;
        });

        $this->output->writeln('<bg=' . self::MAIN_PADDING_COLOR .'> </> ');
        $this->output->writeln('<bg=' . self::MAIN_PADDING_COLOR .'> </>   Given value: ');
        $this->output->writeln('<bg=' . self::MAIN_PADDING_COLOR .'> </> ');

        $this->_drawLineCallback(print_r($exception->getGiven(), true), function($line) {
            return "<bg=" . self::MAIN_PADDING_COLOR ."> </>    <bg=magenta> </> " . $line;
        });
    }

    /**
     * @param string   $string
     * @param callable $callback
     */
    protected function _drawLineCallback($string, $callback)
    {
        $lines = explode(PHP_EOL, $string);

        foreach($lines as $line) {

            if(is_callable($callback)) {
                $line = $callback($line);
            }

            $this->output->writeln($line);
        }
    }
}
