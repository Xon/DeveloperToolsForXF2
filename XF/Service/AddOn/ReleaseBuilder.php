<?php

namespace TickTackk\DeveloperTools\XF\Service\AddOn;

use XF\Util\File as FileUtil;
use TickTackk\DeveloperTools\XF\Entity\AddOn as ExtendedAddOnEntity;
use function in_array;

/**
 * Class ReleaseBuilder
 *
 * @package TickTackk\DeveloperTools
 */
class ReleaseBuilder extends XFCP_ReleaseBuilder
{
    protected function prepareFilesToCopy()
    {
        parent::prepareFilesToCopy();

        $ds = \XF::$DS;
        $buildRoot = $this->buildRoot;
        $licenseAdded = false;
        $readmeAdded = false;

        /** @var ExtendedAddOnEntity $addOnEntity */
        $addOnEntity = $this->addOn->getInstalledAddOn();

        if ($addOnEntity)
        {
            $developerOptions = $addOnEntity->DeveloperOptions;
            if (!empty($developerOptions['license']))
            {
                FileUtil::writeFile($buildRoot . $ds . 'LICENSE.md', $developerOptions['license'], false);
                $licenseAdded = true;
            }

            if (!empty($developerOptions['readme']))
            {
                FileUtil::writeFile($buildRoot . $ds . 'README.md', $developerOptions['readme'], false);
                $readmeAdded = true;
            }
        }

        if (!$licenseAdded)
        {
            $this->copyFileToBuildRoot('LICENSE', ['md', '', 'txt', 'html']);
        }

        if (!$readmeAdded)
        {
            $this->copyFileToBuildRoot('README', ['md', '', 'txt', 'html']);
        }
    }

    /**
     * @param array|string $possibleFileName
     * @param array|string $possibleExtensions
     */
    protected function copyFileToBuildRoot($possibleFileName, $possibleExtensions)
    {
        $ds = \XF::$DS;
        $addOnRoot = $this->addOnRoot;
        $buildRoot = $this->buildRoot;
        
        foreach ((array) $possibleFileName AS $fileName)
        {
            foreach ((array) $possibleExtensions AS $possibleExtension)
            {
                $possibleFileNameFinal= $fileName;
                if (!empty($possibleExtension))
                {
                    $possibleFileNameFinal .= '.' . $possibleExtension;
                }

                if (file_exists($buildRoot . $ds . $possibleFileNameFinal))
                {
                    return;
                }
            }
        }

        foreach ((array) $possibleFileName AS $fileName)
        {
            foreach ((array) $possibleExtensions AS $possibleExtension)
            {
                $possibleFileNameFinal= $fileName;
                if (!empty($possibleExtension))
                {
                    $possibleFileNameFinal .= '.' . $possibleExtension;
                }
                $filePath = $addOnRoot . $ds . $possibleFileNameFinal;

                if (file_exists($filePath) && is_readable($filePath))
                {
                    FileUtil::copyFile($filePath, $buildRoot . $ds . $possibleFileNameFinal);
                    return;
                }
            }
        }
    }

    /**
     * @return array
     *
     * @noinspection PhpUnused
     */
    protected function getExcludedDirectories()
    {
        return array_merge([
            '_repo'
        ], parent::getExcludedDirectories());
    }

    /**
     * @param $fileName
     *
     * @return bool
     *
     * @noinspection PhpUnused
     */
    protected function isExcludedFileName($fileName)
    {
        if (in_array($fileName, ['git.json', 'dev.json']))
        {
            return true;
        }

        return parent::isExcludedFileName($fileName);
    }
}