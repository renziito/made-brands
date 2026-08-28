<?php

/**
 * This is the model class for table "contact_cta".
 *
 * The followings are the available columns in table 'contact_cta':
 * @property string $id
 * @property string $icon
 * @property string $url
 * @property integer $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property ContactCtaTranslations[] $contactCtaTranslations
 */
class ContactCta extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contact_cta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created_at, updated_at', 'required', 'message' => '{attribute} no debe estar vacio.'),
			array('is_active', 'numerical', 'integerOnly'=>true,'message' => '{attribute} solo debe ser numeros.'),
			array('icon', 'length', 'max'=>100),
			array('url', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, icon, url, is_active, created_at, updated_at', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'contactCtaTranslations' => array(self::HAS_MANY, 'ContactCtaTranslations', 'contact_cta_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'icon' => 'Icon',
			'url' => 'Url',
			'is_active' => 'Is Active',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('icon',$this->icon,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('is_active',$this->is_active);

		$criteria->addCondition('is_active = TRUE','AND');
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContactCta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
