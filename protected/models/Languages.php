<?php

/**
 * This is the model class for table "languages".
 *
 * The followings are the available columns in table 'languages':
 * @property string $id
 * @property string $code
 * @property string $locale
 * @property string $name
 * @property string $native_name
 * @property integer $is_default
 * @property integer $is_active
 * @property integer $sort_order
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property AboutSectionStatTranslations[] $aboutSectionStatTranslations
 * @property AboutSectionTranslations[] $aboutSectionTranslations
 * @property BusinessTranslations[] $businessTranslations
 * @property CategoryTranslations[] $categoryTranslations
 * @property ClientsSectionTranslations[] $clientsSectionTranslations
 * @property ContactCtaTranslations[] $contactCtaTranslations
 * @property ContactItemTranslations[] $contactItemTranslations
 * @property FaqTranslations[] $faqTranslations
 * @property HeroSlideTranslations[] $heroSlideTranslations
 * @property ProductTranslations[] $productTranslations
 * @property SubcategoryTranslations[] $subcategoryTranslations
 */
class Languages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'languages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, locale, name, native_name, created_at, updated_at', 'required', 'message' => '{attribute} no debe estar vacio.'),
			array('is_default, is_active, sort_order', 'numerical', 'integerOnly'=>true,'message' => '{attribute} solo debe ser numeros.'),
			array('code', 'length', 'max'=>10),
			array('locale', 'length', 'max'=>20),
			array('name, native_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, locale, name, native_name, is_default, is_active, sort_order, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'aboutSectionStatTranslations' => array(self::HAS_MANY, 'AboutSectionStatTranslations', 'language_id'),
			'aboutSectionTranslations' => array(self::HAS_MANY, 'AboutSectionTranslations', 'language_id'),
			'businessTranslations' => array(self::HAS_MANY, 'BusinessTranslations', 'language_id'),
			'categoryTranslations' => array(self::HAS_MANY, 'CategoryTranslations', 'language_id'),
			'clientsSectionTranslations' => array(self::HAS_MANY, 'ClientsSectionTranslations', 'language_id'),
			'contactCtaTranslations' => array(self::HAS_MANY, 'ContactCtaTranslations', 'language_id'),
			'contactItemTranslations' => array(self::HAS_MANY, 'ContactItemTranslations', 'language_id'),
			'faqTranslations' => array(self::HAS_MANY, 'FaqTranslations', 'language_id'),
			'heroSlideTranslations' => array(self::HAS_MANY, 'HeroSlideTranslations', 'language_id'),
			'productTranslations' => array(self::HAS_MANY, 'ProductTranslations', 'language_id'),
			'subcategoryTranslations' => array(self::HAS_MANY, 'SubcategoryTranslations', 'language_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'locale' => 'Locale',
			'name' => 'Name',
			'native_name' => 'Native Name',
			'is_default' => 'Is Default',
			'is_active' => 'Is Active',
			'sort_order' => 'Sort Order',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('locale',$this->locale,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('native_name',$this->native_name,true);
		$criteria->compare('is_default',$this->is_default);
		$criteria->compare('is_active',$this->is_active);

		$criteria->compare('sort_order',$this->sort_order);

		$criteria->order = 'sort_order DESC';
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
	 * @return Languages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
