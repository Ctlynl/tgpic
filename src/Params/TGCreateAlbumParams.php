<?php

namespace Ctlynl\Tgpic\Params;

/**
 * @\Ctlynl\Tgpic\Params\TGCreateAlbumParams
 */
class TGCreateAlbumParams implements TGRequestParamInterface
{
    private string $albumName;

    private string $description;

    private string $privacy;

    private string $password;

    private array $headers;

    public function setHeaders(array $options)
    {
        $this->headers = $options;
    }

    public function getHeaders(): array
    {
        return $this->headers ?? [];
    }

    /**
     * @param string $albumName
     */
    public function setAlbumName(string $albumName): void
    {
        $this->albumName = $albumName;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $privacy
     */
    public function setPrivacy(string $privacy): void
    {
        $this->privacy = $privacy;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @inheritDoc
     */
    public function getRequestData(): array
    {
        return [
            'action' => 'create-album',
            'type' => 'album',
            'album' => [
                'new' => "true",
                'name' => $this->albumName,
                'description' => $this->description,
                'privacy' => $this->privacy,
                'password' => $this->password ?? '',
            ]
        ];
    }
}
