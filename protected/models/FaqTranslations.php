<?php

/**
 * This is the model class for table "faq_translations".
 *
 * The followings are the available columns in table 'faq_translations':
 * @property string $id
 * @property string $faq_id
 * @property string $language_id
 * @property string $question
 * @property string $question_size
 * @property string $answer
 * @property string $answer_size
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
			array('faq_id, language_id, question, answer, created_at, updated_at', 'required', 'message' => '{attribute} no debe estar vacio.'),
			array('faq_id, language_id', 'length', 'max'=>10),
			array('question_size, answer_size', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, faq_id, language_id, question, question_size, answer, answer_size, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'language_id' => 'Language',
			'question' => 'Question',
			'question_size' => 'Question Size',
			'answer' => 'Answer',
			'answer_size' => 'Answer Size',
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
		$criteria->compare('language_id',$this->language_id,true);
		$criteria->compare('question',$this->question,true);
		$criteria->compare('question_size',$this->question_size,true);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('answer_size',$this->answer_size,true);
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
