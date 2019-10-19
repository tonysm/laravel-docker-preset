<?php

namespace Tonysm\DockerPreset;

use Illuminate\Filesystem\Filesystem;

class DockerPreset
{
    /**
     * @throws \Tonysm\DockerPreset\AlreadyInstalledException
     */
    public static function install()
    {
        $files = new Filesystem;

        if ($files->isDirectory(base_path('resources/docker'))) {
            throw new AlreadyInstalledException();
        }

        static::copyDockerPresets($files);
        static::updateEnv($files);
    }

    private static function copyDockerPresets(Filesystem $files)
    {
        if (! $files->exists(base_path('Makefile'))) {
            $files->copy(
                dirname(__DIR__).'/resources/Makefile',
                base_path('Makefile')
            );
        }

        $files->copy(
            dirname(__DIR__).'/resources/docker-compose.yml',
            base_path('docker-compose.yml')
        );

        $files->copy(
            dirname(__DIR__).'/resources/Dockerfile',
            base_path('Dockerfile')
        );

        $files->copyDirectory(
            dirname(__DIR__).'/resources/docker',
            base_path('resources/docker/')
        );
    }

    private static function updateEnv(Filesystem $files)
    {
        $files->put(
            base_path('.env'),
            str_replace(
                'DB_DATABASE=127.0.0.1',
                'DB_DATABASE=db',
                $files->get(base_path('.env'))
            )
        );

        $files->put(
            base_path('phpunit.xml'),
            str_replace(
                '<server name="CACHE_DRIVER" value="array"/>',
                "<server name=\"CACHE_DRIVER\" value=\"array\"/>\n        <server name=\"DB_HOST\" value=\"dbtesting\"/>",
                $files->get(base_path('phpunit.xml'))
            )
        );
    }
}
