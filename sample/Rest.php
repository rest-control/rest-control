<?php

namespace Sample;

use RestControl\TestCase\AbstractTestCase;

class Rest extends AbstractTestCase
{
    public function testA()
    {
        return $this->send()
             ->get('https://jsonplaceholder.typicode.com/posts/1')
             ->expectedResponse()
             ->headers([
                 ['Content-Type', $this->containsString('application/json')],

             ]);
    }
}