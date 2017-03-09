<?php
/**
 * This file is part of Reflect\Autoloader package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader\Config;

/**
 * Interface RepositoryInterface
 * @package Reflect\Autoloader\Config
 */
interface RepositoryInterface extends \IteratorAggregate
{
    /**
     * @param string $key
     * @param null   $default
     * @param string $delimiter
     * @return mixed
     */
    public function get(string $key, $default = null, string $delimiter = '.');

    /**
     * @return array
     */
    public function all(): array;
}