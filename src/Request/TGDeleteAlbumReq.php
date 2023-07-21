<?php

namespace Ctlynl\Tgpic\Request;

use Ctlynl\Tgpic\Params\TGRequestParamInterface;

/**
 * @\Ctlynl\Tgpic\Request\TGDeleteAlbumReq
 */
class TGDeleteAlbumReq extends AbstractTGRequest
{

    public function execute(TGRequestParamInterface $requestParam = null)
    {
        $this->isRequestParamsNull($requestParam);
        $request = $this->mergeFromParams($requestParam);
        $response = $this->request('POST', '/json', ['form_params' => $request]);
        return tgJsonDecodeFun($response->getBody()->getContents());
    }
}
