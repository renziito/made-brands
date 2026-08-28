<?php

/**
 * This is the model class for table "contact_cta_translations".
 *
 * The followings are the available columns in table 'contact_cta_translations':
 * @property string $id
 * @property string $contact_cta_id
 * @property string $language_id
 * @property string $title
 * @property string $title_size
 * @property string $text
 * @property string $text_size
 * @property string $button_text
 * @property string $button_text_size
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property ContactCta $contactCta
 * @property Languages $language
 */
class ContactCtaTranslations extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contact_cta_translations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('contact_cta_id, language_id, title, created_at, updated_at', 'required', 'message' => '{attribute} no debe estar vacio.'),
			array('contact_cta_id, language_id', 'length', 'max'=>10),
			array('title, button_text', 'length', 'max'=>255),
			array('title_size, text_size, button_text_size', 'length', 'max'=>20),
			array('text', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, contact_cta_id, language_id, title, title_size, text, text_size, button_text, button_text_size, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'contactCta' => array(self::BELONGS_TO, 'ContactCta', 'contact_cta_id'),
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
			'contact_cta_id' => 'Contact Cta',
			'language_id' => 'Language',
			'title' => 'Title',
			'title_size' => 'Title Size',
			'text' => 'Text',
			'text_size' => 'Text Size',
			'button_text' => 'Button Text',
			'button_text_size' => 'Button Text Size',
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
		$criteria->compare('contact_cta_id',$this->contact_cta_id,true);
		$criteria->compare('language_id',$this->language_id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('title_size',$this->title_size,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('text_size',$this->text_size,true);
		$criteria->compare('button_text',$this->button_text,true);
		$criteria->compare('button_text_size',$this->button_text_size,true);
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
	 * @return ContactCtaTranslations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
