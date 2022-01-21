<?php

namespace Creode\AvatarBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;

class AvatarBundle extends AbstractPimcoreBundle
{
    use PackageVersionTrait;

    protected function getComposerPackageName(): string
    {
        // getVersion() will use this name to read the version from
        // PackageVersions and return a normalized value
        return 'creode/pimcore-bundle-avatar';
    }

    public function getJsPaths()
    {
        return [
            '/bundles/avatar/js/pimcore/startup.js'
        ];
    }

    public function getInstaller()
    {
        return $this->container->get(Tools\Installer::class);
    }
}
