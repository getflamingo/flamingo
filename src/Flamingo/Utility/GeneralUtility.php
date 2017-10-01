<?php

namespace Flamingo\Utility;

/**
 * Class GeneralUtility
 * This file is part of the TYPO3 CMS project.
 * https://github.com/TYPO3/TYPO3.CMS/blob/master/typo3/sysext/core/Classes/Utility/GeneralUtility.php
 *
 * @package Flamingo\Utility
 */
class GeneralUtility
{
    /**
     * Check for item in list
     * Check if an item exists in a comma-separated list of items.
     *
     * @param string $list
     * @param string $item
     * @return bool
     */
    public static function inList($list, $item)
    {
        return strpos(',' . $list . ',', ',' . $item . ',') !== false;
    }
}