<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cuaca;

/**
 * CuacaSearch represents the model behind the search form of `app\models\Cuaca`.
 */
class CuacaSearch extends Cuaca
{
    public $nama_pelabuhan;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'visibility', 'temp_avg', 'rh_avg', 'wind_speed', 'wind_gust'], 'integer'],
            [['code_pelabuhan', 'issued', 'valid_from', 'valid_to', 'time', 'weather', 'wind_from', 'wave_cat', 'current_to', 'created_at', 'updated_at'], 'safe'],
            [['wave_height', 'current_speed', 'tides'], 'number'],
            [['nama_pelabuhan'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Cuaca::find()->joinWith(['pelabuhan']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20, // Menampilkan 20 data per halaman (bisa disesuaikan)
            ],
            'sort' => [
                'defaultOrder' => ['time' => SORT_DESC],
            ],
        ]);

        $dataProvider->sort->attributes['nama_pelabuhan'] = [
            'asc' => ['pelabuhan.name' => SORT_ASC],
            'desc' => ['pelabuhan.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'issued' => $this->issued,
            'valid_from' => $this->valid_from,
            'valid_to' => $this->valid_to,
            'time' => $this->time,
            'visibility' => $this->visibility,
            'temp_avg' => $this->temp_avg,
            'rh_avg' => $this->rh_avg,
            'wind_speed' => $this->wind_speed,
            'wind_gust' => $this->wind_gust,
            'wave_height' => $this->wave_height,
            'current_speed' => $this->current_speed,
            'tides' => $this->tides,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code_pelabuhan', $this->code_pelabuhan])
            ->andFilterWhere(['like', 'weather', $this->weather])
            ->andFilterWhere(['like', 'wind_from', $this->wind_from])
            ->andFilterWhere(['like', 'wave_cat', $this->wave_cat])
            ->andFilterWhere(['like', 'current_to', $this->current_to])
            ->andFilterWhere(['like', 'pelabuhan.name', $this->nama_pelabuhan]);

        return $dataProvider;
    }
}
