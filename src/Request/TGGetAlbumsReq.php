<?php

namespace Ctlynl\Tgpic\Request;

use Ctlynl\Tgpic\Exception\TGInvalidParameterException;
use Ctlynl\Tgpic\Params\TGRequestParamInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @\Ctlynl\Tgpic\Request\TGGetAlbumsReq
 */
class TGGetAlbumsReq extends AbstractTGRequest
{

    /**
     * @param TGRequestParamInterface|null $requestParam
     * @return array
     * @throws TGInvalidParameterException
     * @throws \Ctlynl\Tgpic\Exception\TGException
     * @throws \Ctlynl\Tgpic\Exception\TGHttpRequestException
     */
    public function execute(TGRequestParamInterface $requestParam = null): array
    {
        $this->isRequestParamsNull($requestParam);
        $reqArray = $requestParam->getRequestData();
        if (empty($reqArray['userName'])) {
            throw new TGInvalidParameterException('用户名参数不能为空');
        }

        $uri = "{$reqArray['userName']}/albums";
        $response = $this->request('GET', $uri);

        // 处理返回的html文本
        $crawler = new Crawler($response->getBody()->getContents());
        // 获取每个相册信息
        $nodes = $crawler->filterXPath("//div[@id='list-most-recent']/div[@class='pad-content-listing']/div");
        $albums = [];
        /**@var $domElement \DOMElement */
        foreach ($nodes as $domElement) {
            array_push($albums, $this->domAttrToArray($domElement));
        }
        return $albums;
    }

    /**
     * dom属性转换数组
     * @param \DOMElement $domElement
     * @return array
     */
    private function domAttrToArray(\DOMElement $domElement): array
    {
        return [
            // 每个相册缩略图的大小
            'dataSize' => $domElement->getAttribute('data-size'),
            // 相册id
            'albumId' => $domElement->getAttribute('data-id'),
            // 相册名称
            'name' => $domElement->getAttribute('data-name'),
            // 相册描述
            'desc' => $domElement->getAttribute('data-description'),
            // 是否私有 public、private、password
            'dataPrivacy' => $domElement->getAttribute('data-privacy'),
            // 相册路径
            'urlShort' => $domElement->getAttribute('data-url-short'),
            // 缩略图路径
            'dataThumb' => $domElement->getAttribute('data-thumb')
        ];
    }
}
