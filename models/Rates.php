<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "{{%rates}}".
 *
 * @property int $id
 * @property string $code
 * @property float $rate
 * @property string $name
 * @property string $date
 *
 */
class Rates extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%rates}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['rate'], 'number'],
            [['date'], 'safe'],
            [['code', 'name'], 'string'],
        ];
    }

    public function exists($date, $code): bool
    {
        return Rates::find()->where(['date' => $date, 'code' => $code])->exists();
    }

    public function store($date, $code, $name, $rate)
    {
        try {
            $this->date = $date;
            $this->code = $code;
            $this->name = $name;
            $this->rate = $rate;
            $this->save();
        } catch (Exception $e) {
            \Yii::error($e->getMessage(), 'RatesModel:save');
        }
    }
}
