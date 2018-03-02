<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCase;

use RestControl\TestCase\StubGenerator;
use PHPUnit\Framework\TestCase;

class StubGeneratorTest extends TestCase
{
    public function testStubGenerator()
    {
        $methods = [
            [
                'methodName'  => 'testSampleMethod',
                'title'       => 'sample title',
                'description' => 'sample long description',
                'tags'        => 'sample tag v1',
            ],
            [
                'methodName'  => 'anotherMethod',
                'title'       => 'sample title 2',
                'description' => 'sample long description 2',
                'tags'        => 'spl2 rest test',
            ],
        ];

        $generator = new StubGenerator();
        $response = $generator->create(
            'Sample\\Namespace\\Long',
            'SampleTestCase',
            $methods
        );

        $class = '<?php

namespace Sample\Namespace\Long;

use RestControl\TestCase\AbstractTestCase as BaseTestCase;

class SampleTestCase extends BaseTestCase
{
    /**
     * @test(
     *     title="sample title",
     *     description="sample long description",
     *     tags="sample tag v1"
     * )
     */
    public function testSampleMethod()
    {
        //todo
        return send();
    }
    
    /**
     * @test(
     *     title="sample title 2",
     *     description="sample long description 2",
     *     tags="spl2 rest test"
     * )
     */
    public function anotherMethod()
    {
        //todo
        return send();
    }
}';
        $this->assertSame(
            preg_replace('/\s+/', '', $class),
            preg_replace('/\s+/', '', $response)
        );
    }
}