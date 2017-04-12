<?php
/**
 * This file is part of Reflect\Autoloader package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader;

use Composer\Autoload\ClassLoader as Composer;

/**
 * Class ClassLoader
 * @package Reflect\Autoloader
 */
class ClassLoader
{
    /**
     * Self namespace prefix for ignore rewriting of own sources
     */
    const NAMESPACE_PREFIX = 'Reflect\\';

    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var array|Rule[]
     */
    private $rules = [];

    /**
     * ClassLoader constructor.
     * @param Composer $composer
     */
    public function __construct(Composer $composer)
    {
        $this->composer = $composer;

        $this->register(true);
    }

    /**
     * @param bool $prepend
     * @return $this|ClassLoader
     */
    public function register(bool $prepend = true): ClassLoader
    {
        spl_autoload_register([$this, 'loadClass'], true, $prepend);

        return $this;
    }

    /**
     * @return $this|ClassLoader
     */
    public function unregister(): ClassLoader
    {
        spl_autoload_unregister([$this, 'loadClass']);

        return $this;
    }

    /**
     * @return Rule
     * @throws \InvalidArgumentException
     */
    public function when(): Rule
    {
        $rule = new Rule($this);

        $this->rules[] = $rule;

        return $rule;
    }

    /**
     * @param string $class
     * @return bool
     * @throws \Throwable
     */
    public function loadClass(string $class)
    {
        if ($this->isSameNamespace($class)) {
            return false;
        }

        $file = $this->composer->findFile($class);

        if (!is_string($file)) {
            return false;
        }

        foreach ($this->rules as $rule) {
            if ($rule->compare($class, $file)) {
                $rule->require($file);
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $class
     * @return bool
     */
    private function isSameNamespace(string $class): bool
    {
        return 0 === strpos($class, static::NAMESPACE_PREFIX);
    }
}
