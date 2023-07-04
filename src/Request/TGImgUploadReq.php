<?php

namespace Ctlynl\Tgpic\Request;

use Ctlynl\Tgpic\Params\TGRequestParamInterface;

/**
 * @\Ctlynl\Tgpic\Request\TGImgUploadReq
 */
class TGImgUploadReq extends AbstractTGRequest
{

    /**
     * @throws \Ctlynl\Tgpic\Exception\TGInvalidParameterException
     * @throws \Ctlynl\Tgpic\Exception\TGException
     * @throws \Ctlynl\Tgpic\Exception\TGHttpRequestException
     */
    public function execute(TGRequestParamInterface $requestParam = null)
    {
        $this->isRequestParamsNull($requestParam);

        // 合并请求参数
        $multipart = $this->mergeParams($requestParam);

        // 上传图片
        $response = $this->request('POST', '/json', ['multipart' => $multipart]);

        return $response->getBody()->getContents();
    }
}
