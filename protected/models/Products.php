<?php

/**
 * This is the model class for table "products".
 *
 * The followings are the available columns in table 'products':
 * @property string $id
 * @property string $brand_id
 * @property string $main_image
 * @property string $infographic_image
 * @property string $slug
 * @property string $status
 * @property string $published_at
 * @property integer $sort_order
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Categories[] $categories
 * @property Subcategories[] $subcategories
 * @property ProductTranslations[] $productTranslations
 * @property Brands $brand
 */
class Products extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('brand_id, slug, created_at, updated_at', 'required', 'message' => '{attribute} no debe estar vacio.'),
			array('sort_order', 'numerical', 'integerOnly' => true, 'message' => '{attribute} solo debe ser numeros.'),
			array('brand_id', 'length', 'max' => 10),
			array('main_image, infographic_image', 'length', 'max' => 255),
			array('slug', 'length', 'max' => 150),
			array('status', 'length', 'max' => 20),
			array('published_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, brand_id, main_image, infographic_image, slug, status, published_at, sort_order, created_at, updated_at', 'safe', 'on' => 'search'),
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
			'categories' => array(self::MANY_MANY, 'Categories', 'product_categories(product_id, category_id)'),
			'subcategories' => array(self::MANY_MANY, 'Subcategories', 'product_subcategories(product_id, subcategory_id)'),
			'productTranslations' => array(self::HAS_MANY, 'ProductTranslations', 'product_id'),
			'brand' => array(self::BELONGS_TO, 'Brands', 'brand_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'brand_id' => 'Brand',
			'main_image' => 'Main Image',
			'infographic_image' => 'Infographic Image',
			'slug' => 'Slug',
			'status' => 'Status',
			'published_at' => 'Published At',
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

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('brand_id', $this->brand_id, true);
		$criteria->compare('main_image', $this->main_image, true);
		$criteria->compare('infographic_image', $this->infographic_image, true);
		$criteria->compare('slug', $this->slug, true);
		$criteria->compare('status', $this->status, true);
		$criteria->compare('published_at', $this->published_at, true);
		$criteria->compare('sort_order', $this->sort_order);

		$criteria->order = 'sort_order DESC';
		$criteria->compare('created_at', $this->created_at, true);
		$criteria->compare('updated_at', $this->updated_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Products the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
