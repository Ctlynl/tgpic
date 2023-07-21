<?php

namespace Ctlynl\Tgpic\Traits;

use Ctlynl\Tgpic\Constant\Constant;
use Ctlynl\Tgpic\TGConfig;

/**
 * @\Ctlynl\Tgpic\TGStorageFileTrait
 */
trait TGStorageFileTrait
{

    /**
     * get AuthToken FilePath
     * @param TGConfig $config
     * @return string
     */
    protected function getAuthTokenFileName(TGConfig $config): string
    {
        return $config->getUserStoragePath() . DIRECTORY_SEPARATOR . $config->userName . Constant::AUTH_FILE;
    }

    /**
     * get cookie file name
     * @param TGConfig $config
     * @return string
     */
    protected function getCookieFileName(TGConfig $config): string
    {
        return $config->getUserStoragePath() . DIRECTORY_SEPARATOR . $config->userName . Constant::COOKIE_FILE;
    }

    /**
     * get cookie file name
     * @param TGConfig $config
     * @return string
     */
    protected function getUserIdFileName(TGConfig $config): string
    {
        return $config->getUserStoragePath() . DIRECTORY_SEPARATOR . $config->userName . Constant::USERID_FILE;
    }
}
