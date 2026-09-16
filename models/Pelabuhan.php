<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pelabuhan".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $province
 * @property string|null $created_at
 */
class Pelabuhan extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pelabuhan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['province'], 'default', 'value' => null],
            [['code', 'name'], 'required'],
            [['created_at'], 'safe'],
            [['code'], 'string', 'max' => 50],
            [['name', 'province'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'province' => 'Province',
            'created_at' => 'Created At',
        ];
    }

}
