<?php

namespace Ctlynl\Tgpic\Params;

class TGAuthLoginParams implements TGRequestParamInterface
{

    private string $loginSubject;

    private string $password;

    private array $headers;

    /**
     * @param string $loginSubject
     */
    public function setLoginSubject(string $loginSubject): void
    {
        $this->loginSubject = $loginSubject;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRequestData(): array
    {
        return [
            'login-subject' => $this->loginSubject,
            'password' => $this->password,
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
