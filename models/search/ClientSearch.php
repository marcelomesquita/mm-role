<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Client;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class ClientSearch extends Client
{
	public $checkin_day;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id'], 'integer'],
			[['email', 'name', 'cpf', 'sex', 'checkin_day'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
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
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = Client::find();

		$query->joinWith(['checkins']);

		$query->groupBy(['client.id']);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => ['name' => SORT_ASC]
			]
		]);

		$this->load($params);

		if (!$this->validate()) {
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere(['client.id' => $this->id]);
		$query->andFilterWhere(['like', 'client.name', $this->name]);
		$query->andFilterWhere(['like', 'client.cpf', $this->cpf]);
		$query->andFilterWhere(['like', 'client.email', $this->email]);
		$query->andFilterWhere(['client.sex' => $this->sex]);
		$query->andFilterWhere(['like', 'checkin.checkin', $this->checkin_day]);

		return $dataProvider;
	}
}
