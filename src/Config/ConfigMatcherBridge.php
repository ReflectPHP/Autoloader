<?php
/**
 * This file is part of Reflect\Autoloader package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader\Config;

use Reflect\Autoloader\Matcher\Comparator;
use Reflect\Autoloader\Matcher\Rule;
use Reflect\Autoloader\Matcher\RuleMatcherInterface;

/**
 * Class ConfigMatcherBridge
 * @package Reflect\Autoloader\Config
 * @mixin RuleMatcherInterface
 */
trait ConfigMatcherBridge
{
    /**
     * @return Rule
     */
    protected function rule(): Rule
    {
        return new Rule();
    }

    /**
     * @param string $prefix
     * @return RuleMatcherInterface|Rule
     */
    public function namespace(string $prefix): RuleMatcherInterface
    {
        return $this->rule()->namespace($prefix);
    }

    /**
     * @param string $name
     * @return RuleMatcherInterface|Rule
     */
    public function class(string $name): RuleMatcherInterface
    {
        return $this->rule()->class($name);
    }

    /**
     * @param string $regex
     * @return RuleMatcherInterface|Rule
     */
    public function match(string $regex): RuleMatcherInterface
    {
        return $this->rule()->match($regex);
    }

    /**
     * @param Comparator $comparator
     * @return RuleMatcherInterface
     */
    public function compare(Comparator $comparator): RuleMatcherInterface
    {
        return $this->rule()->compare($comparator);
    }
}