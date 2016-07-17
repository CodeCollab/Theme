<?php declare(strict_types=1);
/**
 * Exception which gets thrown when trying to load a theme or file that does not exist
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
 * Exception which gets thrown when trying to load a theme or file that does not exist
 *
 * @category   CodeCollab
 * @package    Theme
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
class NotFoundException extends \Exception
{
}
