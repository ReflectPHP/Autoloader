<?php
/**
 * This file is part of Reflect\Autoloader package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader\Matcher;

/**
 * Interface RuleComparatorInterface
 * @package Reflect\Autoloader\Matcher
 */
interface RuleResolverInterface
{
    /**
     * @param string $class
     * @return bool
     */
    public function check(string $class): bool;

    /**
     * @param string $file
     * @return string
     */
    public function decorate(string $file): string;
}