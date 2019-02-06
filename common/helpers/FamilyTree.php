<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2017-07-24 17:21
 */

namespace common\helpers;


use yii\helpers\ArrayHelper;

class FamilyTree
{

    /**
     * @var array
     */
    private $_tree;

    private $_parentSign = "parent_id";


    /**
     * FamilyTree constructor.
     *
     * @param array $tree
     */
    public function __construct(array $tree)
    {
        $this->_tree = $tree;
    }

    /**
     * @return array
     */
    public function getTree()
    {
        return $this->_tree;
    }

    /**
     * @param array $tree
     * @return FamilyTree
     */
    public function setTree($tree)
    {
        $this->_tree = $tree;
        return $this;
    }

    /**
     * @return string
     */
    public function getParentSign()
    {
        return $this->_parentSign;
    }

    /**
     * @param string $parentSign
     * @return FamilyTree
     */
    public function setParentSign($parentSign)
    {
        $this->_parentSign = $parentSign;
        return $this;
    }

    /**
     * 获取某节点的所有子节点
     *
     * @param $id
     * @return array
     */
    public function getSons($id)
    {
        $sons = [];
        foreach ($this->_tree as $key => $value) {
            if ($value[$this->_parentSign] == $id) {
                $sons[] = $value;
            }
        }
        return $sons;
    }

    /**
     * 获取某节点的所有子孙节点
     * @param $id
     * @param int $level
     * @return array
     */
    public function getDescendants($id, $level = 1)
    {
        $nodes = [];
        foreach ($this->_tree as $key => $value) {
            if ($value[$this->_parentSign] == $id) {
                $value['level'] = $level;
                $nodes[] = $value;
                $nodes = array_merge($nodes, $this->getDescendants($value['id'], $level + 1));
            }
        }
        return $nodes;
    }

    /**
     * 获取某节点的所有父节点
     *
     * @param $id
     * @return array
     */
    public function getParents($id)
    {
        $nodes = [];
        $tree = ArrayHelper::index($this->_tree, 'id');
        foreach ($tree as $key => $value) {
            if ($tree[$id][$this->_parentSign] == $value['id']) {
                $nodes[] = $value;
            }
        }
        return $nodes;
    }

    /**
     * 获取某节点的所有祖先节点
     * @param $id
     * @return array
     */
    public function getAncectors($id)
    {
        $array = $this->_getAncectors($id);
        if( isset($array[0]) ) unset($array[0]);
        return $array;
    }

    /**
     * 递归获取祖先节点
     *
     * @param $id
     * @return array
     */
    private function _getAncectors($id)
    {
        $nodes = [];
        foreach ($this->_tree as $key => $value) {
            if ($value['id'] == $id) {
                $nodes[] = $value;
                if ($value[$this->_parentSign] != 0) {
                    $nodes = array_merge($nodes, $this->_getAncectors($value[$this->_parentSign]));
                }
            }
        }
        return $nodes;
    }


    /**
     * 数据列表转换成树
     *
     * @param  array   $dataArr   数据列表
     * @param  integer $rootId    根节点ID
     * @param  string  $pkName    主键
     * @param  string  $pIdName   父节点名称
     * @param  string  $childName 子节点名称
     * @return array  转换后的树
     */
    public  function  ListToTree($dataArr, $rootId = 0, $pkName = 'id', $pIdName = 'parent_id', $childName = 'children')
    {
        $tree = [];
        if (is_array($dataArr))
        {
            //1.0 创建基于主键的数组引用
            $referList  = [];
            foreach ($dataArr as $key => & $sorData)
            {
                $referList[$sorData[$pkName]] =& $dataArr[$key];
            }
            //2.0 list 转换为 tree
            foreach ($dataArr as $key => $data)
            {
                $pId = $data[$pIdName];
                if ($rootId == $pId) //一级
                {
                    $tree[] =& $dataArr[$key];
                }
                else //多级
                {
                    if (isset($referList[$pId]))
                    {
                         $pNode               =& $referList[$pId];
                         $pNode[$childName][] =& $dataArr[$key];
                    }
                }
            }
        }
        return $tree;
    }

}