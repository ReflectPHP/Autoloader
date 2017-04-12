<?php
/**
 * This file is part of Reflect\Autoloader package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader\Rules;

use Reflect\Autoloader\Support\Str;

/**
 * Class MatchNamespace
 * @package Reflect\Autoloader\Rules
 */
class MatchNamespace implements Comparator
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * MatchNamespace constructor.
     * @param string $namespace
     */
    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;

        if (Str::startsWith($this->namespace, '\\')) {
            $this->namespace = substr($this->namespace, 1);
        }
    }

    /**
     * @param string $class
     * @param string $file
     * @return bool
     */
    public function compare(string $class, string $file): bool
    {
        return Str::startsWith($class, $this->namespace . '\\');
    }

}
