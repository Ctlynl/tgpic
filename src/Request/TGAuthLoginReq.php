<?php

namespace Ctlynl\Tgpic\Request;

use Ctlynl\Tgpic\Exception\TGInvalidParameterException;
use Ctlynl\Tgpic\Exception\TGRegAnalysisException;
use Ctlynl\Tgpic\Params\TGRequestParamInterface;
use GuzzleHttp\Cookie\CookieJar;

/**
 * @\Ctlynl\Tgpic\Request\TGAuthLoginReq
 */
class TGAuthLoginReq extends AbstractTGRequest
{

    /**
     * @throws TGRegAnalysisException
     * @throws TGInvalidParameterException
     * @throws \Ctlynl\Tgpic\Exception\TGException
     * @throws \Ctlynl\Tgpic\Exception\TGHttpRequestException
     */
    public function execute(TGRequestParamInterface $requestParam = null)
    {
        $this->isRequestParamsNull($requestParam);
        // 上下文文件存在
        if (file_exists($this->config->cookieContextFilePathName)) {
            $this->cookieContextFileExist();
        } else {
            $this->cookieContextFileAbsent($requestParam);
        }
    }

    /**
     * cookie上下文文件存在
     * @noinspection PhpDeprecationInspection
     */
    private function cookieContextFileExist()
    {
        $cookie = file_get_contents($this->config->cookieContextFilePathName);
        /**@var $cookieJarOld CookieJar */
        $cookieJarOld = unserialize($cookie);
        $cookieJar = $this->client->getConfig()['cookies'];
        $cookieJar->clearSessionCookies();

        foreach ($cookieJarOld->getIterator() as $value) {
            $cookieJar->setCookie($value);
        }
    }


    /**
     * @throws TGRegAnalysisException
     * @throws \Ctlynl\Tgpic\Exception\TGException
     * @throws \Ctlynl\Tgpic\Exception\TGHttpRequestException
     */
    private function cookieContextFileAbsent(TGRequestParamInterface $requestParam)
    {
        // 合并请求参数
        $multipart = $this->mergeParams($requestParam);

        $response = $this->request('POST', '/login', ['multipart' => $multipart]);

        // 请求数据

        // 获取操作内容
        $content = $response->getBody()->getContents();

        // 错误消息
        preg_match('/PF.fn.growl.expirable\(\"(.*?)\"\);/', $content, $err);
        if (!empty($err) && isset($err[1])) {
            throw new TGRegAnalysisException($err[1]);
        }

        // 判断是否登录成功
        preg_match("/<title>.*?<\/title>/", $content, $matches);
        if (empty($matches[0]) || !stripos($matches[0], $requestParam->getRequestData()['login-subject'])) {
            throw new TGRegAnalysisException('登录失败');
        }

        /** 写入文件 @noinspection PhpDeprecationInspection */
        $cookieJar = $this->client->getConfig()['cookies'];
        file_put_contents($this->config->cookieContextFilePathName, serialize($cookieJar), \LOCK_EX);
    }
}
