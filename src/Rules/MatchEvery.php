<?php
/**
 * This file is part of Reflect\Autoloader package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader\Rules;

/**
 * Class MatchEvery
 * @package Reflect\Autoloader\Rules
 */
class MatchEvery implements Comparator
{
    /**
     * @param string $class
     * @param string $file
     * @return bool
     */
    public function compare(string $class, string $file): bool
    {
        return true;
    }
}
