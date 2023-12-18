<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite498d8a29e85b8c65b7bda570846fef2
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/Twilio',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite498d8a29e85b8c65b7bda570846fef2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite498d8a29e85b8c65b7bda570846fef2::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
