<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4baaaaaf9e1dc0493d694420e0bf21c1
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Modules\\FacebookPostingModule\\' => 30,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Modules\\FacebookPostingModule\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Modules/FacebookPostingModule/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4baaaaaf9e1dc0493d694420e0bf21c1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4baaaaaf9e1dc0493d694420e0bf21c1::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4baaaaaf9e1dc0493d694420e0bf21c1::$classMap;

        }, null, ClassLoader::class);
    }
}
