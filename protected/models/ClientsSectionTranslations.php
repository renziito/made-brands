<?php

/**
 * This is the model class for table "clients_section_translations".
 *
 * The followings are the available columns in table 'clients_section_translations':
 * @property string $id
 * @property string $clients_section_id
 * @property string $language_id
 * @property string $eyebrow
 * @property string $eyebrow_size
 * @property string $title
 * @property string $title_size
 * @property string $text
 * @property string $text_size
 * @property string $brands_label
 * @property string $brands_label_size
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Languages $language
 * @property ClientsSection $clientsSection
 */
class ClientsSectionTranslations extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'clients_section_translations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('clients_section_id, language_id, created_at, updated_at', 'required', 'message' => '{attribute} no debe estar vacio.'),
			array('clients_section_id, language_id', 'length', 'max'=>10),
			array('eyebrow, brands_label', 'length', 'max'=>255),
			array('eyebrow_size, title_size, text_size, brands_label_size', 'length', 'max'=>20),
			array('title, text', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, clients_section_id, language_id, eyebrow, eyebrow_size, title, title_size, text, text_size, brands_label, brands_label_size, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'language' => array(self::BELONGS_TO, 'Languages', 'language_id'),
			'clientsSection' => array(self::BELONGS_TO, 'ClientsSection', 'clients_section_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'clients_section_id' => 'Clients Section',
			'language_id' => 'Language',
			'eyebrow' => 'Eyebrow',
			'eyebrow_size' => 'Eyebrow Size',
			'title' => 'Title',
			'title_size' => 'Title Size',
			'text' => 'Text',
			'text_size' => 'Text Size',
			'brands_label' => 'Brands Label',
			'brands_label_size' => 'Brands Label Size',
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
		$criteria->compare('clients_section_id',$this->clients_section_id,true);
		$criteria->compare('language_id',$this->language_id,true);
		$criteria->compare('eyebrow',$this->eyebrow,true);
		$criteria->compare('eyebrow_size',$this->eyebrow_size,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('title_size',$this->title_size,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('text_size',$this->text_size,true);
		$criteria->compare('brands_label',$this->brands_label,true);
		$criteria->compare('brands_label_size',$this->brands_label_size,true);
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
	 * @return ClientsSectionTranslations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
