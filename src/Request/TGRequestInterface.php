<?php

namespace Ctlynl\Tgpic\Request;

use Ctlynl\Tgpic\Params\TGRequestParamInterface;

/**
 * @\Ctlynl\Tgpic\TGClient
 */
interface TGRequestInterface
{

    /**
     * @param TGRequestParamInterface|null $requestParam
     * @return mixed
     * @throws \Ctlynl\Tgpic\Exception\TGException | \GuzzleHttp\Exception\GuzzleException
     */
    public function execute(TGRequestParamInterface $requestParam = null);
}
