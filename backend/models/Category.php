<?php

namespace backend\models;

use common\helpers\ConstantHelper;
use common\helpers\FamilyTree;
use common\helpers\Param;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%category}}".
 * @property int $id
 * @property int type
 * @property string $category_name
 * @property int category_type
 * @property string $category_icon
 * @property int $display_order
 * @property int $parent_id
 * @property int $is_show
 * @property int $is_delete
 * @property int $created_at
 * @property int $updated_at
 */
class Category extends \backend\models\BaseModel
{
    const SHOW = 0;
    const SHOW_NOT = 1;

    public static $_is_show = null;
    public static $_category_type = null;
    public static $_type = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_name', 'type', 'category_type'], 'required'],
            [['display_order', 'parent_id', 'is_show', 'is_delete'], 'integer'],
            [['category_name'], 'string', 'max' => 50],
            ['is_show', 'default', 'value' => self::SHOW],
            ['display_order', 'default', 'value' => 0],
            ['parent_id', 'default', 'value' => 0],
            ['category_icon', 'default', 'value' => ''],
//            ['category_name', 'checkUnique'],
            ['is_delete', 'default', 'value' => self::DELETE_NOT],
            [['category_icon', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * rules 验证category_name唯一
     * @param $attribute
     * @param $param
     */
    public function checkUnique($attribute, $param)
    {
        $model = $this->findOneByCategoryName($this->$attribute);
        if ($model) {
            if ($model->id != $this->id && $model->is_delete == self::DELETE_NOT) {
                $this->addError($attribute, $this->$attribute . '已存在');
            }
        }
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
//            'update' => ['category_name', 'category_icon', 'display_order', 'parent_id', 'is_show'],
            'delete' => ['is_delete'],
            'show' => ['is_show'],
            'sort' => ['display_order'],
        ]); // TODO: Change the autogenerated stub
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '类别',
            'category_name' => '分类名称',
            'category_type' => '分类类别',
            'category_icon' => '图标',
            'display_order' => '显示排序',
            'parent_id' => '父ID',
            'is_show' => '是否显示',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
     * 通过category_name查询数据
     * @param $category_name
     * @return bool|null|static
     */
    public function findOneByCategoryName($category_name)
    {
        $model = self::findOne(['category_name' => $category_name]);
        if ($model) {
            return $model;
        }
        return false;
    }

    /**
     * 处理返回的数据格式
     * @return array
     */
    public function getView()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'category_name' => $this->category_name,
            'category_type' => $this->category_type,
            'category_icon' => Html::encode($this->category_icon),
            'parent_category_icon' => $this->parent_id == null ? '' : Html::encode($this->parent->category_icon),
            'parent_category_name' => $this->parent_id == null ? '' : Html::encode($this->parent->category_name),
            'parent_id' => $this->parent_id,
            'display_order' => $this->display_order,
            'is_show' => $this->is_show,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }


    /**
     * 创建产品分类
     * @param $data
     * @return array|bool
     */
    public function createCategory($data)
    {
        $model = new self();
        $model->attributes = $data;
        if ($model->validate() && $model->save()) {
            return true;
        }
        return $model->getErrors();
    }

    /**
     * 处理保存前数据格式
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->is_delete = self::DELETE_NOT;
            $this->created_at = time();
            $this->updated_at = time();
        } else {
            $this->updated_at = time();
        }
        return parent::beforeSave($insert);
    }


    /**
     * @param $type
     * @return array|\yii\db\ActiveRecord[]
     */
    protected static function _getMenus($type)
    {
        static $menus = null;
        if ($menus === null) $menus = self::find()->where(['type' => $type])->andWhere(['is_delete' => ConstantHelper::IS_DELETE_FALSE])->orderBy("display_order asc,parent_id asc")->asArray()->all();
        return $menus;
    }

    /**
     * @return bool
     * @author von
     */
    public function afterValidate()
    {
        if (!$this->getIsNewRecord()) {
            if ($this->id == $this->parent_id) {
                $this->addError('parent_id', '不允许当前菜单作为父级菜单');
                return false;
            }
            $familyTree = new FamilyTree(self::_getMenus($this->type));
            $descendants = $familyTree->getDescendants($this->id);
            $descendants = ArrayHelper::getColumn($descendants, 'id');
            if (in_array($this->parent_id, $descendants)) {
                $this->addError('parent_id', '其子菜单不允许作为父级菜单');
                return false;
            }
            $brother = self::find()
                ->where(['is_delete' => ConstantHelper::IS_DELETE_FALSE])
                ->andWhere(['parent_id' => $this->parent_id])
                ->andWhere(['not', ['id' => $this->id]])
                ->one();
        } else {
            $brother = self::find()
                ->where(['is_delete' => ConstantHelper::IS_DELETE_FALSE])
                ->andWhere(['parent_id' => $this->parent_id])
                ->one();
        }; // TODO: Change the autogenerated stub
        if ((int)$this->parent_id != 0) {
            $parent = self::findOne($this->parent_id);
            if ($parent == null) {
                $this->addError('parent_id', '父级不存在');
                return false;
            }
            if ($this->type != $parent->type) {
                $this->addError('type', '子级' . $this->attributeLabels()['type'] . '与父级' . $this->attributeLabels()['type'] . '保持一致');
                return false;
            }
            if ($brother != null) {
                if ($this->category_type != $brother->category_type) {
                    $this->addError('category_type', '子级' . $this->attributeLabels()['category_type'] . '与同级' . $this->attributeLabels()['category_type'] . '保持一致');
                    return false;
                }
            }
        }
    }

    /**
     * 获取查询数据sql
     * @param $params
     * @return $this
     */
    public function getQuery($params)
    {
        return $query = self::find()
            ->where(['is_delete' => self::DELETE_NOT])
            ->andFilterWhere(['like', 'category_name', $params['category_name']])
            ->andFilterWhere(['parent_id' => $params['parent_id']])
            ->andFilterWhere(['category_type' => $params['category_type']])
            ->andFilterWhere(['type' => $params['type']]);
    }

    /**
     * 修改产品分类
     * @param $params
     * @return array|bool
     */
    public function modifyCategory($params)
    {
        //有子级 type,category_type 不支持修改
        if (!empty($params['type']) && (int)$this->parent_id == 0) {
            if ($this->children != null && $params['type'] != $this->type) {
                $this->addError('type', '已有子级,' . $this->attributeLabels()['type'] . '不支持修改');
            }
        }
        if (!empty($this->getErrors())) return $this->getErrors();
        $this->attributes = $params;
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * 获取数据列表
     * @param $params
     * @return array
     */
    public function getList($params)
    {
        $page_size = Param::getParamFromGet('page_size');
        $page = Param::getParamFromGet('page');
        $query = self::getQuery($params);
        $models = $query->limit($page_size)
            ->offset(($page - 1) * $page_size)
            ->orderBy('created_at DESC')
            ->all();
        $list = [];
        foreach ($models as $model) {
            $list[] = $model->getView();
        }
        $count = $query->count();
        $param[ConstantHelper::COUNT] = $count;
        $param[ConstantHelper::LISTS] = $list;
        return self::backListFormat($param);
    }

    /**
     * @param $params
     * @return array
     * @author von
     */
    public function getListByTree($params)
    {
        $models = self::find()
            ->select('id as value,category_name as label,category_icon,parent_id')
            ->where(['is_delete' => self::DELETE_NOT])
            ->andWhere(['is_show' => ConstantHelper::IS_SHOW_TRUE])
            ->andFilterWhere(['type' => $params['type']])
            ->andFilterWhere(['category_type' => $params['category_type']])
            ->asArray()
            ->all();
        $familyTree=new FamilyTree([]);
        $tree=$familyTree->ListToTree($models,0,'value');
        return $tree;
    }


    /**
     * 与父级对应关系
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    /**
     * 与子级对应关系
     * @param null $is_show
     * @param null $type
     * @param null $category_type
     * @return $this
     * @author von
     */
    public function getChildren()
    {
        $models = $this->hasMany(self::className(), ['parent_id' => 'id'])
            ->where(['is_delete' => ConstantHelper::IS_DELETE_FALSE])
            ->andFilterWhere(['is_show' => self::$_is_show])
            ->andFilterWhere(['type' => self::$_type])
            ->andFilterWhere(['category_type' => self::$_category_type]);
        return $models;
    }

    /**
     * 删除分类
     * @return array|bool
     */
    public function del()
    {
        /*有子级，拒绝删除*/
        $model = self::find()
            ->where(['is_delete' => self::DELETE_NOT])
            ->andWhere(['parent_id' => $this->id])
            ->one();
        if ($model != null) {
            $this->addError('category_name', '有子级分类,不支持删除');
            return $this->getErrors();
        }
        $this->scenario = 'delete';
        $this->is_delete = self::DELETED;
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * 显示与隐藏产品分类
     * is_show 数据处理为反；若为0，这处理为1
     * @return array|bool
     */
    public function showCategory()
    {
        $this->scenario = 'show';
        if ($this->is_show == self::SHOW) {
            $this->is_show = self::SHOW_NOT;
        } else {
            $this->is_show = self::SHOW;
        }
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

    /**
     * 分类排序
     * @return array|bool
     */
    public function sort($param)
    {
        $this->scenario = 'sort';
        $this->attributes = $param;
        if ($this->save()) {
            return true;
        }
        return $this->getErrors();
    }

}
