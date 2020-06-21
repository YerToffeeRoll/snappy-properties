<?php

namespace App\Configuration;

class Config
{
    protected const CONFIG = [
        'public_dir' => __DIR__ . '/../../public',
        'database_driver' => 'pdo_mysql',
        'image_directory' => '../public/assets/images',
        'entity' => [
            'App\Entity\Property' => [
                'table' => 'properties',
                'relationship' => [
                    'name' => 'propertyType',
                    'joinColumn' => 'propertyTypeId'
                ]
            ],
            'App\Entity\PropertyType' => [
                'table' => 'propertyTypes'
            ]
        ]
    ];

    /**
     * @param string $path
     * @return array|mixed|null
     */
    public static function get(string $path)
    {
        $keys = explode('.', $path);

        $value = self::CONFIG;

        foreach ($keys as $key) {
            $value = $value[$key] ?? null;
        }

        return $value;
    }
}
