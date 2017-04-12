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
 * Class Group
 * @package Reflect\Autoloader\Rules
 */
class Group implements Comparator, \Countable
{
    /**
     * @var array|Comparator[]
     */
    private $comparators = [];

    /**
     * Group constructor.
     * @param Comparator[] ...$comparators
     */
    public function __construct(Comparator ...$comparators)
    {
        $this->addComparator(...$comparators);
    }

    /**
     * @param Comparator[] ...$comparators
     * @return $this|Group
     */
    public function addComparator(Comparator ...$comparators): Group
    {
        foreach ($comparators as $comparator) {
            $this->comparators[] = $comparator;
        }

        return $this;
    }

    /**
     * @param string $class
     * @param string $file
     * @return bool
     */
    public function compare(string $class, string $file): bool
    {
        foreach ($this->comparators as $comparator) {
            if ($comparator->compare($class, $file)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->comparators);
    }
}
