<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\imagine\Image;

/**
 * This is the model class for table "Client".
 *
 * @property integer $id
 * @property string $email
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property integer $sex
 * @property date $birthday
 * @property string $cpf
 * @property boolean $cop
 * @property integer $cop_institution
 * @property integer $cop_register
 * @property string $cop_weapon
 * @property boolean $foreign
 * @property string $foreign_register
 * @property integer $foreign_register_type
 *
 * @property string $file write-only
 * @property string $checkin write-only
 */
class Client extends \yii\db\ActiveRecord
{
	// sex
	const SEX_MALE = 1;
	const SEX_FEMALE = 2;
	const SEX_UNDEFINED = 99;

	const SEX = [self::SEX_MALE => 'masculino', self::SEX_FEMALE => 'feminino', self::SEX_UNDEFINED => 'indefinido'];

	// cop institution
	const COP_CIVIL = 1;
	const COP_FEDERAL = 2;
	const COP_MILITARY = 3;
	const COP_OTHER = 99;

	const COP_INSTITUTIONS = [self::COP_CIVIL => 'Polícia Civil', self::COP_FEDERAL => 'Polícia Federal', self::COP_MILITARY => 'Polícia Militar', self::COP_OTHER => 'Outro'];

	// foreign register type
	const FOREIGN_PASSPORT = 1;
	const FOREIGN_OTHER = 99;

	const FOREIGN_REGISTER_TYPES = [self::FOREIGN_PASSPORT => 'Passaporte', self::FOREIGN_OTHER => 'Outro'];

	// write-only atributtes
	public $file;
	public $photo;
	public $checkin;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return '{{%client}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();

		$scenarios['create'] = ['name', 'phone', 'email', 'sex', 'birthday', 'cpf', 'cop', 'cop_institution', 'cop_register', 'cop_weapon', 'foreign', 'foreign_register', 'foreign_register_type', 'photo', 'checkin'];
		$scenarios['update'] = ['name', 'phone', 'email', 'sex', 'birthday', 'cpf', 'cop', 'cop_institution', 'cop_register', 'cop_weapon', 'foreign', 'foreign_register', 'foreign_register_type', 'photo', 'checkin'];

		return $scenarios;
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['name', 'photo'], 'required', 'on' => 'create'],
			[['name'], 'required', 'on' => 'update'],
			[['cpf'], 'required', 'when' => function($model) { return !$model->foreign; }, 'whenClient' => 'function (attribute, value) { return !$("#client-foreign").bootstrapSwitch("state") ? 1 : 0; }'],
			[['foreign_register'], 'required', 'when' => function($model) { return $model->foreign; }, 'whenClient' => 'function (attribute, value) { return $("#client-foreign").bootstrapSwitch("state") ? 1 : 0; }'],
			[['name', 'phone', 'email', 'cpf', 'cop_weapon', 'foreign_register'], 'string'],
			[['sex', 'cop_institution', 'cop_register', 'foreign_register_type'], 'integer'],
			[['checkin', 'cop', 'foreign'], 'boolean'],
			[['email'], 'email'],
			[['cpf'], 'validateCpf'],
			[['cpf'], 'unique', 'skipOnEmpty' => true],
			// [['file'], 'file', 'extensions' => ['jpg', 'jpeg'], 'mimeTypes' => ['image/jpg', 'image/jpeg'], 'maxSize' => 1024 * 1024 * 3],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'name' => 'Nome',
			'email' => 'E-mail',
			'phone' => 'Telefone',
			'sex' => 'Sexo',
			'birthday' => 'Data de Nascimento',
			'cpf' => 'CPF',
			'cop' => 'Policial',
			'cop_institution' => 'Instituição',
			'cop_register' => 'Matrícula',
			'cop_weapon' => 'Nº do Porte',
			'foreign' => 'Estrangeiro',
			'foreign_register' => 'Registro',
			'foreign_register_type' => 'Tipo de Registro',
			'file' => 'Foto',
			'photo' => 'Foto',
			'checkin_day' => 'Consulta Checkin',
			'lastCheckin' => 'Último Checkin',
		];
	}

	/**
	 * Validar CPF
	 *
	 * @link http://www.geradorcpf.com/script-validar-cpf-php.htm
	 *
	 * @param string $attribute
	 * @return boolean
	 */
	public function validateCpf($attribute)
	{
		if (!$this->hasErrors()) {
			$valid = true;
			$cpf = $this->cpf;

			if (strlen($cpf) != 11) {
				$valid = false;
			} else if (in_array($cpf, ['00000000000', '11111111111', '22222222222', '33333333333', '44444444444', '55555555555', '66666666666', '77777777777', '88888888888', '99999999999'])) {
				$valid = false;
			} else {
				for ($t = 9; $t < 11; $t++) {
					for ($d = 0, $c = 0; $c < $t; $c++) {
						$d += $cpf{$c} * (($t + 1) - $c);
					}

					$d = ((10 * $d) % 11) % 10;

					if ($cpf{$c} != $d) {
						$valid = false;
					}
				}
			}

			if (!$valid) {
				$this->addError($attribute, 'CPF inválido!');
			}
		}
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCheckins()
	{
		return $this->hasMany(Checkin::className(), ['id_client' => 'id']);
	}

	/**
	 * Return last checkin
	 */
	public function getLastCheckin()
	{
		return $this->hasOne(Checkin::className(), ['id_client' => 'id'])->onCondition(['last' => true]);
	}

	/**
	 * Realizar checkin
	 *
	 * @return bool
	 */
	public function doCheckin()
	{
		$now = Yii::$app->formatter->asDate(new \DateTime('NOW'), 'php:Y-m-d H:i:s');

		// atualizar checkin anterior
		Checkin::updateAll(['last' => false], ['last' => true, 'id_client' => $this->id]);

		// inserir novo checkin
		$checkin = new Checkin();

		$checkin->id_client = $this->id;
		$checkin->checkin = $now;
		$checkin->last = true;

		if ($checkin->save()) {
			Yii::$app->session->setFlash('success', 'Checkin realizado com sucesso!');

			return true;
		}

		return false;
	}

	/**
	 * Informa se o cliente já possui uma foto.
	 *
	 * @return bool
	 */
	public function hasPhotoBig()
	{
		return file_exists($this->filePath() . '/' . $this->fileNameBig());
	}

	/**
	 * Informa se o cliente já possui uma foto.
	 *
	 * @return bool
	 */
	public function hasPhotoThumb()
	{
		return file_exists($this->filePath() . '/' . $this->fileNameThumb());
	}

	/**
	 * Retorna o local onde os arquivos são armazenados.
	 *
	 * @return string
	 */
	public static function filePath()
	{
		return Yii::$aliases['@app'] . '/uploads';
	}

	/**
	 * Retorna o nome da foto grande.
	 *
	 * @return string
	 */
	public function fileNameBig()
	{
		return $this->id . '-big.jpg';
	}

	/**
	 * Retorna o nome da foto pequena.
	 *
	 * @return string
	 */
	public function fileNameThumb()
	{
		return $this->id . '-thumb.jpg';
	}

	/**
	 * @return boolean
	 */
	public function createPhotoBig()
	{
		if ($this->validate()) {
			if (preg_match('/^data:image\/(\w+);base64,/', $this->photo, $type)) {
				$data = substr($this->photo, strpos($this->photo, ',') + 1);
				$type = strtolower($type[1]); // jpg, png, gif

				if (!in_array($type, ['jpg', 'jpeg'])) {
					throw new \Exception('invalid image type');
				}

				$data = base64_decode($data);

				if ($data === false) {
					throw new \Exception('base64_decode failed');
				}
			} else {
				throw new \Exception('did not match data URI with image data');
			}

			return file_put_contents($this->filePath() . '/' . $this->fileNameBig(), $data);
		} else {
			return false;
		}
	}

	/**
	 * @return boolean
	 */
	public function createPhotoThumb()
	{
		if ($this->validate()) {
			return Image::thumbnail($this->filePath() . '/' . $this->fileNameBig(), 100, 100)->save($this->filePath() . '/' . $this->fileNameThumb());
		} else {
			return false;
		}
	}

	/**
	 * Finds Client by cpf
	 *
	 * @param string $cpf
	 * @return static|null
	 */
	public static function findIdentityByCpf($cpf)
	{
		return static::findOne(['cpf' => $cpf]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		if ($this->checkin) {
			$this->doCheckin();
		}
	}
}
