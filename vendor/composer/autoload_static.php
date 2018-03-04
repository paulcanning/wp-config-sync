<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0b167967516b82e4a66a0d2d8568da74
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\Yaml\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\Yaml\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/yaml',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0b167967516b82e4a66a0d2d8568da74::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0b167967516b82e4a66a0d2d8568da74::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
