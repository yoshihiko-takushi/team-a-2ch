<?php
/**
 * Created by PhpStorm.
 * User: kazumatamaki
 * Date: 2016/05/13
 * Time: 21:23
 */
require('./_util/DbUtil.php');
require('./_util/ViewUtil.php');

$dbUtil = new DbUtil();
$threads = $dbUtil->paginate('threads');
$threadsAllCount = $dbUtil->selectAllCount('threads');
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Team A 2ch</title>
</head>
<body>
<h1>スレッド一覧 <span>全<?= ViewUtil::h($threadsAllCount['count']) ?>件</span></h1>
<ul id="thread-list">
    <?php foreach ($threads as $thread): ?>
        <li>
            <a href="/thread.php?id=<?= ViewUtil::h($thread['id']) ?>">
                <span><?=ViewUtil::h($thread['threads_name'])?></span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>
