<?php

/**
 * This is the model class for table "faq_translations".
 *
 * The followings are the available columns in table 'faq_translations':
 * @property string $id
 * @property string $faq_id
 * @property string $form_text
 * @property string $form_id
 * @property string $language_id
 * @property string $question
 * @property string $answer
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Faqs $faq
 * @property Languages $language
 */
class FaqTranslations extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'faq_translations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('faq_id, language_id, question, answer, created_at, updated_at', 'required'),
			array('faq_id, form_id, language_id', 'length', 'max'=>10),
			array('form_text', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, faq_id, form_text, form_id, language_id, question, answer, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'faq' => array(self::BELONGS_TO, 'Faqs', 'faq_id'),
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
			'faq_id' => 'Faq',
			'form_text' => 'Form Text',
			'form_id' => 'Form',
			'language_id' => 'Language',
			'question' => 'Question',
			'answer' => 'Answer',
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
		$criteria->compare('faq_id',$this->faq_id,true);
		$criteria->compare('form_text',$this->form_text,true);
		$criteria->compare('form_id',$this->form_id,true);
		$criteria->compare('language_id',$this->language_id,true);
		$criteria->compare('question',$this->question,true);
		$criteria->compare('answer',$this->answer,true);
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
	 * @return FaqTranslations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
