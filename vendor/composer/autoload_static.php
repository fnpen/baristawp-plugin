<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit576d0b6f1db655d9479ec642cdc3b701
{
    public static $files = array (
        '49a1299791c25c6fd83542c6fedacddd' => __DIR__ . '/..' . '/yahnis-elsts/plugin-update-checker/load-v4p11.php',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit576d0b6f1db655d9479ec642cdc3b701::$classMap;

        }, null, ClassLoader::class);
    }
}
