<?php

namespace Ctlynl\Tgpic;

use Ctlynl\Tgpic\Exception\TGInvalidParameterException;

class TGPicTest extends \PHPUnit\Framework\TestCase
{
    public function testTGConfig()
    {
        $tgConfig = new TGConfig();
        $tgConfig->globalHeaders = [];
        $tgConfig->authTokenFilePathName = 'xxx';
        $tgConfig->cookieContextFilePathName = 'xxx';

        // 断言会抛出此异常类
        $this->expectException(TGInvalidParameterException::class);
        new TGClient($tgConfig);
    }
}
