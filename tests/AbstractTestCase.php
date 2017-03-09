<?php
/**
 * This file is part of Reflect\Autoloader package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTestCase
 * @package Tests
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @param string $suffix
     * @return string
     */
    protected function dir(string $suffix = '')
    {
        return str_replace('\\', '/', __DIR__) . $suffix;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function res(string $name)
    {
        return $this->dir('/resources/' . $name);
    }
}