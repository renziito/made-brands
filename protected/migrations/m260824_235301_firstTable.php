<?php

class m260824_235301_firstTable extends CDbMigration
{
	public function safeUp()
	{
		$this->createTables();
		$this->seedData();
	}

	public function safeDown()
	{
		$tables = array(
			'product_subcategories',
			'product_categories',
			'product_translations',
			'products',
			'subcategory_translations',
			'subcategories',
			'category_translations',
			'categories',
			'brands',
			'brands_section',
			'business_translations',
			'businesses',
			'intro_section_translations',
			'intro_sections',
			'about_section_stat_translations',
			'about_section_stats',
			'about_section_translations',
			'about_sections',
			'hero_slide_translations',
			'hero_slides',
			'faq_form_submission_values',
			'faq_form_submissions',
			'faq_form_fields',
			'faq_translations',
			'faqs',
			'faq_forms',
			'contact_item_translations',
			'contact_items',
			'contact_cta_translations',
			'contact_cta',
			'social_links',
			'site_settings',
			'languages',
			'users',
			'menu_item_translations',
			'menu_items'
		);

		foreach ($tables as $table) {
			$this->dropTable($table);
		}
	}

	protected function createTables()
	{
		$this->createTable('users', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'username' => 'VARCHAR(100) NOT NULL',
			'email' => 'VARCHAR(255) NULL',
			'password_hash' => 'VARCHAR(255) NOT NULL',
			'status' => 'TINYINT NOT NULL DEFAULT 1',
			'last_login_at' => 'DATETIME NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_users_username` (`username`)',
			'UNIQUE KEY `uq_users_email` (`email`)',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('languages', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'code' => 'VARCHAR(10) NOT NULL',
			'locale' => 'VARCHAR(20) NOT NULL',
			'name' => 'VARCHAR(100) NOT NULL',
			'native_name' => 'VARCHAR(100) NOT NULL',
			'is_default' => 'TINYINT NOT NULL DEFAULT 0',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'sort_order' => 'INT NOT NULL DEFAULT 0',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_languages_code` (`code`)',
			'UNIQUE KEY `uq_languages_locale` (`locale`)',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('hero_slides', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'image' => 'VARCHAR(255) NULL',
			'alignment' => "VARCHAR(20) NOT NULL DEFAULT 'center'",
			'button_url' => 'VARCHAR(255) NULL',
			'sort_order' => 'INT NOT NULL DEFAULT 0',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_hero_slides_active_order` (`is_active`, `sort_order`)',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('hero_slide_translations', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'hero_slide_id' => 'INT UNSIGNED NOT NULL',
			'language_id' => 'INT UNSIGNED NOT NULL',
			'eyebrow' => 'VARCHAR(255) NULL',
			'eyebrow_size' => 'VARCHAR(20) NULL',
			'title' => 'TEXT NULL',
			'title_size' => 'VARCHAR(20) NULL',
			'text' => 'TEXT NULL',
			'text_size' => 'VARCHAR(20) NULL',
			'button_text' => 'VARCHAR(255) NULL',
			'button_text_size' => 'VARCHAR(20) NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_hero_slide_language` (`hero_slide_id`, `language_id`)',
			'KEY `idx_hero_translation_language` (`language_id`)',
			'CONSTRAINT `fk_hero_translation_slide` FOREIGN KEY (`hero_slide_id`) REFERENCES `hero_slides` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
			'CONSTRAINT `fk_hero_translation_language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('intro_sections', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'type' => "VARCHAR(30) NOT NULL DEFAULT 'intro'",
			'sort_order' => 'INT NOT NULL DEFAULT 0',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_intro_sections_type_active` (`type`, `is_active`, `sort_order`)',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('intro_section_translations', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'intro_section_id' => 'INT UNSIGNED NOT NULL',
			'language_id' => 'INT UNSIGNED NOT NULL',
			'eyebrow' => 'VARCHAR(255) NULL',
			'eyebrow_size' => 'VARCHAR(20) NULL',
			'title' => 'TEXT NULL',
			'title_size' => 'VARCHAR(20) NULL',
			'text' => 'TEXT NULL',
			'text_size' => 'VARCHAR(20) NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_intro_section_language` (`intro_section_id`, `language_id`)',
			'KEY `idx_intro_translation_language` (`language_id`)',
			'CONSTRAINT `fk_intro_translation_section` FOREIGN KEY (`intro_section_id`) REFERENCES `intro_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
			'CONSTRAINT `fk_intro_translation_language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');


		$this->createTable('about_sections', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'image' => 'VARCHAR(255) NULL',
			'sort_order' => 'INT NOT NULL DEFAULT 0',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_about_sections_active` (`is_active`, `sort_order`)',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('about_section_translations', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'about_section_id' => 'INT UNSIGNED NOT NULL',
			'language_id' => 'INT UNSIGNED NOT NULL',
			'eyebrow' => 'VARCHAR(255) NULL',
			'eyebrow_size' => 'VARCHAR(20) NULL',
			'title' => 'TEXT NULL',
			'title_size' => 'VARCHAR(20) NULL',
			'text' => 'TEXT NULL',
			'text_size' => 'VARCHAR(20) NULL',
			'secondary_text' => 'TEXT NULL',
			'secondary_text_size' => 'VARCHAR(20) NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_about_section_language` (`about_section_id`, `language_id`)',
			'KEY `idx_about_translation_language` (`language_id`)',
			'CONSTRAINT `fk_about_translation_section` FOREIGN KEY (`about_section_id`) REFERENCES `about_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
			'CONSTRAINT `fk_about_translation_language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('about_section_stats', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'about_section_id' => 'INT UNSIGNED NOT NULL',
			'sort_order' => 'INT NOT NULL DEFAULT 0',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_about_stats_section_order` (`about_section_id`, `sort_order`)',
			'CONSTRAINT `fk_about_stats_section` FOREIGN KEY (`about_section_id`) REFERENCES `about_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('about_section_stat_translations', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'stat_id' => 'INT UNSIGNED NOT NULL',
			'language_id' => 'INT UNSIGNED NOT NULL',
			'value' => 'VARCHAR(255) NOT NULL',
			'value_size' => 'VARCHAR(20) NULL',
			'label' => 'VARCHAR(255) NOT NULL',
			'label_size' => 'VARCHAR(20) NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_about_stat_language` (`stat_id`, `language_id`)',
			'KEY `idx_about_stat_translation_language` (`language_id`)',
			'CONSTRAINT `fk_about_stat_translation_stat` FOREIGN KEY (`stat_id`) REFERENCES `about_section_stats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
			'CONSTRAINT `fk_about_stat_translation_language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('businesses', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'image' => 'VARCHAR(255) NULL',
			'icon' => 'VARCHAR(100) NULL',
			'sort_order' => 'INT NOT NULL DEFAULT 0',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_businesses_active_order` (`is_active`, `sort_order`)',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('business_translations', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'business_id' => 'INT UNSIGNED NOT NULL',
			'language_id' => 'INT UNSIGNED NOT NULL',
			'name' => 'VARCHAR(255) NULL',
			'name_size' => 'VARCHAR(20) NULL',
			'description' => 'TEXT NULL',
			'description_size' => 'VARCHAR(20) NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_business_language` (`business_id`, `language_id`)',
			'KEY `idx_business_translation_language` (`language_id`)',
			'CONSTRAINT `fk_business_translation_business` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
			'CONSTRAINT `fk_business_translation_language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('categories', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'image' => 'VARCHAR(255) NULL',
			'is_featured' => 'TINYINT NOT NULL DEFAULT 0',
			'sort_order' => 'INT NOT NULL DEFAULT 0',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_categories_featured_active_order` (`is_featured`, `is_active`, `sort_order`)',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('category_translations', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'category_id' => 'INT UNSIGNED NOT NULL',
			'language_id' => 'INT UNSIGNED NOT NULL',
			'name' => 'VARCHAR(255) NOT NULL',
			'name_size' => 'VARCHAR(20) NULL',
			'description' => 'TEXT NULL',
			'description_size' => 'VARCHAR(20) NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_category_language` (`category_id`, `language_id`)',
			'KEY `idx_category_translation_language` (`language_id`)',
			'CONSTRAINT `fk_category_translation_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
			'CONSTRAINT `fk_category_translation_language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('subcategories', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'category_id' => 'INT UNSIGNED NOT NULL',
			'sort_order' => 'INT NOT NULL DEFAULT 0',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_subcategories_category_active_order` (`category_id`, `is_active`, `sort_order`)',
			'CONSTRAINT `fk_subcategories_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('subcategory_translations', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'subcategory_id' => 'INT UNSIGNED NOT NULL',
			'language_id' => 'INT UNSIGNED NOT NULL',
			'name' => 'VARCHAR(255) NOT NULL',
			'name_size' => 'VARCHAR(20) NULL',
			'description' => 'TEXT NULL',
			'description_size' => 'VARCHAR(20) NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_subcategory_language` (`subcategory_id`, `language_id`)',
			'KEY `idx_subcategory_translation_language` (`language_id`)',
			'CONSTRAINT `fk_subcategory_translation_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
			'CONSTRAINT `fk_subcategory_translation_language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('brands', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'name' => 'VARCHAR(255) NOT NULL',
			'slug' => 'VARCHAR(150) NOT NULL',
			'logo' => 'VARCHAR(255) NULL',
			'website_url' => 'VARCHAR(255) NULL',
			'is_featured' => 'TINYINT NOT NULL DEFAULT 0',
			'sort_order' => 'INT NOT NULL DEFAULT 0',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_brands_featured_active_order` (`is_featured`, `is_active`, `sort_order`)',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('brands_section', array(
			'id'         => 'pk',
			'language_id' => 'INT UNSIGNED NOT NULL',
			'eyebrow'    => 'VARCHAR(255) NOT NULL',
			'title'      => 'VARCHAR(500) NOT NULL',
			'text'       => 'TEXT NOT NULL',
			'featured_label' => 'TEXT NOT NULL',
			'image'      => 'VARCHAR(255) DEFAULT NULL',
			'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
			'updated_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('products', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'brand_id' => 'INT UNSIGNED NOT NULL',
			'main_image' => 'VARCHAR(255) NULL',
			'infographic_image' => 'VARCHAR(255) NULL',
			'slug' => 'VARCHAR(150) NOT NULL',
			'status' => "VARCHAR(20) NOT NULL DEFAULT 'borrador'",
			'published_at' => 'DATETIME NULL',
			'sort_order' => 'INT NOT NULL DEFAULT 0',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_products_slug` (`slug`)',
			'KEY `idx_products_brand_status` (`brand_id`, `status`)',
			'KEY `idx_products_status_published` (`status`, `published_at`)',
			'KEY `idx_products_status_order` (`status`, `sort_order`)',
			'CONSTRAINT `fk_products_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('product_translations', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'product_id' => 'INT UNSIGNED NOT NULL',
			'language_id' => 'INT UNSIGNED NOT NULL',
			'name' => 'VARCHAR(255) NOT NULL',
			'name_size' => 'VARCHAR(20) NULL',
			'short_description' => 'TEXT NULL',
			'short_description_size' => 'VARCHAR(20) NULL',
			'description' => 'TEXT NULL',
			'description_size' => 'VARCHAR(20) NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_product_language` (`product_id`, `language_id`)',
			'KEY `idx_product_translation_language` (`language_id`)',
			'CONSTRAINT `fk_product_translation_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
			'CONSTRAINT `fk_product_translation_language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('product_categories', array(
			'product_id' => 'INT UNSIGNED NOT NULL',
			'category_id' => 'INT UNSIGNED NOT NULL',
			'PRIMARY KEY (`product_id`, `category_id`)',
			'KEY `idx_product_categories_category` (`category_id`)',
			'CONSTRAINT `fk_product_categories_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
			'CONSTRAINT `fk_product_categories_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('product_subcategories', array(
			'product_id' => 'INT UNSIGNED NOT NULL',
			'subcategory_id' => 'INT UNSIGNED NOT NULL',
			'PRIMARY KEY (`product_id`, `subcategory_id`)',
			'KEY `idx_product_subcategories_subcategory` (`subcategory_id`)',
			'CONSTRAINT `fk_product_subcategories_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
			'CONSTRAINT `fk_product_subcategories_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');


		$this->createTable('faqs', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'icon' => 'VARCHAR(100) NULL',
			'sort_order' => 'INT NOT NULL DEFAULT 0',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_faqs_active_order` (`is_active`, `sort_order`)'
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');


		$this->createTable('faq_forms', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'title' => 'VARCHAR(255) NOT NULL',
			'description' => 'TEXT NULL',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_faq_forms_active` (`is_active`)',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('faq_form_fields', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'form_id' => 'INT UNSIGNED NOT NULL',
			'name' => 'VARCHAR(100) NOT NULL',
			'label' => 'VARCHAR(255) NOT NULL',
			'type' => 'VARCHAR(50) NOT NULL',
			'placeholder' => 'VARCHAR(255) NULL',
			'default_value' => 'TEXT NULL',
			'options' => 'TEXT NULL',
			'is_required' => 'TINYINT NOT NULL DEFAULT 0',
			'sort_order' => 'INT NOT NULL DEFAULT 0',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_faq_form_fields_form_order` (`form_id`, `sort_order`)',
			'KEY `idx_faq_form_fields_active` (`is_active`)',
			'CONSTRAINT `fk_faq_form_fields_form` FOREIGN KEY (`form_id`) REFERENCES `faq_forms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');


		$this->createTable('faq_translations', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'faq_id' => 'INT UNSIGNED NOT NULL',
			'form_text' => 'TEXT NULL',
			'form_id' => 'INT UNSIGNED NULL',
			'language_id' => 'INT UNSIGNED NOT NULL',
			'question' => 'TEXT NOT NULL',
			'answer' => 'LONGTEXT NOT NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_faq_language` (`faq_id`, `language_id`)',
			'KEY `idx_faq_translation_language` (`language_id`)',
			'CONSTRAINT `fk_faq_translation_faq` FOREIGN KEY (`faq_id`) REFERENCES `faqs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
			'CONSTRAINT `fk_faq_translation_language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('faq_form_submissions', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'form_id' => 'INT UNSIGNED NOT NULL',
			'ip_address' => 'VARCHAR(45) NULL',
			'user_agent' => 'TEXT NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_faq_form_submissions_form` (`form_id`)',
			'KEY `idx_faq_form_submissions_created` (`created_at`)',
			'CONSTRAINT `fk_faq_form_submissions_form` FOREIGN KEY (`form_id`) REFERENCES `faq_forms` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('faq_form_submission_values', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'submission_id' => 'INT UNSIGNED NOT NULL',
			'field_id' => 'INT UNSIGNED NOT NULL',
			'value' => 'LONGTEXT NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_submission_field` (`submission_id`, `field_id`)',
			'KEY `idx_submission_values_submission` (`submission_id`)',
			'KEY `idx_submission_values_field` (`field_id`)',
			'CONSTRAINT `fk_submission_values_submission` FOREIGN KEY (`submission_id`) REFERENCES `faq_form_submissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
			'CONSTRAINT `fk_submission_values_field` FOREIGN KEY (`field_id`) REFERENCES `faq_form_fields` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('contact_items', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'icon' => 'VARCHAR(100) NULL',
			'sort_order' => 'INT NOT NULL DEFAULT 0',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_contact_items_active_order` (`is_active`, `sort_order`)',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('contact_item_translations', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'contact_item_id' => 'INT UNSIGNED NOT NULL',
			'language_id' => 'INT UNSIGNED NOT NULL',
			'label' => 'VARCHAR(255) NOT NULL',
			'label_size' => 'VARCHAR(20) NULL',
			'value' => 'TEXT NOT NULL',
			'value_size' => 'VARCHAR(20) NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_contact_item_language` (`contact_item_id`, `language_id`)',
			'KEY `idx_contact_item_translation_language` (`language_id`)',
			'CONSTRAINT `fk_contact_item_translation_item` FOREIGN KEY (`contact_item_id`) REFERENCES `contact_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
			'CONSTRAINT `fk_contact_item_translation_language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('contact_cta', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'icon' => 'VARCHAR(100) NULL',
			'url' => 'VARCHAR(255) NULL',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('contact_cta_translations', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'contact_cta_id' => 'INT UNSIGNED NOT NULL',
			'language_id' => 'INT UNSIGNED NOT NULL',
			'title' => 'VARCHAR(255) NOT NULL',
			'title_size' => 'VARCHAR(20) NULL',
			'text' => 'TEXT NULL',
			'text_size' => 'VARCHAR(20) NULL',
			'button_text' => 'VARCHAR(255) NULL',
			'button_text_size' => 'VARCHAR(20) NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_contact_cta_language` (`contact_cta_id`, `language_id`)',
			'KEY `idx_contact_cta_translation_language` (`language_id`)',
			'CONSTRAINT `fk_contact_cta_translation_cta` FOREIGN KEY (`contact_cta_id`) REFERENCES `contact_cta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
			'CONSTRAINT `fk_contact_cta_translation_language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('social_links', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'name' => 'VARCHAR(100) NOT NULL',
			'name_size' => 'VARCHAR(20) NULL',
			'icon' => 'VARCHAR(100) NOT NULL',
			'url' => 'VARCHAR(255) NOT NULL',
			'sort_order' => 'INT NOT NULL DEFAULT 0',
			'is_active' => 'TINYINT NOT NULL DEFAULT 1',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'KEY `idx_social_links_active_order` (`is_active`, `sort_order`)',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('site_settings', array(
			'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
			'setting_key' => 'VARCHAR(100) NOT NULL',
			'setting_value' => 'LONGTEXT NULL',
			'setting_type' => "VARCHAR(30) NOT NULL DEFAULT 'text'",
			'setting_size' => 'VARCHAR(20) NULL',
			'created_at' => 'DATETIME NOT NULL',
			'updated_at' => 'DATETIME NOT NULL',
			'PRIMARY KEY (`id`)',
			'UNIQUE KEY `uq_site_settings_key` (`setting_key`)',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createTable('menu_items', array(
			'id' => 'pk',
			'key' => 'varchar(100) NOT NULL',
			'is_menu' => 'tinyint(1) NOT NULL DEFAULT 0',
			'is_button' => 'tinyint(1) NOT NULL DEFAULT 0',
			'link' => 'varchar(255) NULL',
			'sort_order' => 'int NOT NULL DEFAULT 0',
			'active' => 'tinyint(1) NOT NULL DEFAULT 1',
			'created_at' => 'datetime NOT NULL',
			'updated_at' => 'datetime NULL',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createIndex('ux_menu_items_key', 'menu_items', 'key', true);
		$this->createIndex('ix_menu_items_menu', 'menu_items', 'is_menu, active, sort_order');

		$this->createTable('menu_item_translations', array(
			'id' => 'pk',
			'menu_item_id' => 'int NOT NULL',
			'language_id' => 'int NOT NULL',
			'label' => 'varchar(255) NOT NULL',
			'created_at' => 'datetime NOT NULL',
			'updated_at' => 'datetime NULL',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

		$this->createIndex('ux_menu_item_translations_language_item', 'menu_item_translations', 'menu_item_id, language_id', true);
		$this->createIndex('ix_menu_item_translations_language', 'menu_item_translations', 'language_id');
	}

	protected function seedData()
	{
		$now = date('Y-m-d H:i:s');

		// Admin user. Password hash supplied by the project owner.
		$this->insert('users', array(
			'username' => 'admin',
			'email' => null,
			'password_hash' => '$2y$10$g2siL4DcVBuwv7XXuIrVtuxe4ShtoRuS/LV00NU/wo3ITu/3RrT1u',
			'status' => 1,
			'created_at' => $now,
			'updated_at' => $now,
		));

		// Languages.
		$this->insert('languages', array(
			'code' => 'es',
			'locale' => 'es-PE',
			'name' => 'Spanish',
			'native_name' => 'Español',
			'is_default' => 1,
			'is_active' => 1,
			'sort_order' => 1,
			'created_at' => $now,
			'updated_at' => $now,
		));
		$this->insert('languages', array(
			'code' => 'en',
			'locale' => 'en-US',
			'name' => 'English',
			'native_name' => 'English',
			'is_default' => 0,
			'is_active' => 1,
			'sort_order' => 2,
			'created_at' => $now,
			'updated_at' => $now,
		));

		// -----------------------------------------------------------------
		// HERO
		// -----------------------------------------------------------------
		$this->insert('hero_slides', array(
			'image' => null,
			'alignment' => 'center',
			'button_url' => null,
			'sort_order' => 1,
			'is_active' => 1,
			'created_at' => $now,
			'updated_at' => $now,
		));
		$heroId = $this->dbConnection->getLastInsertID();

		$this->insert('hero_slide_translations', array(
			'hero_slide_id' => $heroId,
			'language_id' => 1,
			'eyebrow' => 'NUESTRA MISIÓN',
			'eyebrow_size' => '12',
			'title' => 'Llevamos grandes marcas a grandes personas',
			'title_size' => '42',
			'text' => 'Trabajamos con marcas internacionales de prestigio para ofrecer productos de la más alta calidad, con diseño, innovación y propósito.',
			'text_size' => '16',
			'button_text' => null,
			'button_text_size' => null,
			'created_at' => $now,
			'updated_at' => $now,
		));


		$this->insert('about_sections', array(
			'image' => null,
			'sort_order' => 1,
			'is_active' => 1,
			'created_at' => $now,
			'updated_at' => $now,
		));
		$aboutId = $this->dbConnection->getLastInsertID();

		$this->insert('about_section_translations', array(
			'about_section_id' => $aboutId,
			'language_id' => 1,
			'eyebrow' => 'SOBRE NOSOTROS',
			'eyebrow_size' => '12',
			'title' => 'Construimos relaciones que generan valor',
			'title_size' => '38',
			'text' => 'Representamos marcas que comparten nuestra visión y las conectamos con consumidores que buscan productos diferentes.',
			'text_size' => '16',
			'secondary_text' => 'Nuestro trabajo combina experiencia, conocimiento del mercado y una mirada enfocada en construir relaciones de largo plazo.',
			'secondary_text_size' => '16',
			'created_at' => $now,
			'updated_at' => $now,
		));

		$stats = array(
			array('value' => '6+', 'label' => 'Años en el mercado', 'order' => 1),
			array('value' => '5+', 'label' => 'Socios comerciales', 'order' => 2),
			array('value' => '5', 'label' => 'Categorías de productos', 'order' => 3),
			array('value' => '#2', 'label' => 'Marca de granola en Perú', 'order' => 4),
		);
		foreach ($stats as $stat) {
			$this->insert('about_section_stats', array(
				'about_section_id' => $aboutId,
				'sort_order' => $stat['order'],
				'is_active' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			));
			$statId = $this->dbConnection->getLastInsertID();

			$this->insert('about_section_stat_translations', array(
				'stat_id' => $statId,
				'language_id' => 1,
				'value' => $stat['value'],
				'value_size' => '28',
				'label' => $stat['label'],
				'label_size' => '13',
				'created_at' => $now,
				'updated_at' => $now,
			));
		}

		// -----------------------------------------------------------------
		// BUSINESSES
		// -----------------------------------------------------------------
		$businesses = array(
			array(
				'name' => 'Consumo masivo',
				'description' => 'Llevamos productos de calidad a las góndolas de todo el país.',
				'icon' => 'fas fa-shopping-cart',
				'order' => 1,
			),
			array(
				'name' => 'Soluciones B2B',
				'description' => 'Soluciones a medida para empresas, instituciones y canales profesionales.',
				'icon' => 'fas fa-truck-moving',
				'order' => 2,
			),
		);
		foreach ($businesses as $business) {
			$this->insert('businesses', array(
				'image' => null,
				'icon' => $business['icon'],
				'sort_order' => $business['order'],
				'is_active' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			));
			$businessId = $this->dbConnection->getLastInsertID();

			$this->insert('business_translations', array(
				'business_id' => $businessId,
				'language_id' => 1,
				'name' => $business['name'],
				'name_size' => '24',
				'description' => $business['description'],
				'description_size' => '14',
				'created_at' => $now,
				'updated_at' => $now,
			));
		}

		// -----------------------------------------------------------------
		// CATEGORIES
		// -----------------------------------------------------------------
		$categories = array(
			array('name' => 'Chocolates', 'order' => 1, 'featured' => 1),
			array('name' => 'Salsas', 'order' => 2, 'featured' => 1),
			array('name' => 'Galletas', 'order' => 3, 'featured' => 1),
			array('name' => 'Hogar', 'order' => 4, 'featured' => 1),
			array('name' => 'Snacks', 'order' => 5, 'featured' => 1),
			array('name' => 'Cuidado personal', 'order' => 6, 'featured' => 1),
		);

		$categoryIds = array();
		foreach ($categories as $category) {
			$this->insert('categories', array(
				'image' => null,
				'is_featured' => $category['featured'],
				'sort_order' => $category['order'],
				'is_active' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			));
			$categoryId = $this->dbConnection->getLastInsertID();
			$categoryIds[$category['name']] = $categoryId;

			$this->insert('category_translations', array(
				'category_id' => $categoryId,
				'language_id' => 1,
				'name' => $category['name'],
				'name_size' => '14',
				'description' => null,
				'description_size' => null,
				'created_at' => $now,
				'updated_at' => $now,
			));
		}

		// -----------------------------------------------------------------
		// SUBCATEGORIES
		// Demo hierarchy to exercise category -> subcategory filters.
		// -----------------------------------------------------------------
		$subcategories = array(
			array('category' => 'Chocolates', 'name' => 'Chocolate con leche', 'order' => 1),
			array('category' => 'Chocolates', 'name' => 'Chocolate amargo', 'order' => 2),
			array('category' => 'Salsas', 'name' => 'Salsas gourmet', 'order' => 1),
			array('category' => 'Galletas', 'name' => 'Galletas con chips', 'order' => 1),
			array('category' => 'Snacks', 'name' => 'Snacks dulces', 'order' => 1),
		);

		$subcategoryIds = array();
		foreach ($subcategories as $subcategory) {
			$this->insert('subcategories', array(
				'category_id' => $categoryIds[$subcategory['category']],
				'sort_order' => $subcategory['order'],
				'is_active' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			));
			$subcategoryId = $this->dbConnection->getLastInsertID();
			$subcategoryIds[$subcategory['name']] = $subcategoryId;

			$this->insert('subcategory_translations', array(
				'subcategory_id' => $subcategoryId,
				'language_id' => 1,
				'name' => $subcategory['name'],
				'name_size' => '14',
				'description' => null,
				'description_size' => null,
				'created_at' => $now,
				'updated_at' => $now,
			));
		}

		// -----------------------------------------------------------------
		// BRANDS / CLIENTS
		// -----------------------------------------------------------------
		$brands = array(
			array('name' => 'Mercado Verde', 'slug' => 'mercado-verde', 'featured' => 1, 'order' => 1),
			array('name' => 'Disco', 'slug' => 'disco', 'featured' => 1, 'order' => 2),
			array('name' => 'Devoto', 'slug' => 'devoto', 'featured' => 0, 'order' => 3),
		);

		$brandIds = array();
		foreach ($brands as $brand) {
			$this->insert('brands', array(
				'name' => $brand['name'],
				'slug' => $brand['slug'],
				'logo' => null,
				'website_url' => null,
				'is_featured' => $brand['featured'],
				'sort_order' => $brand['order'],
				'is_active' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			));
			$brandIds[$brand['name']] = $this->dbConnection->getLastInsertID();
		}

		$this->insert('brands_section', array(
			'eyebrow' => 'NUESTROS CLIENTES',
			'language_id' => 1,
			'title'   => 'Estamos donde vos estás',
			'text'    => 'Nuestras marcas llegan a miles de puntos de venta en todo el país, acompañando cada momento.',
			'featured_label' => ' Marcas Destacadas',
			'image'   => null,
		));

		// -----------------------------------------------------------------
		// FAQ
		// -----------------------------------------------------------------
		$faqs = array(
			array(
				'icon' => 'fas fa-map-marker-alt',
				'question' => '¿Dónde puedo comprar nuestros productos?',
				'answer' => 'Puedes encontrar nuestros productos en los principales puntos de venta y canales de distribución donde están presentes nuestras marcas.',
				'order' => 1,
			),
			array(
				'icon' => 'fas fa-thumbs-up',
				'question' => '¿Quieres vender nuestras marcas?',
				'answer' => 'Si estás interesado en comercializar nuestras marcas, contáctanos y conversemos sobre las oportunidades disponibles para tu negocio.',
				'order' => 2,
			),
			array(
				'icon' => 'fas fa-cube',
				'question' => '¿Tienes una marca? Hagámosla crecer en Uruguay',
				'answer' => 'Trabajamos con marcas que buscan crecer y llegar a nuevos consumidores. Cuéntanos sobre tu marca y evaluemos juntos las oportunidades.',
				'order' => 3,
			),
			array(
				'icon' => 'fas fa-plane',
				'question' => '¿Quieres exportar y distribuir tu marca en Uruguay?',
				'answer' => 'Contamos con experiencia en distribución y comercialización para conectar marcas con nuevos mercados y oportunidades.',
				'order' => 4,
			),
		);

		foreach ($faqs as $faq) {
			$this->insert('faqs', array(
				'icon' => $faq['icon'],
				'sort_order' => $faq['order'],
				'is_active' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			));
			$faqId = $this->dbConnection->getLastInsertID();

			$this->insert('faq_translations', array(
				'faq_id' => $faqId,
				'language_id' => 1,
				'question' => $faq['question'],
				'answer' => $faq['answer'],
				'created_at' => $now,
				'updated_at' => $now,
			));
		}

		// -----------------------------------------------------------------
		// CONTACT
		// -----------------------------------------------------------------
		$contactItems = array(
			array(
				'icon' => 'fas fa-map-marker-alt',
				'label' => 'Dirección',
				'value' => "Av. Italia 1234, Oficina 456\nMontevideo, Uruguay",
				'order' => 1,
			),
			array(
				'icon' => 'fas fa-phone',
				'label' => 'Teléfono',
				'value' => '+598 2628 1234',
				'order' => 2,
			),
			array(
				'icon' => 'far fa-envelope',
				'label' => 'Email',
				'value' => 'hola@madebrands.com',
				'order' => 3,
			),
		);

		foreach ($contactItems as $contact) {
			$this->insert('contact_items', array(
				'icon' => $contact['icon'],
				'sort_order' => $contact['order'],
				'is_active' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			));
			$contactId = $this->dbConnection->getLastInsertID();

			$this->insert('contact_item_translations', array(
				'contact_item_id' => $contactId,
				'language_id' => 1,
				'label' => $contact['label'],
				'label_size' => '14',
				'value' => $contact['value'],
				'value_size' => '14',
				'created_at' => $now,
				'updated_at' => $now,
			));
		}

		$this->insert('contact_cta', array(
			'icon' => 'far fa-envelope',
			'url' => 'mailto:hola@madebrands.com',
			'is_active' => 1,
			'created_at' => $now,
			'updated_at' => $now,
		));
		$contactCtaId = $this->dbConnection->getLastInsertID();

		$this->insert('contact_cta_translations', array(
			'contact_cta_id' => $contactCtaId,
			'language_id' => 1,
			'title' => 'ESCRÍBENOS',
			'title_size' => '20',
			'text' => 'Te responderemos a la brevedad',
			'text_size' => '13',
			'button_text' => null,
			'button_text_size' => null,
			'created_at' => $now,
			'updated_at' => $now,
		));

		// -----------------------------------------------------------------
		// SOCIAL LINKS
		// -----------------------------------------------------------------
		$socials = array(
			array('name' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'url' => '#', 'order' => 1),
			array('name' => 'Instagram', 'icon' => 'fab fa-instagram', 'url' => '#', 'order' => 2),
			array('name' => 'WhatsApp', 'icon' => 'fab fa-whatsapp', 'url' => '#', 'order' => 3),
		);

		foreach ($socials as $social) {
			$this->insert('social_links', array(
				'name' => $social['name'],
				'name_size' => '14',
				'icon' => $social['icon'],
				'url' => $social['url'],
				'sort_order' => $social['order'],
				'is_active' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			));
		}

		// -----------------------------------------------------------------
		// SITE SETTINGS
		// -----------------------------------------------------------------
		$settings = array(
			array('key' => 'site_name', 'value' => 'made.brands', 'type' => 'text'),
			array('key' => 'tagline', 'value' => 'Llevamos grandes marcas a grandes personas', 'type' => 'text'),
			array('key' => 'tagline_menu', 'value' => '0', 'type' => 'bool'),
			array('key' => 'tagline_footer', 'value' => '0', 'type' => 'bool'),
			array('key' => 'logo_menu_size', 'value' => '16', 'type' => 'text'),
			array('key' => 'logo_footer_size', 'value' => '16', 'type' => 'text'),
			array('key' => 'full_sheet', 'value' => null, 'type' => 'text'),
			array('key' => 'font_family', 'value' => 'Inter, sans-serif', 'type' => 'text'),
			array('key' => 'logo_font_family', 'value' => 'Inter', 'type' => 'text'),
			array('key' => 'heading_font_family', 'value' => 'Inter, sans-serif', 'type' => 'text'),
			array('key' => 'eyebrow_font_family', 'value' => 'Inter, sans-serif', 'type' => 'text'),
			array('key' => 'body_font_family', 'value' => 'Inter, sans-serif', 'type' => 'text'),
			array('key' => 'button_font_family', 'value' => 'Inter, sans-serif', 'type' => 'text'),
			array('key' => 'heading_color', 'value' => '#111111', 'type' => 'text'),
			array('key' => 'eyebrow_color', 'value' => '#666666', 'type' => 'text'),
			array('key' => 'body_text_color', 'value' => '#444444', 'type' => 'text'),
			array('key' => 'separator_color', 'value' => '#D9D9D9', 'type' => 'text'),
			array('key' => 'contact_button_background_color', 'value' => '#111111', 'type' => 'text'),
			array('key' => 'contact_button_text_color', 'value' => '#FFFFFF', 'type' => 'text'),
			array('key' => 'category_button_background_color', 'value' => '#111111', 'type' => 'text'),
			array('key' => 'category_button_text_color', 'value' => '#FFFFFF', 'type' => 'text'),
			array('key' => 'cta_background_color', 'value' => '#111111', 'type' => 'text'),
			array('key' => 'cta_text_color', 'value' => '#FFFFFF', 'type' => 'text'),
			array('key' => 'body_background_color', 'value' => '#FFFFFF', 'type' => 'text'),
			array('key' => 'header_background_color', 'value' => '#FFFFFF', 'type' => 'text'),
			array('key' => 'section_background_color', 'value' => '#FFFFFF', 'type' => 'text'),
			array('key' => 'section_alt_background_color', 'value' => '#F5F5F5', 'type' => 'text'),
			array('key' => 'footer_background_color', 'value' => '#111111', 'type' => 'text'),
		);

		foreach ($settings as $setting) {
			$this->insert('site_settings', array(
				'setting_key' => $setting['key'],
				'setting_value' => $setting['value'],
				'setting_type' => $setting['type'],
				'setting_size' => null,
				'created_at' => $now,
				'updated_at' => $now,
			));
		}

		$this->insert('intro_sections', array(
			'type' => 'intro',
			'sort_order' => 0,
			'is_active' => 1,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		));

		$introSectionId = $this->getDbConnection()->getLastInsertID();

		$this->insert('intro_section_translations', array(
			'intro_section_id' => $introSectionId,
			'language_id' => 1,
			'eyebrow' => 'NUESTRA MISIÓN',
			'eyebrow_size' => '14px',
			'title' => 'Llevamos grandes marcas a grandes personas',
			'title_size' => '52px',
			'text' => 'Trabajamos con marcas internacionales de prestigio para ofrecer productos de la más alta calidad, con diseño, innovación y propósito.',
			'text_size' => '20px',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		));

		// -----------------------------------------------------------------
		// DEMO PRODUCTS
		// These are temporary seed records so the catalog can be developed.
		// -----------------------------------------------------------------
		$demoProducts = array(
			array(
				'brand' => 'Mercado Verde',
				'slug' => 'chocolate-clasico',
				'name' => 'Chocolate clásico',
				'description' => 'Chocolate de alta calidad para disfrutar en cualquier momento.',
				'categories' => array('Chocolates'),
				'subcategories' => array('Chocolate con leche'),
				'order' => 1,
			),
			array(
				'brand' => 'Disco',
				'slug' => 'salsa-gourmet',
				'name' => 'Salsa gourmet',
				'description' => 'Salsa pensada para acompañar tus comidas y crear nuevas experiencias.',
				'categories' => array('Salsas'),
				'subcategories' => array('Salsas gourmet'),
				'order' => 2,
			),
			array(
				'brand' => 'Devoto',
				'slug' => 'galletas-con-chips',
				'name' => 'Galletas con chips',
				'description' => 'Galletas con chips de chocolate para compartir y disfrutar.',
				'categories' => array('Galletas'),
				'subcategories' => array('Galletas con chips'),
				'order' => 3,
			),
		);

		foreach ($demoProducts as $product) {
			$this->insert('products', array(
				'brand_id' => $brandIds[$product['brand']],
				'main_image' => null,
				'infographic_image' => null,
				'slug' => $product['slug'],
				'status' => 'publicado',
				'published_at' => $now,
				'sort_order' => $product['order'],
				'created_at' => $now,
				'updated_at' => $now,
			));
			$productId = $this->dbConnection->getLastInsertID();

			$this->insert('product_translations', array(
				'product_id' => $productId,
				'language_id' => 1,
				'name' => $product['name'],
				'name_size' => '24',
				'short_description' => null,
				'short_description_size' => null,
				'description' => $product['description'],
				'description_size' => '14',
				'created_at' => $now,
				'updated_at' => $now,
			));

			foreach ($product['categories'] as $categoryName) {
				$this->insert('product_categories', array(
					'product_id' => $productId,
					'category_id' => $categoryIds[$categoryName],
				));
			}

			foreach ($product['subcategories'] as $subcategoryName) {
				$this->insert('product_subcategories', array(
					'product_id' => $productId,
					'subcategory_id' => $subcategoryIds[$subcategoryName],
				));
			}
		}
		$items = array(
			array(
				'key' => 'about',
				'is_menu' => 1,
				'is_button' => 0,
				'link' => '#nosotros',
				'sort_order' => 1,
			),
			array(
				'key' => 'business',
				'is_menu' => 1,
				'is_button' => 0,
				'link' => '#negocios',
				'sort_order' => 2,
			),
			array(
				'key' => 'products',
				'is_menu' => 1,
				'is_button' => 0,
				'link' => '#productos',
				'sort_order' => 3,
			),
			array(
				'key' => 'brands',
				'is_menu' => 1,
				'is_button' => 0,
				'link' => '#clientes',
				'sort_order' => 4,
			),
			array(
				'key' => 'faq',
				'is_menu' => 1,
				'is_button' => 0,
				'link' => '#faq',
				'sort_order' => 5,
			),
			array(
				'key' => 'contact',
				'is_menu' => 1,
				'is_button' => 1,
				'link' => '#contacto',
				'sort_order' => 6,
			),
			array(
				'key' => 'our_businesses',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),
			array(
				'key' => 'our_categories',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),
			array(
				'key' => 'view_all_products',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),
			array(
				'key' => 'frequently_asked_questions',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),
			array(
				'key' => 'all_rights_reserved',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),
			array(
				'key' => 'filters',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),
			array(
				'key' => 'categories',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),
			array(
				'key' => 'our_products',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),
			array(
				'key' => 'sort_most_recent',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),
			array(
				'key' => 'sort_name',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),
			array(
				'key' => 'sort_oldest',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),
			array(
				'key' => 'clear_filters',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),

			array(
				'key' => 'download_product_catalog',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),

			array(
				'key' => 'show_filters',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),

			array(
				'key' => 'showing',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),

			array(
				'key' => 'of',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),
			array(
				'key' => 'no_products_found',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),

			array(
				'key' => 'remove_filters_message',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),

			array(
				'key' => 'loading_product_information',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),

			array(
				'key' => 'product_infographic_unavailable',
				'is_menu' => 0,
				'is_button' => 0,
				'link' => null,
				'sort_order' => 0,
			),
		);

		foreach ($items as $item) {
			$this->insert('menu_items', array(
				'key' => $item['key'],
				'is_menu' => $item['is_menu'],
				'is_button' => $item['is_button'],
				'link' => $item['link'],
				'sort_order' => $item['sort_order'],
				'active' => 1,
				'created_at' => $now,
				'updated_at' => null,
			));
		}


		// ==========================================================
		// SPANISH TRANSLATIONS
		// language_id = 1
		// ==========================================================

		$translations = array(
			'about' => 'Nosotros',
			'business' => 'Negocios',
			'products' => 'Productos',
			'brands' => 'Marcas',
			'faq' => 'FAQ',
			'contact' => 'Contacto',
			'our_businesses' => 'Nuestros negocios',
			'our_categories' => 'Nuestras categorias',
			'view_all_products' => 'Ver todos los productos',
			'frequently_asked_questions' => 'Preguntas Frecuentes',
			'all_rights_reserved' => 'Todos los derechos reservados',
			'filters' => 'Filtros',
			'categories' => 'Categorías',
			'our_products' => 'Nuestros productos',
			'sort_most_recent' => 'Ordenar por: Más recientes',
			'sort_name' => 'Ordenar por: Nombre',
			'sort_oldest' => 'Ordenar por: Más antiguos',
			'clear_filters' => 'Limpiar filtros',
			'download_product_catalog' => 'Descargar Ficha Completa de Productos',
			'show_filters' => 'Mostrar filtros',
			'showing' => 'Mostrando',
			'of' => 'de',
			'no_products_found' => 'No encontramos productos',
			'remove_filters_message' => 'Prueba quitando alguno de los filtros para volver a ver todo el catálogo.',
			'loading_product_information' => 'Cargando información del producto...',
			'product_infographic_unavailable' => 'La infografía de este producto aún no está disponible.',
		);

		foreach ($translations as $key => $label) {
			$item = $this->getDbConnection()->createCommand()
				->select('id')
				->from('menu_items')
				->where('`key` = :key', array(
					':key' => $key,
				))
				->queryRow();

			if ($item) {
				$this->insert('menu_item_translations', array(
					'menu_item_id' => $item['id'],
					'language_id' => 1,
					'label' => $label,
					'created_at' => $now,
					'updated_at' => null,
				));
			}
		}
	}
}
