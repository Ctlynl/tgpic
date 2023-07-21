<?php

namespace Ctlynl\Tgpic\Params;

use Ctlynl\Tgpic\Exception\TGInvalidParameterException;

/**
 * @\Ctlynl\Tgpic\Params\TGGetAlbumsParams
 */
class TGGetAlbumsParams implements TGRequestParamInterface
{

    private array $headers;

    /**
     * @var string
     */
    private string $page;

    /**
     * sort
     * @var string
     */
    private string $sort;

    /**
     * 每次获取分页都要传递，数据返回的字段seekEnd字段
     * @var string
     */
    private string $seek;

    /**
     * 1页24条信息
     * @param string $page
     */
    public function setPage(string $page): void
    {
        $this->page = $page;
    }

    /**
     * @param string $sort 参数值为：name_asc name_desc date_desc date_asc
     * @throws TGInvalidParameterException
     */
    public function setSort(string $sort): void
    {
        $filter = ['name_asc', 'name_desc', 'date_desc', 'date_asc'];
        if (!in_array($sort, $filter)) {
            throw new TGInvalidParameterException('排序错误' . implode(',', $filter));
        }
        $this->sort = $sort;
    }

    /**
     * 每次获取分页都要传递，第一页不用填写，从第二页开始每次从数据返回的字段seekEnd值直接填入本字段
     * @param string $seek
     */
    public function setSeek(string $seek): void
    {
        $this->seek = $seek;
    }

    public function setHeaders(array $options)
    {
        $this->headers = $options;
    }

    public function getHeaders(): array
    {
        return $this->headers ?? [];
    }

    public function getRequestData(): array
    {
        return [
            'action' => 'list',
            'list' => 'albums',
            'sort' => $this->sort ?? 'name_asc',
            'page' => $this->page ?? 1,
            'from' => 'user',
            'seek' => $this->seek ?? '',
            'params_hidden' => [
                'list' => 'albums',
                'from' => 'user',
                'params_hidden' => ''
            ],
        ];
    }
}
