<?php
/**
 * This file is part of Reflect\Autoloader package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader\Support;

/**
 * Class Str
 * @package Reflect\Autoloader\Support
 */
class Str
{
    /**
     * Generate a truly random alpha-numeric string.
     *
     * @param int $length
     * @return string
     */
    public static function random(int $length = 8): string
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
            $sequence = str_replace(['/', '+', '='], '', base64_encode(random_bytes($size)));
            $string .= substr($sequence, 0, $size);
        }

        return $string;
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param string $haystack
     * @param string[] ...$needles
     * @return bool
     */
    public static function startsWith(string $haystack, string ...$needles): bool
    {
        foreach ($needles as $needle) {
            if ($needle !== '' && 0 === strpos($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param string $haystack
     * @param string[] ...$needles
     * @return bool
     */
    public static function endsWith(string $haystack, string ...$needles): bool
    {
        foreach ($needles as $needle) {
            if (substr($haystack, -strlen($needle)) === (string)$needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $haystack
     * @param string[] ...$needles
     * @return bool
     */
    public static function contains(string $haystack, string ...$needles): bool
    {
        foreach ($needles as $needle) {
            if (strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }
}
