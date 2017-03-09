<?php
/**
 * This file is part of Reflect\Autoloader package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader\Matcher;

/**
 * Class Comparator
 * @package Reflect\Autoloader\Matcher
 */
abstract class Comparator
{
    /**
     * @param string $classPath
     * @return bool
     */
    final public function __invoke(string $classPath): bool
    {
        return $this->compare($classPath);
    }

    /**
     * @param string $classPath
     * @return bool
     */
    abstract public function compare(string $classPath): bool;
}