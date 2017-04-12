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
 * Class MatchName
 * @package Reflect\Autoloader\Rules
 */
class MatchName implements Comparator
{
    /**
     * @var string
     */
    private $name;

    /**
     * MatchClass constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $class
     * @param string $file
     * @return bool
     */
    public function compare(string $class, string $file): bool
    {
        return Str::contains($class, $this->name);
    }

}
