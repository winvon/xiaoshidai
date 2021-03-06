<?php

namespace backend\models;

use common\helpers\ConstantHelper;
use common\helpers\FamilyTree;
use common\helpers\Param;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%nav}}".
 *
 * @property int $id 自增id
 * @property int $source 菜单类型
 * @property int $parent_id 上级菜单id
 * @property string $nav_name 名称
 * @property string $route_url route_url地址
 * @property string $icon 图标
 * @property double $display_order 排序
 * @property int $is_show 是否显示.0否,1是
 * @property int is_delete 是否显示.0否,1是
 * @property int $is_lock 默认0未冻结，1冻结，冻结提示暂未开放
 * @property int $created_at 创建时间
 * @property int $updated_at 最后修改时间
 */
class Nav extends \backend\models\BaseModel
{

    const BACKEND_TYPE = 'manage';
    const IOS_TYPE = 'ios';
    const ANDROID_TYPE = 'android';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%nav}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nav_name'], 'required'],
            [['parent_id', 'is_show', 'is_lock', 'is_delete', 'created_at', 'updated_at'], 'integer'],
            ['parent_id', 'default', 'value' => 0],
            ['display_order', 'default', 'value' => 0],
            ['is_show', 'default', 'value' => ConstantHelper::MENU_IS_DISPLAY_TRUE],
            ['source', 'default', 'value' => 'manage'],
            ['source', 'in', 'range' => ['manage', 'ios', 'android']],
            [['display_order'], 'number'],
            [['route_url'], 'trim'],
            [['nav_name', 'route_url', 'icon'], 'string', 'max' => 255],
//            [
//                'route_url',
//                'match',
//                'pattern' => '/^[\/].*/',
//                'message' => Yii::t('app', Yii::t('app', '必须以 /开头， 例: /module/controller/action '))
//            ],
        ];
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(),
            [
                'show' => ['is_show'],
                'sort' => ['display_order'],
                'lock' => ['is_lock'],
                'delete' => ['is_delete'],
            ]); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'source' => '渠道',
            'parent_id' => '上级菜单id',
            'nav_name' => '名称',
            'route_url' => 'url地址',
            'icon' => '图标',
            'display_order' => '排序',
            'is_show' => '是否显示.0否,1是',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 处理返回的数据格式
     * @return array
     */
    public function getView()
    {
        return [
            'id' => $this->id,
            'nav_name' => $this->nav_name,
            'source' => $this->source,
            'parent_id' => $this->parent_id,
            'parent_nav_name' => empty($this->parent) ? "" : $this->parent->nav_name,
            'route_url' => $this->route_url,
            'icon' => $this->icon,
            'display_order' => $this->display_order,
            'is_show' => $this->is_show,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }


    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
        if (!$this->getIsNewRecord()) {
            if ($this->id == $this->parent_id) {
                $this->addError('parent_id', '不允许当前菜单作为父级菜单');
                return false;
            }
            $familyTree = new FamilyTree(Nav::_getMenus($this->source));
            $descendants = $familyTree->getDescendants($this->id);
            $descendants = ArrayHelper::getColumn($descendants, 'id');
            if (in_array($this->parent_id, $descendants)) {
                $this->addError('parent_id', '其子菜单不允许作为父级菜单');
                return false;
            }
        }
    }

    /**
     * @param bool $insert
     * @author von
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if ($this->isNewRecord) {
            $this->created_at = time();
            $this->updated_at = time();
        } else {
            $this->updated_at = time();
        }
        return true;
    }

    /**
     * @param $type
     * @return array|\yii\db\ActiveRecord[]
     */
    static function _getMenus($type = null)
    {
        static $menus = null;
        if ($menus === null)
            $menus = self::find()
                ->filterWhere(['source' => $type])
                ->andWhere(['is_delete' => ConstantHelper::IS_DELETE_FALSE])
                ->orderBy("display_order asc,parent_id asc")
                ->asArray()
                ->all();
        return $menus;
    }

    /**
     * 获取菜单伪树形结构
     * @param int $type
     * @return array
     * @author von
     */
    public static function getMenus($type = null)
    {
        $menus = self::_getMenus($type);
        $familyTree = new FamilyTree($menus);
        $array = $familyTree->getDescendants(0);
        foreach ($array as $k => &$menu) {
            if (isset($menus[$k + 1]['level']) && $menus[$k + 1]['level'] == $menu['level']) {
                $name = ' ├' . $menu['nav_name'];
            } else {
                $name = ' └' . $menu['nav_name'];
            }
            if (end($menus) == $menu) {
                $sign = ' └';
            } else {
                $sign = ' │';
            }
            $menu['treename'] = str_repeat($sign, $menu['level'] - 1) . $name;
        }
        return ArrayHelper::index($array, 'id');
    }

    /**
     * 添加时，获取父id
     * @param int $type
     * @return array
     * @author von
     */
    public static function getMenusName($type = null)
    {
        $menus = self::_getMenus(null);
        $array = [];
        foreach ($menus as $value) {
            $array[] = [
                'id' => $value['id'],
                'parent_id' => $value['parent_id'],
                'label' => $value['nav_name'],
                'value' => $value['id'],
            ];
        }
        $familyTree = new FamilyTree([]);
        $menus = $familyTree->ListToTree($array);
        return $menus;
    }


    /**
     * @return bool
     * @author von
     */
    public function beforeDelete()
    {
        $menus = Nav::_getMenus($this->source);
        $familyTree = new FamilyTree($menus);
        $subs = $familyTree->getDescendants($this->id);
        if (!empty($subs)) {
            $this->addError('id', '此菜单还有子级菜单，不能删除');
            return false;
        }
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     * @author von
     */
    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    /**
     * 查询sql
     * @param $params
     * @return $this
     * @author von
     */
    public function getQuery($params)
    {
        $query = Nav::getMenus(Nav::BACKEND_TYPE);
        $this->load($params);
        $temp = explode('\\', self::className());
        $temp = end($temp);
        if (isset($params[$temp])) {
            $searchArr = $params[$temp];
            foreach ($searchArr as $k => $v) {
                if ($v !== '') {
                    foreach ($query as $key => $val) {
                        if (in_array($k, ['display_order', 'display'])) {
                            if ($val[$k] != $v) {
                                unset($query[$key]);
                            }
                        } else {
                            if (strpos($val[$k], $v) === false) {
                                unset($query[$key]);
                            }
                        }
                    }
                }
            }
        };
        return $query;
    }

    /**
     * 通过id查询数据
     * @param $id
     * @return bool|null|static
     */
    public function findOneById($id)
    {
        $model = self::findOne(['id' => $id]);
        if ($model) {
            return $model;
        }
        return false;
    }

    /**
     * 新增菜单
     * @param $data
     * @return array|bool
     * @author von
     */
    public function createMenu($data)
    {
        $model = new self();
        $model->attributes = $data;
        if ($model->validate() && $model->save()) {
            return true;
        }
        return $model->getErrors();
    }

    /**
     * 修改菜单
     * @param $data
     * @return array|bool
     * @author von
     */
    public function modifyMenu($data)
    {
        $this->attributes = $data;
        if ($this->validate() && $this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * 获取数据列
     * @param $params
     * @return array
     */
    public function getList($params)
    {
        $query = self::getQuery($params);
        $list = [];
        foreach ($query as $value) {
            unset($value['treename']);
            unset($value['is_delete']);
            $list[] = $value;
        }
        $familyTree = new FamilyTree([]);
        $list = $familyTree->ListToTree($list);
        $param[ConstantHelper::COUNT] = count($list);
        $param[ConstantHelper::LISTS] = $list;
        return self::backListFormat($param);
    }

    /**
     * app菜单
     * @param string $source
     * @return array|\yii\db\ActiveRecord[]
     * @author von
     */
    public function getAppNav($source=ConstantHelper::MENU_SOURCE_ANDROID)
    {
        $models = self::find()
            ->select('id,nav_name,route_url,icon,is_lock')
            ->where(['is_delete' => ConstantHelper::IS_DELETE_FALSE])
            ->andWhere(['is_show' => ConstantHelper::MENU_IS_DISPLAY_TRUE])
            ->andWhere(['source' => $source])
            ->orderBy("display_order asc,parent_id asc")
            ->asArray()
            ->all();
        return $models;
    }

    /**
     * 管理系统菜单
     * @param int $id
     * @return mixed
     * @author von
     */
    public function getManageNav($id)
    {
        $models = self::find()
            ->where(['is_delete' => ConstantHelper::IS_DELETE_FALSE])
            ->andWhere(['is_show' => ConstantHelper::MENU_IS_DISPLAY_TRUE])
            ->andWhere(['source' => ConstantHelper::MENU_SOURCE_MANAGE])
            ->andWhere(['parent_id' => 0])
            ->orderBy("display_order asc,parent_id asc")
            ->all();
        $list = [];
        foreach ($models as $model) {
            $children = self::getChildrenList($model->id);
            $row = [
                'id' => $model->id,
                'nav_name' => $model->nav_name,
                'route_url' => $model->route_url,
                'icon' => $model->icon,
                'is_lock' => $model->is_lock,
                'children' => $children
            ];
//            if (empty($children)) unset($row['children']);
            $list[] = $row;
        }
        return $list;

        $models = self::find()
            ->select('id,nav_name,route_url,icon,is_lock,parent_id')
            ->where(['is_delete' => ConstantHelper::IS_DELETE_FALSE])
            ->andWhere(['is_show' => ConstantHelper::MENU_IS_DISPLAY_TRUE])
            ->andWhere(['source' => ConstantHelper::MENU_SOURCE_MANAGE])
            ->orderBy("display_order asc,parent_id asc")
            ->asArray()
            ->all();
        $familyTree = new FamilyTree($models);
//      return  $familyTree->ListToTree($models);
        $empModel = Emp::findOne(['id' => $id]);
        $auth = $empModel->getAuth();//权限
        $auths = ArrayHelper::getColumn($auth, 'route_url');
        $tree = [];
        $dataArr = $models;//菜单
        if (is_array($dataArr)) {
            //1.0 创建基于主键的数组引用
            $referList = [];
            $_ids = '';//搜集菜单id;
            foreach ($dataArr as $key => $sorData) {
                if (in_array($sorData['route_url'], $auths)) {//总会有路由
                    $ancectors = $familyTree->getAncectors($sorData['id']);
                    $_ids .= implode(',', ArrayHelper::getColumn($ancectors, 'id')) . ',' . $sorData['id'] . ',';
                }
            }
            $ids = explode(',', $_ids);
            $ids = array_filter(array_unique($ids));
            foreach ($dataArr as $key => & $sorData) {
                if (!in_array($sorData['id'], $ids)) {
                    unset($dataArr[$key]);
                    continue;
                }
                $referList[$sorData['id']] =& $dataArr[$key];
            }
            foreach ($dataArr as $key => $data) {
                $pId = $data['parent_id'];
                if (0 == $pId) //一级
                {
                    $tree[] =& $dataArr[$key];
                } else //多级
                {
                    if (isset($referList[$pId])) {
                        if (in_array($data['id'], $ids)) {
                            $pNode =& $referList[$pId];
                            $pNode['children'][] =& $dataArr[$key];
                        }
                    }
                }
            }
        }
        return $tree;
    }


    /**
     * 找子级
     * @param int $parent_id
     * @return array
     * @author von
     */
    public static function getChildrenList($parent_id = 0)
    {
        $model = self::findOne($parent_id);
        $list = [];
        $childs = $model->children;//找到子集
        if (!empty($childs)) {
            $children = [];
            foreach ($childs as $child) {
                if (!empty($child->children)) {
                    $children = self::getChildrenList($child->id);
                }
                $row = [
                    'id' => $child->id,
                    'nav_name' => $child->nav_name,
                    'route_url' => $child->route_url,
                    'icon' => $child->icon,
                    'is_lock' => $child->is_lock,
                    'children' => $children
                ];
                if (empty($children)) {
//                    unset($row['children']);
                } else {
                    unset($children);
                };
                $list[] = $row;;
            }
        }
        return $list;
    }

    /**
     * @return \yii\db\ActiveQuery
     * @author von
     */
    public function getChildren()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'id'])
            ->where(['is_delete' => ConstantHelper::IS_DELETE_FALSE])
            ->andWhere(['is_show' => ConstantHelper::MENU_IS_DISPLAY_TRUE]);
    }

    /**
     * 设置菜单是否显示
     * @return array|bool
     * @author von
     */
    public function setMenuShowById()
    {
        $this->scenario = 'show';
        if ($this->is_show == ConstantHelper::MENU_IS_DISPLAY_TRUE) {
            $this->is_show = ConstantHelper::MENU_IS_DISPLAY_FALSE;
        } else {
            $this->is_show = ConstantHelper::MENU_IS_DISPLAY_TRUE;
        }
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * 冻结菜单是否显示
     * @return array|bool
     * @author von
     */
    public function setMenuLockById()
    {
        $this->scenario = 'lock';
        if ($this->is_lock == ConstantHelper::IS_LOCK_FALSE) {
            $this->is_lock = ConstantHelper::IS_LOCK_TRUE;
        } else {
            $this->is_lock = ConstantHelper::IS_LOCK_FALSE;
        }
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }


    /**
     * 设置菜单排序
     * @param $data
     * @return array|bool
     * @author von
     */
    public function setMenuSortById($data)
    {
        $this->scenario = 'sort';
        $this->attributes = $data;
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * 删除菜单
     * @param $data
     * @return array|bool
     * @author von
     */
    public function delById()
    {
        $menus = Nav::_getMenus($this->source);
        $familyTree = new FamilyTree($menus);
        $subs = $familyTree->getDescendants($this->id);
        if (!empty($subs)) {
            $this->addError('id', '此菜单还有子级菜单，不能删除');
            return $this->getErrors();
        }
        $this->scenario = 'delete';
        $this->is_delete = ConstantHelper::IS_DELETE_TRUE;
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * 根据menu id获取祖先菜单
     * @param string $id 菜单id
     * @return array
     */
    public static function getAncectorsByMenuId($id)
    {
        $menus = self::_getMenus(self::BACKEND_TYPE);
        $familyTree = new FamilyTree($menus);
        return $familyTree->getAncectors($id);
    }

    /**
     * 根据menu id获取子孙菜单
     * @param string $id 菜单id
     * @return array
     */
    public static function getDescendantsByMenuId($id)
    {
        $menus = self::_getMenus(self::BACKEND_TYPE);
        $familyTree = new FamilyTree($menus);
        return $familyTree->getDescendants($id);
    }


}
