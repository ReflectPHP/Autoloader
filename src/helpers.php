<?php
/**
 * This file is part of Reflect\Autoloader package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader {
    /**
     * Scope isolated include.
     * Prevents access to $this/self from included files.
     *
     * @param string $file
     * @return mixed
     */
    function require_file(string $file)
    {
        return require $file;
    }
}