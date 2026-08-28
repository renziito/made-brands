<?php

/**
 * This is the model class for table "contact_item_translations".
 *
 * The followings are the available columns in table 'contact_item_translations':
 * @property string $id
 * @property string $contact_item_id
 * @property string $language_id
 * @property string $label
 * @property string $label_size
 * @property string $value
 * @property string $value_size
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property ContactItems $contactItem
 * @property Languages $language
 */
class ContactItemTranslations extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contact_item_translations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('contact_item_id, language_id, label, value, created_at, updated_at', 'required', 'message' => '{attribute} no debe estar vacio.'),
			array('contact_item_id, language_id', 'length', 'max'=>10),
			array('label', 'length', 'max'=>255),
			array('label_size, value_size', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, contact_item_id, language_id, label, label_size, value, value_size, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'contactItem' => array(self::BELONGS_TO, 'ContactItems', 'contact_item_id'),
			'language' => array(self::BELONGS_TO, 'Languages', 'language_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'contact_item_id' => 'Contact Item',
			'language_id' => 'Language',
			'label' => 'Label',
			'label_size' => 'Label Size',
			'value' => 'Value',
			'value_size' => 'Value Size',
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
		$criteria->compare('contact_item_id',$this->contact_item_id,true);
		$criteria->compare('language_id',$this->language_id,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('label_size',$this->label_size,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('value_size',$this->value_size,true);
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
	 * @return ContactItemTranslations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
