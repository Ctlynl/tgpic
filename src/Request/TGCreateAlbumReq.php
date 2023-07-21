<?php

namespace Ctlynl\Tgpic\Request;

use Ctlynl\Tgpic\Exception\TGException;
use Ctlynl\Tgpic\Params\TGRequestParamInterface;

/**
 * @\Ctlynl\Tgpic\Request\TGCreateAlbumReq
 */
class TGCreateAlbumReq extends AbstractTGRequest
{

    /**
     * @inheritDoc
     */
    public function execute(TGRequestParamInterface $requestParam = null)
    {
        $this->isRequestParamsNull($requestParam);

        $requestData = $this->mergeFromParams($requestParam);

        // 创建相册信息
        $response = $this->request('POST', '/json', ['form_params' => $requestData]);

        $jsonContent = $response->getBody()->getContents();

        $content = tgJsonDecodeFun($jsonContent);
        if (empty($content) || $content['status_code'] !== 200) {
            throw new TGException('response info error');
        }
        if (empty($content['album']['id_encoded'])) {
            throw new TGException('create album error');
        }
        return $content;
    }
}
