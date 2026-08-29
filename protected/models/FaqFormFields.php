<?php

/**
 * This is the model class for table "faq_form_fields".
 *
 * The followings are the available columns in table 'faq_form_fields':
 * @property string $id
 * @property string $form_id
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $placeholder
 * @property string $default_value
 * @property string $options
 * @property integer $is_required
 * @property integer $sort_order
 * @property integer $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property FaqForms $form
 * @property FaqFormSubmissionValues[] $faqFormSubmissionValues
 */
class FaqFormFields extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'faq_form_fields';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('form_id, name, label, type, created_at, updated_at', 'required'),
			array('is_required, sort_order, is_active', 'numerical', 'integerOnly'=>true),
			array('form_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>100),
			array('label, placeholder', 'length', 'max'=>255),
			array('type', 'length', 'max'=>50),
			array('default_value, options', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, form_id, name, label, type, placeholder, default_value, options, is_required, sort_order, is_active, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'form' => array(self::BELONGS_TO, 'FaqForms', 'form_id'),
			'faqFormSubmissionValues' => array(self::HAS_MANY, 'FaqFormSubmissionValues', 'field_id'),
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
			'name' => 'Name',
			'label' => 'Label',
			'type' => 'Type',
			'placeholder' => 'Placeholder',
			'default_value' => 'Default Value',
			'options' => 'Options',
			'is_required' => 'Is Required',
			'sort_order' => 'Sort Order',
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
		$criteria->compare('form_id',$this->form_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('placeholder',$this->placeholder,true);
		$criteria->compare('default_value',$this->default_value,true);
		$criteria->compare('options',$this->options,true);
		$criteria->compare('is_required',$this->is_required);
		$criteria->compare('sort_order',$this->sort_order);
		$criteria->compare('is_active',$this->is_active);
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
	 * @return FaqFormFields the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
