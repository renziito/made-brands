<?php

/**
 * This is the model class for table "menu_items".
 *
 * The followings are the available columns in table 'menu_items':
 * @property integer $id
 * @property string $key
 * @property integer $is_menu
 * @property integer $is_button
 * @property string $link
 * @property integer $sort_order
 * @property integer $active
 * @property string $created_at
 * @property string $updated_at
 */
class MenuItems extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'menu_items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('key, created_at', 'required'),
			array('is_menu, is_button, sort_order, active', 'numerical', 'integerOnly' => true),
			array('key', 'length', 'max' => 100),
			array('link', 'length', 'max' => 255),
			array('updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, key, is_menu, is_button, link, sort_order, active, created_at, updated_at', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'menuItemTranslations' => array(
				self::HAS_MANY,
				'MenuItemTranslations',
				'menu_item_id',
			),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'key' => 'Key',
			'is_menu' => 'Is Menu',
			'is_button' => 'Is Button',
			'link' => 'Link',
			'sort_order' => 'Sort Order',
			'active' => 'Active',
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

		$criteria->compare('id', $this->id);
		$criteria->compare('key', $this->key, true);
		$criteria->compare('is_menu', $this->is_menu);
		$criteria->compare('is_button', $this->is_button);
		$criteria->compare('link', $this->link, true);
		$criteria->compare('sort_order', $this->sort_order);
		$criteria->compare('active', $this->active);
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
	 * @return MenuItems the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
