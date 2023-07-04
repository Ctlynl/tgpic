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