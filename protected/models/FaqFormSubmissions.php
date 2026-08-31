<?php

/**
 * This is the model class for table "faq_form_submissions".
 *
 * The followings are the available columns in table 'faq_form_submissions':
 * @property string $id
 * @property string $form_id
 * @property string $ip_address
 * @property string $user_agent
 * @property string $estado
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property FaqFormSubmissionValues[] $faqFormSubmissionValues
 * @property FaqForms $form
 */
class FaqFormSubmissions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'faq_form_submissions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('form_id, created_at, updated_at', 'required', 'message' => '{attribute} no debe estar vacio.'),
			array('form_id', 'length', 'max'=>10),
			array('ip_address, estado', 'length', 'max'=>45),
			array('user_agent', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, form_id, ip_address, user_agent, estado, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'faqFormSubmissionValues' => array(self::HAS_MANY, 'FaqFormSubmissionValues', 'submission_id'),
			'form' => array(self::BELONGS_TO, 'FaqForms', 'form_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'form_id' => 'Form',
			'ip_address' => 'Ip Address',
			'user_agent' => 'User Agent',
			'estado' => 'Estado',
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
		$criteria->compare('form_id',$this->form_id,true);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('user_agent',$this->user_agent,true);
		$criteria->compare('estado',$this->estado,true);
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
	 * @return FaqFormSubmissions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
