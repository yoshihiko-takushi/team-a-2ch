<?php

/**
 * Created by PhpStorm.
 * User: kazumatamaki
 * Date: 2016/05/13
 * Time: 19:43
 */
class DbUtil
{
    private $dbHost = 'localhost';
    private $dbName = 'a-team-2ch';
    private $user = 'root';
    private $password = 'testTamashiro2015';
    private $charset = 'utf8';
    private $unixSocket = '/tmp/mysql.sock';
    private $dbh;
    private $pdo;

    /**
     * DbUtil constructor.
     */
    public function __construct()
    {
        $this->dbh = "mysql:dbname=$this->dbName;unix_socket=$this->unixSocket;host=$this->dbHost;charset=$this->charset";
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
     *
     * @param $tableName
     * @return array|bool
     */
    public function selectAll($tableName)
    {
        $sql = "SELECT * FROM $tableName";
        return $this->executeQuery($sql);
    }

    /**
     * ページネーションを実行する
     * @param $tableName
     * @param int $offset
     * @param int $count
     * @return bool
     */
    public function paginate($tableName, $offset, $count)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $tableName limit :offset, :count");
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':count', $count, PDO::PARAM_INT);
        return $this->executeStatement($stmt);
    }

    /**
     * @param $tableName
     * @return bool
     */
    public function selectAllCount($tableName)
    {
        $sql = "SELECT COUNT(*) as count FROM $tableName";
        return $this->executeFirst($sql);
    }

    /**
     * sqlを実行するメソッド
     * @param $sql
     * @return bool
     */
    public function executeFirst($sql)
    {
        try {
            $query = $this->pdo->query($sql);
            $data = $query->fetchColumn();
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
        return $data;
    }

    /**
     * sqlを実行するメソッド
     * @param $sql
     * @return bool
     */
    public function executeQuery($sql)
    {
        try {
            $query = $this->pdo->query($sql);
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
        return $data;
    }

    /**
     * @param $stmt
     * @return bool
     */
    public function executeStatement($stmt)
    {
        try {
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_BOTH);
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
            echo 'commentIDを渡してください'; // fix me
            return $data;
        }
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM comments WHERE id = :commentId');
            $stmt->bindValue(':commentId', $commentId, PDO::PARAM_INT);
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

        $execDeleteKey = $threadRecord[0]['delete_key'];
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

    /**
     * コメントを削除するメソッド
     * @param $threads_id
     * @param $deleteKey
     */
    public function deleteByCommentId($threadId, $commentDeleteId, $commentDeleteKey)
    {
        $threadRecord = $this->selectByThreadId($threadId);
        // もらったthreadIdでDBにデータが無かった場合
        if (empty($threadRecord)) {
            return false;
        }

        $commentId = $this->selectByCommentId($commentDeleteId);
        if (empty($commentId)) {
            return false;
        }

        $commentsThreadId = $commentId[0]['threads_id'];
        if ($commentsThreadId != $threadId) {
            return false;
        }

        $execDeleteKey = $commentId[0]['delete_key'];
        if ($commentDeleteKey === $execDeleteKey) {
            // delete keyがマッチすれば削除
            try {
                $stmt = $this->pdo->prepare('DELETE FROM comments WHERE id = :commentId');
                $stmt->bindValue(':commentId', $commentDeleteId, PDO::PARAM_INT);
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

    public function insertComment($threadsId, $nickName, $comment, $deleteKey, $created)
    {
        try {
            $this->pdo->beginTransaction();
            $sql = "INSERT INTO comments(threads_id,comment,nickname,delete_key,created)VALUES(:thredsIdData,:commentData,:niknameData,:deleteKey,:created)";
            $stmh = $this->pdo->prepare($sql);
            $stmh->bindValue(':thredsIdData', $threadsId);
            $stmh->bindValue(':niknameData', $nickName);
            $stmh->bindValue(':commentData', $comment);
            $stmh->bindValue(':deleteKey', $deleteKey);
            $stmh->bindValue(':created', $created);
            $stmh->execute();
            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            echo('Error:' . $e->getMessage());
            return false;
        }
        return true;
    }

    public function deleteComment($threadsId, $commentsId, $deleteKey)
    {


        try {

            $delete = "DELETE FROM comments WHERE id = :commentsId AND delete_key = :deleteKey";
            $stmh = $this->pdo->prepare($delete);
            $stmh->bindValue(':commentsId', $commentsId);
            $stmh->bindValue(':deleteKey', $deleteKey);
            $stmh->execute();


        } catch (PDOException $e) {
            echo('Error:' . $e->getMessage());
            return false;
        }
        return true;
    }

    public function getThredsData($threadsId)
    {
        $threadsData = [];
        try {
            $threadsData = $this->pdo->prepare("select threads.id as threads_id, threads.threads_name, threads.delete_key, threads.created, comments.id as comments_id, comments.comment,  comments.unique_id, comments.nickname, comments.delete_key, comments.created from threads inner join comments on (threads.id = comments.threads_id) where threads.id = :threadsId");
            $threadsData->bindValue(':threadsId', $threadsId);
            $threadsData->execute();
            $threadsData = $threadsData->fetchAll();
        } catch (PDOException $e) {
            echo('Error:' . $e->getMessage());
            return false;
        }
        return $threadsData;

    }


}
