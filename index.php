<?php
/**
 * Created by PhpStorm.
 * User: kazumatamaki
 * Date: 2016/05/13
 * Time: 21:23
 */
require('./_util/Paginate.php');
require('./_util/ViewUtil.php');

$paginate = new Paginate();
$threads = $paginate->paginate('threads');
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Team A 2ch</title>
</head>
<body>
<h1>スレッド一覧 <span>全<?= ViewUtil::h($paginate->getTotal()) ?>件</span></h1>
<ul id="thread-list">
    <?php foreach ($threads as $thread): ?>
        <li>
            <a href="/thread.php?id=<?= ViewUtil::h($thread['id']) ?>">
                <span><?= ViewUtil::h($thread['threads_name']) ?></span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<div id="paginate-section">
    <?php if ($paginate->getPage() > 1) : ?>
        <a href="?page=<?php echo $paginate->getPage() - 1; ?>">前</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $paginate->getTotalPages(); $i++) : ?>
        <?php if ($paginate->getPage() == $i) : ?>
            <strong><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></strong>
        <?php else: ?>
            <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>
    <?php if ($paginate->getPage() < $paginate->getTotalPages()) : ?>
        <a href="?page=<?php echo $paginate->getPage() + 1; ?>">次</a>
    <?php endif; ?>
</div>
</body>
</html>
