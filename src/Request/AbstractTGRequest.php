<?php

namespace Ctlynl\Tgpic\Request;

use Ctlynl\Tgpic\Exception\TGException;
use Ctlynl\Tgpic\Exception\TGHttpRequestException;
use Ctlynl\Tgpic\Exception\TGInvalidParameterException;
use Ctlynl\Tgpic\Params\TGRequestParamInterface;
use Ctlynl\Tgpic\TGConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Utils;

/**
 * @\Ctlynl\Tgpic\Request\AbstractTGRequest
 */
abstract class AbstractTGRequest implements TGRequestInterface
{

    protected Client $client;

    protected TGConfig $config;

    protected string $authToken;

    public function __construct(Client $client, TGConfig $config, string $authToken)
    {
        $this->client = $client;
        $this->config = $config;
        $this->authToken = $authToken;
    }

    /**
     * @throws TGInvalidParameterException
     */
    protected function isRequestParamsNull(TGRequestParamInterface $requestParam = null)
    {
        if (is_null($requestParam)) {
            throw new TGInvalidParameterException('缺少参数');
        }
    }

    /**
     * 合并/处理请求参数
     * @param TGRequestParamInterface $requestParam
     * @return array
     */
    protected function mergeParams(TGRequestParamInterface $requestParam): array
    {
        $params = array_merge($requestParam->getRequestData(), ['auth_token' => $this->authToken]);
        $multipart = [];
        foreach ($params as $key => $datum) {
            // 上传文件
            if (!is_array($datum)) {
                $multipart[] = ['name' => $key, 'contents' => $datum];
                continue;
            }
            $multipart[] = [
                'name' => $key,
                'filename' => basename($datum['file']),
                'contents' => Utils::tryFopen($datum['file'], 'r')
            ];
        }
        return $multipart;
    }

    /**
     * 请求接口
     * @throws TGHttpRequestException
     * @throws TGException
     */
    protected function request($method, $uri, array $options = []): \Psr\Http\Message\ResponseInterface
    {
        try {
            return $this->client->request($method, $uri, $options);
        } catch (RequestException $exception) {
            $statusCode = $exception->getResponse()->getStatusCode();
            $errMsg = $exception->getResponse()->getReasonPhrase();
            // 403代表请求被拒绝,或者权限访问此网页或链接已过期.删除cookie上下文及authToken重新获取
            if ($statusCode === 403) {
                unlinkFile($this->config->authTokenFilePathName);
                unlinkFile($this->config->cookieContextFilePathName);
                throw new TGHttpRequestException("[$statusCode][$errMsg]没有权限访问，认证已过期，请重试");
            } else {
                throw new TGHttpRequestException("[$statusCode]$errMsg", $statusCode, $exception);
            }
        } catch (GuzzleException $e) {
            throw new TGException('GuzzleException异常' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
