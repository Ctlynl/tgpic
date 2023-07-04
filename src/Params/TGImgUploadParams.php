<?php

namespace Ctlynl\Tgpic\Params;

class TGImgUploadParams implements TGRequestParamInterface
{

    /**
     * file 上传文件
     * @var string
     */
    private string $type;

    /**
     * 动作 upload
     * @var string
     */
    private string $action;

    /**
     * 毫秒时间戳
     * @var string
     */
    private string $timestamp;

    /**
     * 标记为不健康
     * @var string
     */
    private string $nsfw;

    /**
     * 相册id
     * @var string
     */
    private string $albumId;

    /**
     * 上传的文件
     * @var string
     */
    private string $source;

    /**
     * 请求头
     * @var array
     */
    private array $headers;

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    /**
     * @param string $timestamp
     */
    public function setTimestamp(string $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @param string $nsfw
     */
    public function setNsfw(string $nsfw): void
    {
        $this->nsfw = $nsfw;
    }

    /**
     * @param string $albumId
     */
    public function setAlbumId(string $albumId): void
    {
        $this->albumId = $albumId;
    }

    /**
     * @param string $source
     */
    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    public function getRequestData(): array
    {
        return [
            'type' => $this->type,
            'action' => $this->action,
            'timestamp' => $this->timestamp,
            'nsfw' => $this->nsfw,
            'album_id' => $this->albumId,
            'source' => ['file' => $this->source]
        ];
    }

    /**
     * @inheritDoc
     */
    public function setHeaders(array $options)
    {
        $this->headers = $options;
    }

    public function getHeaders(): array
    {
        return $this->headers ?? [];
    }
}
