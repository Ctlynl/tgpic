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
        $reqArray = $this->mergeFromParams($requestParam);
        // 获取userid
        $userId = $this->getUserId();
        $reqArray['userid'] = $userId;
        $reqArray['params_hidden']['userid'] = $userId;
        // 获取数据内容
        $response = $this->request('POST', '/json', ['form_params' => $reqArray]);
        $arrayContent = tgJsonDecodeFun($response->getBody()->getContents());
        // 处理返回的html文本
        $crawler = new Crawler($arrayContent['html']);
        $albums = [];
        $crawler->filterXPath('//div[@data-type="album"]')->each(function (Crawler $node) use (&$albums) {
            //div[@class="list-item-from font-size-small"] 精确匹配
            //contains模糊匹配，表示选择 id 中包含“stu”的所有 div 节点。
            $imgCount = $node->filterXPath('//div[contains(@class,"list-item-from")]')->text();
            $albumValue = $this->domAttrToArray($node, $imgCount);
            array_push($albums, $albumValue);
        });
        return [
            'list' => $albums,
            'request' => $arrayContent['request'],
            'seekEnd' => $arrayContent['seekEnd']
        ];
    }

    /**
     * dom属性转换数组
     * @param Crawler $node
     * @param string $imgCount
     * @return array
     */
    private function domAttrToArray(Crawler $node, string $imgCount): array
    {
        return [
            // 相册id
            'albumId' => $node->attr('data-id'),
            // 相册名称
            'name' => $node->attr('data-name'),
            // 相册描述
            'desc' => $node->attr('data-description'),
            // 是否私有 public、private、password
            'dataPrivacy' => $node->attr('data-privacy'),
            // 相册路径
            'urlShort' => $node->attr('data-url-short'),
            // 每个相册缩略图的大小
            'dataSize' => $imgCount != 0 ? $node->attr('data-size') : 0,
            // 缩略图路径
            'dataThumb' => $imgCount != 0 ? $node->attr('data-thumb') : '',
            // 图片数量
            'imgCount' => $imgCount
        ];
    }
}
