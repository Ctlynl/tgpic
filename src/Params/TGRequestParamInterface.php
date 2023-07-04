<?php

namespace Ctlynl\Tgpic\Params;

/**
 * @\Ctlynl\Tgpic\Params\TGRequestParamInterface
 */
interface TGRequestParamInterface
{

    /**
     * 设置请求头
     * @param array $options
     * @return mixed
     */
    public function setHeaders(array $options);

    /**
     * 获取请求头
     * @return array
     */
    public function getHeaders(): array;

    /**
     * 获取请求数据
     * @return array
     */
    public function getRequestData(): array;

}
