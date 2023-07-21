<?php

if (!function_exists('getMillisecond')) {
    /**
     * 获取毫秒时间戳
     * @return float
     */
    function getMillisecond(): float
    {
        list($microsecond, $time) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($microsecond) + floatval($time)) * 1000);
    }
}

if (!function_exists('unlinkFile')) {
    /**
     * 删除文件
     * @param string $filePath
     * @return bool
     */
    function unlinkFile(string $filePath): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }
        return unlink($filePath);
    }
}

if (!function_exists('filePutDataLock')) {

    /**
     * 写入文件
     * @param string $filePath
     * @param string $content
     * @return false|int
     */
    function filePutDataLock(string $filePath, string $content)
    {
        $dir = dirname($filePath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        return file_put_contents($filePath, $content, \LOCK_EX);
    }
}

if (!function_exists('tgJsonDecodeFun')) {

    /**
     * @param string $content
     * @return mixed
     * @throws \Ctlynl\Tgpic\Exception\TGJsonDecodeErrorException
     */
    function tgJsonDecodeFun(string $content)
    {
        $decode = json_decode($content, true);
        if (json_last_error() != 0) {
            throw new \Ctlynl\Tgpic\Exception\TGJsonDecodeErrorException(json_last_error_msg());
        }
        return $decode;
    }
}
