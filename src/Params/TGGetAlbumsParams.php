<?php

namespace Ctlynl\Tgpic\Params;

/**
 * @\Ctlynl\Tgpic\Params\TGGetAlbumsParams
 */
class TGGetAlbumsParams implements TGRequestParamInterface
{

    private array $headers;

    private string $userName;

    /**
     * @param string $userName
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
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
            'userName' => $this->userName
        ];
    }
}
