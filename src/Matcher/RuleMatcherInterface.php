<?php
/**
 * This file is part of Reflect\Autoloader package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader\Matcher;

/**
 * Interface RuleMatcherInterface
 * @package Reflect\Autoloader\Matcher
 */
interface RuleMatcherInterface
{
    /**
     * @param string $prefix
     * @return RuleMatcherInterface
     */
    public function namespace(string $prefix): RuleMatcherInterface;

    /**
     * @param string $name
     * @return RuleMatcherInterface
     */
    public function class(string $name): RuleMatcherInterface;

    /**
     * @param string $regex
     * @return RuleMatcherInterface
     */
    public function match(string $regex): RuleMatcherInterface;

    /**
     * @param Comparator $comparator
     * @return RuleMatcherInterface
     */
    public function compare(Comparator $comparator): RuleMatcherInterface;
}
