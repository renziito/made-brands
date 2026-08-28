<?php

/**
 * This is the model class for table "product_translations".
 *
 * The followings are the available columns in table 'product_translations':
 * @property string $id
 * @property string $product_id
 * @property string $language_id
 * @property string $name
 * @property string $name_size
 * @property string $short_description
 * @property string $short_description_size
 * @property string $description
 * @property string $description_size
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Languages $language
 * @property Products $product
 */
class ProductTranslations extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product_translations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, language_id, name, created_at, updated_at', 'required', 'message' => '{attribute} no debe estar vacio.'),
			array('product_id, language_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>255),
			array('name_size, short_description_size, description_size', 'length', 'max'=>20),
			array('short_description, description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, product_id, language_id, name, name_size, short_description, short_description_size, description, description_size, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO, 'Products', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Product',
			'language_id' => 'Language',
			'name' => 'Name',
			'name_size' => 'Name Size',
			'short_description' => 'Short Description',
			'short_description_size' => 'Short Description Size',
			'description' => 'Description',
			'description_size' => 'Description Size',
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
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('language_id',$this->language_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('name_size',$this->name_size,true);
		$criteria->compare('short_description',$this->short_description,true);
		$criteria->compare('short_description_size',$this->short_description_size,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('description_size',$this->description_size,true);
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
	 * @return ProductTranslations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
