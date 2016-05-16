<?php

/**
 * Created by PhpStorm.
 * User: kazumatamaki
 * Date: 2016/05/13
 * Time: 21:45
 */
class ViewUtil
{
    public function __construct()
    {
    }

    /**
     * 文字通りエスケープ
     * @param $v
     * @return string
     */
    public static function h($v) {
        return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    }
}