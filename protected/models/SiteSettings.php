<?php

/**
 * This is the model class for table "site_settings".
 *
 * The followings are the available columns in table 'site_settings':
 * @property string $id
 * @property string $setting_key
 * @property string $setting_value
 * @property string $setting_type
 * @property string $setting_size
 * @property string $created_at
 * @property string $updated_at
 */
class SiteSettings extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'site_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('setting_key, created_at, updated_at', 'required', 'message' => '{attribute} no debe estar vacio.'),
			array('setting_key', 'length', 'max'=>100),
			array('setting_type', 'length', 'max'=>30),
			array('setting_size', 'length', 'max'=>20),
			array('setting_value', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, setting_key, setting_value, setting_type, setting_size, created_at, updated_at', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'setting_key' => 'Setting Key',
			'setting_value' => 'Setting Value',
			'setting_type' => 'Setting Type',
			'setting_size' => 'Setting Size',
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
		$criteria->compare('setting_key',$this->setting_key,true);
		$criteria->compare('setting_value',$this->setting_value,true);
		$criteria->compare('setting_type',$this->setting_type,true);
		$criteria->compare('setting_size',$this->setting_size,true);
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
	 * @return SiteSettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
