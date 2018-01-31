<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCasePipeline\Adapters;

use RestControl\ApiClient\ApiClientRequest;
use RestControl\TestCase\Request;

/**
 * Class ApiClientRequestAdapter
 * @package RestControl\TestCasePipeline\Adapters
 */
class ApiClientRequestAdapter
{
    /**
     * @param Request $request
     *
     * @return ApiClientRequest
     */
    public function transform(Request $request)
    {
        $apiRequest = new ApiClientRequest();

        $this->transformMethod($request, $apiRequest);
        $this->transformForm($request, $apiRequest);
        $this->transformBody($request, $apiRequest);

        return $apiRequest;
    }

    /**
     * @param Request $request
     * @param ApiClientRequest $apiRequest
     */
    protected function transformMethod(Request $request, ApiClientRequest $apiRequest)
    {
        /** @var \RestControl\TestCase\ChainObject|null $method */
        $method = $request->_getChainObject(Request::CO_METHOD);

        if($method) {
            $apiRequest->setMethod($method->getParam(0));
            $apiRequest->setUrl(
                $method->getParam(1),
                $method->getParam(2)
            );
        }
    }

    /**
     * @param Request $request
     * @param ApiClientRequest $apiRequest
     */
    protected function transformBody(Request $request, ApiClientRequest $apiRequest)
    {
        $bodyObjects = $request->_getChainObjects(Request::CO_BODY);

        $body = [];

        foreach($bodyObjects as $row) {
            /** @var \RestControl\TestCase\ChainObject $row */
            $rowBody = $row->getParam(0);

            if(gettype($body) !== gettype($rowBody)) {
                $body = $rowBody;
                continue;
            }

            if(is_array($rowBody)) {
                $body = array_merge($body, $rowBody);
                continue;
            }

            $body = $rowBody;
        }

        $apiRequest->setBody($body);
    }

    /**
     * @param Request $request
     * @param ApiClientRequest $apiRequest
     */
    protected function transformForm(Request $request, ApiClientRequest $apiRequest)
    {
        $formDataObjects = $request->_getChainObjects(Request::CO_FORM);
        $formData = [];

        foreach($formDataObjects as $row){
            /** @var  \RestControl\TestCase\ChainObject $row */
            $formData = array_merge($formData, $row->getParam(0));
        }

        $apiRequest->form($formData);
    }
}