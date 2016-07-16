<?php declare(strict_types=1);
/**
 * Theme and child theme manager
 *
 * PHP version 7.0
 *
 * @category   CodeCollab
 * @package    Theme
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2015 Pieter Hordijk <https://github.com/PeeHaa>
 * @license    See the LICENSE file
 * @version    1.0.0
 */
namespace CodeCollab\Theme;

/**
 * Theme and child theme manager
 *
 * @category   CodeCollab
 * @package    Theme
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
class Theme implements Loader
{
    const CONFIG_REQUIRED_FIELDS = ['name', 'description', 'version'];
    /**
     * @var string Base path for themes
     */
    private $themePath;

    /**
     * @var array The main theme
     */
    private $theme;

    /**
     * @var null|array The child theme
     */
    private $childTheme;

    /**
     * Creates instance and sets the themes
     *
     * @param string $themePath Base path for themes
     * @param string $theme     The name of the active theme
     */
    public function __construct(string $themePath, string $theme)
    {
        $this->themePath = rtrim($themePath, '/');

        $this->setTheme($theme);
    }

    /**
     * Sets a theme
     *
     * @param string $theme The name of the theme to set
     *
     * @throws \CodeCollab\Theme\NotFoundException When the theme cannot be found
     * @throws \CodeCollab\Theme\InvalidException  When the theme is not valid
     */
    public function setTheme(string $theme)
    {
        if (!$this->isPathValid($theme)) {
            throw new NotFoundException('The theme cannot be found.');
        }

        $this->validateTheme($theme);

        $themeInfo = $this->getThemeInfo($theme);

        if (array_key_exists('parent', $themeInfo)) {
            $this->childTheme = $themeInfo;

            $this->setTheme($themeInfo['parent']);
        } else {
            $this->theme = $themeInfo;
        }
    }

    /**
     * Gets the theme info (based on the theme's info.json file)
     *
     * @param string $theme The name of the theme to get the info for
     *
     * @return array List containing the theme information
     */
    private function getThemeInfo(string $theme): array
    {
        return json_decode(file_get_contents($this->themePath . '/' . $theme . '/info.json'), true);
    }

    /**
     * Checks whether the theme directory exists
     *
     * @param string $theme The name of the theme
     *
     * @return bool True when the directory of the theme exists
     */
    private function isPathValid(string $theme): bool
    {
        if (!is_dir(rtrim($this->themePath, '/') . '/' . $theme)) {
            return false;
        }

        return true;
    }

    /**
     * Checks whether the layout of the theme directory is valid
     *
     * @param string $theme The name of the theme
     *
     * @throws \CodeCollab\Theme\InvalidException  When the theme is not valid
     */
    private function validateTheme(string $theme)
    {
        if (!file_exists($this->themePath . '/' . $theme . '/info.json')) {
            throw new InvalidException('The theme (`' . $theme . '`) is missing the configuration file (`info.json`).');
        }

        try {
            $themeInfo = $this->getThemeInfo($theme);
        } catch (\Throwable $e) {
            throw new InvalidException('The theme\'s (`' . $theme . '`) configuration file (`info.json`) does not contain valid json or could not be read.');
        }

        foreach (self::CONFIG_REQUIRED_FIELDS as $requiredField) {
            if (!array_key_exists($requiredField, $themeInfo)) {
                throw new InvalidException('The theme\'s (`' . $theme . '`) configuration file (`info.json`) is missing the required theme ' . $requiredField . ' property.');
            }
        }
    }

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
    public function load(string $file): string
    {
        if ($this->childTheme && file_exists($this->themePath . '/' . $this->childTheme['name'] . $file)) {
            return $this->themePath . '/' . $this->childTheme['name'] . $file;
        }

        if (!file_exists($this->themePath . '/' . $this->theme['name'] . $file)) {
            throw new NotFoundException('The template file (`' . $file . '`) could not be found in the theme.');
        }

        return $this->themePath . '/' . $this->theme['name'] . $file;
    }
}
