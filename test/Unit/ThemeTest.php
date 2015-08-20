<?php declare(strict_types=1);

namespace CodeCollabTest\Unit\Theme;

use CodeCollab\Theme\Theme;

class ThemeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers CodeCollab\Theme\Theme::__construct
     */
    public function testImplementsCorrectInterface()
    {
        $this->assertInstanceOf('CodeCollab\Theme\Loader', new Theme(TEST_DATA_DIR, 'valid'));
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::isPathValid
     */
    public function testSetThemeThrowsInvalidPath()
    {
        $this->setExpectedException('CodeCollab\Theme\NotFoundException');

        new Theme(TEST_DATA_DIR, 'notfound');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::isThemeValid
     */
    public function testSetThemeThrowsOnMissingConfigFile()
    {
        $this->setExpectedException('CodeCollab\Theme\InvalidException');

        new Theme(TEST_DATA_DIR, 'missingconfig');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::isThemeValid
     */
    public function testSetThemeThrowsOnInvalidThemeMissingName()
    {
        $this->setExpectedException('CodeCollab\Theme\InvalidException');

        new Theme(TEST_DATA_DIR, 'missingname');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::isThemeValid
     */
    public function testSetThemeThrowsOnInvalidThemeMissingDescription()
    {
        $this->setExpectedException('CodeCollab\Theme\InvalidException');

        new Theme(TEST_DATA_DIR, 'missingdescription');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::isThemeValid
     */
    public function testSetThemeThrowsOnInvalidThemeMissingType()
    {
        $this->setExpectedException('CodeCollab\Theme\InvalidException');

        new Theme(TEST_DATA_DIR, 'missingtype');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::isThemeValid
     */
    public function testSetThemeThrowsOnInvalidThemeMissingVersion()
    {
        $this->setExpectedException('CodeCollab\Theme\InvalidException');

        new Theme(TEST_DATA_DIR, 'missingversion');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::isPathValid
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::isThemeValid
     * @covers CodeCollab\Theme\Theme::load
     */
    public function testLoadThrowsOnThemeFileNotFound()
    {
        $this->setExpectedException('CodeCollab\Theme\NotFoundException');

        (new Theme(TEST_DATA_DIR, 'valid'))->load('/doesntexist.phtml');
    }

    /**
     * @covers CodeCollab\Theme\Theme::__construct
     * @covers CodeCollab\Theme\Theme::setTheme
     * @covers CodeCollab\Theme\Theme::isPathValid
     * @covers CodeCollab\Theme\Theme::getThemeInfo
     * @covers CodeCollab\Theme\Theme::isThemeValid
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
     * @covers CodeCollab\Theme\Theme::isThemeValid
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
     * @covers CodeCollab\Theme\Theme::isThemeValid
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
     * @covers CodeCollab\Theme\Theme::isThemeValid
     * @covers CodeCollab\Theme\Theme::load
     */
    public function testLoadChildFoundNowhere()
    {
        $this->setExpectedException('CodeCollab\Theme\NotFoundException');

       (new Theme(TEST_DATA_DIR, 'child'))->load('/doesntexist.phtml');
    }
}
