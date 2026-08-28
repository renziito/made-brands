<?php

/**
 * This is the model class for table "faq_form_submission_values".
 *
 * The followings are the available columns in table 'faq_form_submission_values':
 * @property string $id
 * @property string $submission_id
 * @property string $field_id
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property FaqFormFields $field
 * @property FaqFormSubmissions $submission
 */
class FaqFormSubmissionValues extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'faq_form_submission_values';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('submission_id, field_id, created_at, updated_at', 'required', 'message' => '{attribute} no debe estar vacio.'),
			array('submission_id, field_id', 'length', 'max'=>10),
			array('value', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, submission_id, field_id, value, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'field' => array(self::BELONGS_TO, 'FaqFormFields', 'field_id'),
			'submission' => array(self::BELONGS_TO, 'FaqFormSubmissions', 'submission_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'submission_id' => 'Submission',
			'field_id' => 'Field',
			'value' => 'Value',
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
		$criteria->compare('submission_id',$this->submission_id,true);
		$criteria->compare('field_id',$this->field_id,true);
		$criteria->compare('value',$this->value,true);
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
	 * @return FaqFormSubmissionValues the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
