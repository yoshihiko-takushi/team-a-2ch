<?php

/**
 * Created by PhpStorm.
 * User: kazumatamaki
 * Date: 2016/05/13
 * Time: 19:43
 */
class DbUtil
{
    private $dbHost = '127.0.0.1';
    private $dbName = 'a-team-2ch';
    private $user = 'root';
    private $password = 'testTamashiro2015';
    private $charset = 'utf8';
    private $dbh;
    private $pdo;

    /**
     * DbUtil constructor.
     */
    public function __construct()
    {
        $this->dbh = "mysql:dbname=$this->dbName;host=$this->dbHost;charset=$this->charset";
        $this->init();
    }

    /**
     * DBへ接続する$pdoのオブジェクトを作成
     */
    private function init()
    {
        try {
            $this->pdo = new PDO(
                $this->dbh,
                $this->user,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * table名を受け取り、そのデータすべて取得
     * @param $tableName
     * @return array|bool
     */
    public function selectAll($tableName)
    {
        try {
            $query = $this->pdo->query("SELECT * FROM $tableName");
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
        return $data;
    }

    /**
     * スレッドテーブルを検索する
     * @param $threadId
     * @return array
     */
    public function selectByThreadId($threadId)
    {
        $data = [];
        // 空チェックをしている。threadsIdがわたらない場合のチェック
        if ($threadId === '') {
            echo 'threadIDを渡してください';
            return $data;
        }

        // DB関連はエラーが多いと思うので、try { } catch () {}で、エラーをキャッチできるようにする
        // try {} の中には、エラーが発生すると思われる処理を書く
        // catch() {} は、エラーが発生したときにこの中になる。エラーが発生した場合にリプレイスなどの処理を書く
        // 要はエラーが発生したときに白い画面を見せないようにするために配慮です。
        try {
            // SQLをまずは書く。この時直接SQL文に変数を渡さないでプレースホルダーという手法を取る
            // これを使わないとエスケープしてくれない。いわゆるSQLインジェクションを許してしまう
            $stmt = $this->pdo->prepare('SELECT * FROM threads WHERE id = :threadId');

            // ここで、変数と先ほどの":threadId"をバインド（紐付け）している
            // 第一引数がプレースホルダーで用意した名前（':threadId'）
            // 第二引数が渡したい値・変数名（$threadId）
            // 第三引数は無くても良いが、型を決めて渡したい場合は使う。今回は数値なのでInt型として渡すことを定義している
            $stmt->bindValue(':threadId', $threadId, PDO::PARAM_INT);

            // ここで渡したSQL文を実行している
            $stmt->execute();

            // 実行した後、値を取り出すのがfetch()とfetchAll()。全部取得したい場合はfetchAll()を使う
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            $e->getMessage();
        }
        return $data;
    }

    /**
     * コメントテーブルを検索する
     * @param $commentId
     * @return array
     */
    public function selectByCommentId($commentId)
    {
        $data = [];
        if ($commentId === '') {
            echo 'threadIDを渡してください';
            return $data;
        }
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM threads WHERE id = :threadId');
            $stmt->bindValue(1, $commentId, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            $e->getMessage();
        }
        return $data;
    }

    /**
     * スレッドを削除するメソッド
     * @param $threadId
     * @param $deleteKey
     */
    public function deleteByThreadId($threadId, $deleteKey)
    {
        $threadRecord = $this->selectByThreadId($threadId);
        // もらったthreadIdでDBにデータが無かった場合
        if (empty($threadRecord)) {
            return false;
        }

        $execDeleteKey = $threadRecord['delete_key'];
        if ($deleteKey === $execDeleteKey) {
            // delete keyがマッチすれば削除
            try {
                $stmt = $this->pdo->prepare('DELETE FROM threads WHERE id = :threadId');
                $stmt->bindValue(':threadId', $threadId, PDO::PARAM_INT);
                $stmt->execute();
            } catch (Exception $e) {
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
            // マッチしない場合
            return false;
        }
    }
}