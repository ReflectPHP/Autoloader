<?php
/**
 * This file is part of Reflect\Autoloader package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader\Config;

/**
 * Class ConfigGetter
 * @package Reflect\Autoloader\Config
 * @mixin RepositoryInterface
 */
trait ConfigGetter
{
    /**
     * @return array
     */
    private function getAllConfig(): array
    {
        return $this->all();
    }

    /**
     * @param string $key
     * @param null   $default
     * @param string $delimiter
     * @return array|mixed
     */
    public function get(string $key, $default = null, string $delimiter = '.')
    {
        $config = $this->getAllConfig();

        if (! $this->accessible($config)) {
            return $this->value($default);
        }

        if ($this->exists($config, $key)) {
            return $config[$key];
        }

        return $this->deepGet($key, $default, $delimiter);
    }

    /**
     * @param string $key
     * @param null   $default
     * @param string $delimiter
     * @return array|mixed
     */
    private function deepGet(string $key, $default = null, string $delimiter = '.')
    {
        $config = $this->getAllConfig();

        foreach (explode($delimiter, $key) as $segment) {
            if ($this->accessible($config) && $this->exists($config, $segment)) {
                $config = $config[$segment];
            } else {
                return $this->value($default);
            }
        }

        return $config;
    }

    /**
     * @param array  $array
     * @param string $key
     * @return bool
     */
    private function exists(array $array, string $key): bool
    {
        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * @param $value
     * @return mixed
     */
    private function value($value)
    {
        return $value instanceof \Closure ? $value() : $value;
    }

    /**
     * @param $value
     * @return bool
     */
    private function accessible($value): bool
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    /**
     * @return \Traversable|\Generator
     */
    public function getIterator(): \Generator
    {
        foreach ($this->getAllConfig() as $namespace => $protocol) {
            yield $namespace => $protocol;
        }
    }
}