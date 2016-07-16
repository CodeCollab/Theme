<?php declare(strict_types=1);

namespace CodeCollabTest\Unit\Theme;

use CodeCollab\Theme\Theme;
use CodeCollab\Theme\Loader;
use CodeCollab\Theme\NotFoundException;
use CodeCollab\Theme\InvalidException;
use CodeCollab\Theme\DirectoryTraversalException;

class ThemeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers CodeCollab\Theme\Theme::__construct
     */
    public function testImplementsCorrectInterface()
    {
        $this->assertInstanceOf(Loader::class, new Theme(TEST_DATA_DIR, 'valid'));
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::isPathValid
     */
    public function testSetThemeThrowsInvalidPath()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The theme cannot be found.');

        new Theme(TEST_DATA_DIR, 'notfound');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::validateTheme
     */
    public function testSetThemeThrowsOnMissingConfigFile()
    {
        $this->expectException(InvalidException::class);
        $this->expectExceptionMessage('The theme (`missingconfig`) is missing the configuration file (`info.json`).');

        new Theme(TEST_DATA_DIR, 'missingconfig');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::validateTheme
     */
    public function testSetThemeThrowsOnInvalidConfigFile()
    {
        $this->expectException(InvalidException::class);
        $this->expectExceptionMessage('The theme\'s (`invalidconfig`) configuration file (`info.json`) does not contain valid json or could not be read.');

        new Theme(TEST_DATA_DIR, 'invalidconfig');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::validateTheme
     */
    public function testSetThemeThrowsOnInvalidThemeMissingName()
    {
        $this->expectException(InvalidException::class);
        $this->expectExceptionMessage('The theme\'s (`missingname`) configuration file (`info.json`) is missing the required theme name property.');

        new Theme(TEST_DATA_DIR, 'missingname');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::validateTheme
     */
    public function testSetThemeThrowsOnInvalidThemeMissingDescription()
    {
        $this->expectException(InvalidException::class);
        $this->expectExceptionMessage('The theme\'s (`missingdescription`) configuration file (`info.json`) is missing the required theme description property.');

        new Theme(TEST_DATA_DIR, 'missingdescription');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::validateTheme
     */
    public function testSetThemeThrowsOnInvalidThemeMissingVersion()
    {
        $this->expectException(InvalidException::class);
        $this->expectExceptionMessage('The theme\'s (`missingversion`) configuration file (`info.json`) is missing the required theme version property.');

        new Theme(TEST_DATA_DIR, 'missingversion');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::isPathValid
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::validateTheme
     * @covers CodeCollab\Theme\Theme::load
     */
    public function testLoadThrowsOnThemeFileNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The template file (`/doesntexist.phtml`) could not be found in the theme.');

        (new Theme(TEST_DATA_DIR, 'valid'))->load('/doesntexist.phtml');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::isPathValid
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::validateTheme
     * @covers CodeCollab\Theme\Theme::load
     */
    public function testLoadNoChild()
    {
        $this->assertSame(
            TEST_DATA_DIR . '/valid/exists.phtml',
            (new Theme(TEST_DATA_DIR, 'valid'))->load('/exists.phtml')
        );
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::isPathValid
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::validateTheme
     * @covers CodeCollab\Theme\Theme::load
     */
    public function testLoadChildFound()
    {
        $this->assertSame(
            TEST_DATA_DIR . '/child/exists.phtml',
            (new Theme(TEST_DATA_DIR, 'child'))->load('/exists.phtml')
        );
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::isPathValid
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::validateTheme
     * @covers CodeCollab\Theme\Theme::load
     */
    public function testLoadChildFoundInParent()
    {
        $this->assertSame(
            TEST_DATA_DIR . '/valid/existsinparent.phtml',
            (new Theme(TEST_DATA_DIR, 'child'))->load('/existsinparent.phtml')
        );
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::isPathValid
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::validateTheme
     * @covers CodeCollab\Theme\Theme::load
     */
    public function testLoadChildFoundNowhere()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The template file (`/doesntexist.phtml`) could not be found in the theme.');

       (new Theme(TEST_DATA_DIR, 'child'))->load('/doesntexist.phtml');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::isPathValid
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::validateTheme
     * @covers CodeCollab\Theme\Theme::load
     */
    public function testLoadFileFromChildThrowsOnDirectoryTraversal()
    {
        $this->expectException(DirectoryTraversalException::class);
        $this->expectExceptionMessage('Trying to load a file outside of the theme directory.');

        (new Theme(TEST_DATA_DIR, 'child'))->load('/../../bootstrap.php');
    }
}
