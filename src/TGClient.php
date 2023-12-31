<?php

namespace Ctlynl\Tgpic;

use Ctlynl\Tgpic\Exception\TGInvalidParameterException;
use Ctlynl\Tgpic\Params\TGRequestParamInterface;
use Ctlynl\Tgpic\Request\TGRequestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

/**
 * @\Ctlynl\Tgpic\TGClient
 * @method mixed  authLoginReq(TGRequestParamInterface $requestParam = null)
 * @method string getAuthTokenReq(TGRequestParamInterface $requestParam = null)
 * @method string imgUploadReq(TGRequestParamInterface $requestParam = null)
 * @method array createAlbumReq(TGRequestParamInterface $requestParam = null)
 * @method array deleteAlbumReq(TGRequestParamInterface $requestParam = null)
 * @method array getAlbumsReq(TGRequestParamInterface $requestParam = null)
 */
class TGClient
{

    /**
     * authToken
     */
    private string $authToken;

    /**
     * 配置文件
     * @var TGConfig|null
     */
    private TGConfig $config;

    /**
     * http客户端
     * @var Client
     */
    private Client $httpClient;

    /**
     * @throws TGInvalidParameterException
     */
    public function __construct(TGConfig $config)
    {
        $this->config = $config;
        $this->assertRequiredOptions();
        $this->initHttpClient();
        $this->authToken = $this->getAuthTokenReq();
    }

    /**
     * 校验TG参数赋值
     * @return void
     * @throws TGInvalidParameterException
     */
    private function assertRequiredOptions()
    {
        $missing = array_diff($this->getRequiredOptions(), array_keys($this->config->getConfig()));
        if (!empty($missing)) {
            throw new TGInvalidParameterException(
                'Required options not defined: ' . implode(',', $missing)
            );
        }
    }

    /**
     * 获取必填选项
     * @return string[]
     */
    private function getRequiredOptions(): array
    {
        return [
            'baseUrl',
            'userName',
            'password',
            'userStoragePath',
        ];
    }

    /**
     * 初始化http客户端
     */
    private function initHttpClient()
    {
        $options = [
            'base_uri' => $this->config->baseUrl,
            'cookies' => new CookieJar(),
            'headers' => !empty($this->config->globalHeaders) ? $this->config->globalHeaders : []
        ];
        $this->httpClient = new Client($options);
    }

    /**
     * 登录TG web 平台
     */
    public function postLoginTgWebPlatform()
    {
        $this->authLoginReq();
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $className = __NAMESPACE__ . '\\Request\\TG' . ucfirst($name);
        /**@var $fackes TGRequestInterface */
        $objectFacade = new $className($this->httpClient, $this->config, $this->authToken ?? '');
        return $objectFacade->execute(...$arguments);
    }
}
