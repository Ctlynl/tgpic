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
        $cookieFilePath = $this->getCookieFileName($this->config);
        // 上下文文件存在
        if (file_exists($cookieFilePath)) {
            $this->cookieContextFileExist($cookieFilePath);
        } else {
            $this->cookieContextFileAbsent($cookieFilePath);
        }
    }

    /**
     * cookie上下文文件存在
     * @param string $cookieFilePath
     * @noinspection PhpDeprecationInspection
     */
    private function cookieContextFileExist(string $cookieFilePath)
    {
        $cookie = file_get_contents($cookieFilePath);
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
    private function cookieContextFileAbsent(string $cookieFilePath)
    {
        // 合并请求参数
        $request = [
            'login-subject' => $this->config->userName,
            'password' => $this->config->password
        ];
        $request = $this->mergeFromDataParamsByArray($request);
        // 请求数据
        $response = $this->request('POST', '/login', ['multipart' => $request]);
        // 获取操作内容
        $content = $response->getBody()->getContents();
        // 错误消息
        preg_match('/PF.fn.growl.expirable\(\"(.*?)\"\);/', $content, $err);
        if (!empty($err) && isset($err[1])) {
            throw new TGRegAnalysisException($err[1]);
        }
        // 判断是否登录成功
        preg_match("/<title>.*?<\/title>/", $content, $matches);
        if (empty($matches[0]) || !stripos($matches[0], $this->config->userName)) {
            throw new TGRegAnalysisException('登录失败');
        }
        /** 写入文件 @noinspection PhpDeprecationInspection */
        $cookieJar = $this->client->getConfig()['cookies'];
        filePutDataLock($cookieFilePath, serialize($cookieJar));
        // 写入存储文件userId
        $this->analysisUserIdStorageFile($content);
    }

    /**
     * @param string $htmlContent
     * @throws TGRegAnalysisException
     */
    private function analysisUserIdStorageFile(string $htmlContent)
    {
        preg_match('/CHV.obj.resource.user((.|\n)*});/', $htmlContent, $matcher);
        if (empty($matcher[1])) {
            throw new TGRegAnalysisException('userId正则解析失败');
        }
        // 根据第一步解析，把换行空格去除
        $jsonContent = preg_replace('/[=\n\s]/', '', $matcher[1]);
        // 根据第二步json文件提取id内容
        preg_match('/id:"(.*)"/', $jsonContent, $jsonMatcher);
        if (empty($jsonMatcher[1])) {
            throw new TGRegAnalysisException("userId正则解析json失败[$jsonContent]");
        }
        $decodeArray = explode(',', $jsonMatcher[1]);
        $userId = trim($decodeArray[0], '"');
        // 存储userid
        filePutDataLock($this->getUserIdFileName($this->config), $userId);
    }
}
