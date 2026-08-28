<?php

/**
 * This is the model class for table "about_section_stats".
 *
 * The followings are the available columns in table 'about_section_stats':
 * @property string $id
 * @property string $about_section_id
 * @property integer $sort_order
 * @property integer $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property AboutSectionStatTranslations[] $aboutSectionStatTranslations
 * @property AboutSections $aboutSection
 */
class AboutSectionStats extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'about_section_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('about_section_id, created_at, updated_at', 'required', 'message' => '{attribute} no debe estar vacio.'),
			array('sort_order, is_active', 'numerical', 'integerOnly'=>true,'message' => '{attribute} solo debe ser numeros.'),
			array('about_section_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, about_section_id, sort_order, is_active, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'aboutSectionStatTranslations' => array(self::HAS_MANY, 'AboutSectionStatTranslations', 'stat_id'),
			'aboutSection' => array(self::BELONGS_TO, 'AboutSections', 'about_section_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'about_section_id' => 'About Section',
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
		$criteria->compare('about_section_id',$this->about_section_id,true);
		$criteria->compare('sort_order',$this->sort_order);

		$criteria->order = 'sort_order DESC';
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
	 * @return AboutSectionStats the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
