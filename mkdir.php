<?php

$directories = array(
    'assets',

    'images',
    'images/about',
    'images/brands',
    'images/brands-section',
    'images/business',
    'images/categories',
    'images/hero-slides',
    'images/intro',
    'images/products',

    'protected/runtime'
);

foreach ($directories as $directory) {

    if (!is_dir($directory)) {

        if (!mkdir($directory, 0755, true)) {
            echo "ERROR: No se pudo crear la carpeta: {$directory}" . PHP_EOL;
            exit(1);
        }

        echo "CREATED: {$directory}" . PHP_EOL;
    } else {

        echo "EXISTS: {$directory}" . PHP_EOL;
    }
}

echo "Directory setup completed successfully." . PHP_EOL;
