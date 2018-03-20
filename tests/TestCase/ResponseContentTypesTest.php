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

use RestControl\TestCase\ChainObject;
use RestControl\TestCase\Response;
use PHPUnit\Framework\TestCase;
use RestControl\TestCase\ResponseFilters\ContentTypeFilter;

class ResponseContentTypesTest extends TestCase
{
    public function testContentTypes()
    {
        $contentTypes  = [
            [
                'audio/aac',
                'contentTypeAudioAac',
            ],

            [
                'application/x-abiword',
                'contentTypeApplicationXAbiword',
            ],

            [
                'application/octet-stream',
                'contentTypeApplicationOctetStream',
            ],

            [
                'video/x-msvideo',
                'contentTypeVideoXMsvideo',
            ],

            [
                'application/vnd.amazon.ebook',
                'contentTypeApplicationVndAmazonEbook',
            ],

            [
                'application/x-bzip',
                'contentTypeApplicationXBzip',
            ],

            [
                'application/x-bzip2',
                'contentTypeApplicationXBzip2',
            ],

            [
                'application/x-csh',
                'contentTypeApplicationXCsh',
            ],

            [
                'text/css',
                'contentTypeTextCss',
            ],

            [
                'text/csv',
                'contentTypeTextCsv',
            ],

            [
                'application/msword',
                'contentTypeApplicationMsword',
            ],

            [
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'contentTypeApplicationVndOpenxmlformatsOfficedocumentWordprocessingmlDocument',
            ],

            [
                'application/vnd.ms-fontobject',
                'contentTypeApplicationVndMsFontobject',
            ],

            [
                'application/epub+zip',
                'contentTypeApplicationEpubZip',
            ],

            [
                'application/ecmascript',
                'contentTypeApplicationEcmascript',
            ],
            [
                'image/gif',
                'contentTypeImageGif',
            ],

            [
                'text/html',
                'contentTypeTextHtml',
            ],

            [
                'image/x-icon',
                'contentTypeImageXIcon',
            ],
        ];

        foreach($contentTypes as $contentTypeConf) {

            $response = new Response();
            $response->{$contentTypeConf[1]}();

            $this->assertSame(1, $response->_getChainLength());

            $chainObjects = $response->_getChainObjects(ContentTypeFilter::FILTER_NAME);
            $this->assertCount(1, $chainObjects);
            $this->assertInstanceOf(ChainObject::class, $chainObjects[0]);

            /** @var ChainObject $chainObject */
            $chainObject = $chainObjects[0];

            $this->assertSame($contentTypeConf[0], $chainObject->getParam(0));
        }
    }
}