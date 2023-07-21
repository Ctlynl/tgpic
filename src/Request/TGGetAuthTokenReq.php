<?php

namespace Ctlynl\Tgpic\Request;

use Ctlynl\Tgpic\Exception\TGRegAnalysisException;
use Ctlynl\Tgpic\Params\TGRequestParamInterface;

/**
 * \Ctlynl\Tgpic\Request\TGGetAuthTokenReq
 */
class TGGetAuthTokenReq extends AbstractTGRequest
{

    /**
     * @throws TGRegAnalysisException
     * @throws \Ctlynl\Tgpic\Exception\TGException
     * @throws \Ctlynl\Tgpic\Exception\TGHttpRequestException
     */
    public function execute(TGRequestParamInterface $requestParam = null)
    {
        $authTokenFilePath = $this->getAuthTokenFileName($this->config);
        if (file_exists($authTokenFilePath)) {
            return file_get_contents($authTokenFilePath);
        }

        $response = $this->request('GET', '/login');

        $content = $response->getBody();

        // 提取authToken
        preg_match("/PF.obj.config.auth_token(.*?);/", $content, $matches);

        if (empty($matches) || empty($matches[1])) {
            throw new TGRegAnalysisException('获取authToken失败');
        }

        $authToken = explode('"', $matches[1]);
        if (empty($authToken[1])) {
            throw new TGRegAnalysisException('获取authToken成功正则解析token失败' . $matches[1]);
        }
        // 写入authToken内容
        filePutDataLock($authTokenFilePath, $authToken[1]);
        return $authToken[1];
    }
}
