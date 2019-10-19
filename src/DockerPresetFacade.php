<?php

namespace Tonysm\DockerPreset;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tonysm\DockerPreset\Skeleton\SkeletonClass
 */
class DockerPresetFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'docker-preset';
    }
}
