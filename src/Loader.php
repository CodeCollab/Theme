<?php declare(strict_types=1);
/**
 * Interface for file loaders (from themes)
 *
 * PHP version 7.0
 *
 * @category   CodeCollab
 * @package    Theme
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2015 Pieter Hordijk <https://github.com/PeeHaa>
 * @license    See the LICENSE file
 * @version    1.0.1
 */
namespace CodeCollab\Theme;

/**
 * Interface for file loaders (from themes)
 *
 * @category   CodeCollab
 * @package    Theme
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
interface Loader
{
    /**
     * Loads a file from the theme
     *
     * This method will first try to load the file from the child theme (if available)
     *
     * @param string $file The file to load (relative to the theme directory)
     *
     * @return string The filename to load
     *
     * @throws \CodeCollab\Theme\NotFoundException When the file cannot be found in either the theme or the child theme
     */
    public function load(string $file): string;
}
