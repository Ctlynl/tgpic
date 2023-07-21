<?php

namespace Ctlynl\Tgpic\Params;

use Ctlynl\Tgpic\Exception\TGInvalidParameterException;

class TGDeleteAlbumParams implements TGRequestParamInterface
{

    private array $headers;

    private bool $isMultiple;

    private array $albumIds;

    public function setHeaders(array $options)
    {
        $this->headers = $options;
    }

    public function getHeaders(): array
    {
        return $this->headers ?? [];
    }

    /**
     * @param array $albumIds
     */
    public function setAlbumIds(array $albumIds): void
    {
        $this->albumIds = $albumIds;
        $this->isMultiple = count($albumIds) > 1;
    }

    /**
     * @throws TGInvalidParameterException
     */
    public function getRequestData(): array
    {
        if (empty($this->albumIds)) {
            throw new TGInvalidParameterException('参数错误需设置相册ids');
        }
        if ($this->isMultiple) {
            return $this->getMultipleData();
        }
        return [
            'action' => 'delete',
            'single' => 'true',
            'delete' => 'albums',
            'deleting' => [
                'id' => $this->albumIds[0]
            ]
        ];
    }

    /**
     * 删除多个相册参数
     * @return array
     */
    private function getMultipleData(): array
    {
        return [
            'action' => 'delete',
            'from' => 'list',
            'delete' => 'albums',
            'multiple' => 'true',
            'deleting' => [
                'ids' => $this->albumIds
            ]
        ];
    }
}
