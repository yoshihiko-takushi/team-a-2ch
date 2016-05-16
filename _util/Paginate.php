<?php
require_once('DbUtil.php');

/**
 * Created by PhpStorm.
 * User: kazumatamaki
 * Date: 2016/05/15
 * Time: 16:11
 */
class Paginate
{
    private $page = 1;
    private $contentsPerPage = 5;
    private $dbUtilObject;
    private $totalPages = 0;
    private $recordTotal = 0;
    private $from = 0;
    private $to = 0;

    public function __construct()
    {
        $this->dbUtilObject = new DbUtil();
        $this->setPage();
    }

    private function setPage()
    {
        $page = filter_input(INPUT_GET, 'page');
        if ($page !== '' && preg_match('/^[1-9][0-9]*$/', $page)) {
            $this->page = intval($page);
        }
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->recordTotal;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @param $tableName
     * @return bool
     */
    public function paginate($tableName)
    {
        $offset = $this->contentsPerPage * ($this->page - 1);
        $searchResultArray = $this->dbUtilObject->paginate($tableName, $offset, $this->contentsPerPage);

        // totalを取得
        $total = $this->dbUtilObject->selectAllCount($tableName);
        $this->setTotalPages($total);
        $this->setTotal($total);
        $this->setFrom($offset);
        $this->setTo($offset, $total);

        return $searchResultArray;
    }

    private function setFrom($offset)
    {
        $this->from = $offset + 1;;
    }

    private function setTo($offset, $total)
    {
        $this->to = ($offset + $this->contentsPerPage) < $total ? ($offset + $this->contentsPerPage) : $total;
    }

    private function setTotal($total)
    {
        $this->recordTotal = $total;
    }

    private function setTotalPages($total)
    {
        $this->totalPages = ceil($total / $this->contentsPerPage);
    }
}
