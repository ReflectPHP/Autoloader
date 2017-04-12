<?php
/**
 * This file is part of Reflect\Streaming package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader\Rules;

use Reflect\Autoloader\Support\Str;

/**
 * Class MatchClass
 * @package Reflect\Autoloader\Rules
 */
class MatchClass implements Comparator
{
    /**
     * @var string
     */
    private $class;

    /**
     * MatchClass constructor.
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * @param string $class
     * @param string $file
     * @return bool
     */
    public function compare(string $class, string $file): bool
    {
        return Str::endsWith($class, '\\' . $this->class);
    }

}
