<?php

namespace nuke2015\api\base;

use nuke2015\api\config;

class MongoDB
{
    private $_manager;
    private $_host;
    private $_username;
    private $_password;
    private $_db;

    public function __construct($_db)
    {
        $this->_db = $_db;
        $connStr = config\mongodb::config();
        $this->_manager = new \MongoDB\Driver\Manager($connStr);
    }

    public function getInstense()
    {
        return $this->_manager;
    }
    public function getDB()
    {
        return $this->_db;
    }
    public function getBulk()
    {
        return  new \MongoDB\Driver\BulkWrite;
    }
    public function getWriteConcern()
    {
        new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    }


    /**
     * 插入数据
     * @param $db 数据库名
     * @param $collection 集合名
     * @param $document 数据 json格式
     * @return
     */
    public function insert($collection, $document)
    {
        $bulk = $this->getBulk();
        $write_concern = $this->getWriteConcern();

        $document = json_decode($document, 1);
        $document['_id'] = new \MongoDB\BSON\ObjectID;
        $bulk->insert($document);

        return $this->_manager->executeBulkWrite($this->_db . '.' . $collection, $bulk, $write_concern);
    }

    /**
     * 插入数据
     * @param $db 数据库名
     * @param $collection 集合名
     * @param $document 数据 json格式
     * @return
     */
    public function insertMany($collection, $document)
    {
        $bulk = $this->getBulk();
        $write_concern = $this->getWriteConcern();

        $document = json_decode($document, 1);
        foreach ($document as $val) {
            $val['_id'] = new \MongoDB\BSON\ObjectID;
            $bulk->insert($val);
        }

        return $this->_manager->executeBulkWrite($this->_db . '.' . $collection, $bulk, $write_concern);
    }


    /**
     * 删除数据
     * @param array $where
     * @param array $option
     * @param string $db
     * @param string $collection
     * @return mixed
     */
    public function delete($collection, $where = array(), $option = array())
    {
        $bulk = $this->getBulk();
        $bulk->delete($where, $option);
        return $this->_manager->executeBulkWrite($this->_db . '.' . $collection, $bulk);
    }

    /**
     * 更新数据
     * @param array $where 类似where条件
     * @param array $field  要更新的字段
     * @param bool $upsert 如果不存在是否插入，默认为false不插入
     * @param bool $multi 是否更新全量，默认为false
     * @param string $db   数据库
     * @param string $collection 集合
     * @return mixed
     */
    public function update($collection, $where = array(), $field = array(), $upsert = false, $multi = false)
    {
        if (empty($where)) {
            return 'filter is null';
        }
        if (isset($where['_id'])) {
            $where['_id'] = new \MongoDB\BSON\ObjectId($where['_id']);
        }
        $bulk = $this->getBulk();
        $write_concern = $this->getWriteConcern();
        $bulk->update($where, $field, $upsert, $multi);
        $res = $this->_manager->executeBulkWrite($this->_db . '.' . $collection, $bulk, $write_concern);
        if (empty($res->getWriteErrors())) {
            return true;
        } else {
            return false;
        }
    }


    public function selectById($collection, $id, $options = array())
    {
        $filter = ['_id' => new \MongoDB\BSON\ObjectID($id)];
        $res = $this->query($collection, $filter, $options);
        foreach ($res as $item) {
            $data = $this->objToArray($item);
        }
        return $data;
    }

    public function query($collection, $filter, $options)
    {
        $query = new \MongoDB\Driver\Query($filter, $options);
        $res = $this->_manager->executeQuery($this->_db . '.' . $collection, $query);
        $data = array();
        foreach ($res as $item) {
            $tmp = $this->objToArray($item);
            $tmp['_id'] = $tmp['_id']['$oid'];
            $data[] = $tmp;
        }
        return $data;
    }

    /**
     * 执行MongoDB命令
     * @param array $param
     * @return \MongoDB\Driver\Cursor
     */
    public function command(array $param)
    {
        $cmd = new \MongoDB\Driver\Command($param);
        return $this->_manager->executeCommand($this->_db, $cmd);
    }

    /**
     * 按条件计算个数
     *
     * @param string $collName 集合名
     * @param array $where 条件
     * @return int
     */
    public function count($collName, array $where)
    {
        $result = 0;
        $cmd = [
            'count' => $collName,
            'query' => $where
        ];
        $arr = $this->command($cmd)->toArray();
        if (!empty($arr)) {
            $result = $arr[0]->n;
        }
        return $result;
    }


    /**
     * 聚合查询
     * @param $collName
     * @param array $where
     * @param array $group
     * @return \MongoDB\Driver\Cursor
     */
    public function aggregate($collName, array $where, array $group)
    {
        $cmd = [
            'aggregate' => $collName,
            'pipeline' => [
                ['$match' => $where],
                ['$group' => $group]
            ]
        ];
        //print_r($cmd);exit();
        $result = $this->command($cmd)->toArray();
        // print_r($result);exit();
        return $result[0]->result;
    }

    /**
     * 同mysql中的distinct功能
     *
     * @param string $collName collection名
     * @param string $key 要进行distinct的字段名
     * @param array $where 条件
     * @return array
     * Array
     * (
     * [0] => 1.0
     * [1] => 1.1
     * )
     */
    public function distinct($collName, $key, array $where)
    {
        $result = [];
        $cmd = [
            'distinct' => $collName,
            'key' => $key,
            'query' => $where
        ];
        $arr = $this->command($cmd)->toArray();
        if (!empty($arr)) {
            $result = $arr[0]->values;
        }
        return $result;
    }

    public function objToArray($data)
    {
        return json_decode(json_encode($data), true);
    }
}
