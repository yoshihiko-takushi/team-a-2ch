<?php
/**
 * Created by PhpStorm.
 * User: kazumatamaki
 * Date: 2016/05/13
 * Time: 19:54
 */

require('../_util/DbUtil.php');


$dbUtil = new DbUtil();
$threadData = $dbUtil->selectByThreadId(1);

?>
<html>
    <head>
        <title>テスト</title>
    </head>
<body>
    <h1>テスト</h1>
    <p><?php var_dump($threadData) ?></p>
</body>
</html>
