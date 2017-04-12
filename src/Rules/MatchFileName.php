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
 * Class MatchFileName
 * @package Reflect\Autoloader\Rules
 */
class MatchFileName implements Comparator
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * MatchFileName constructor.
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @param string $class
     * @param string $file
     * @return bool
     */
    public function compare(string $class, string $file): bool
    {
        return $this->fileName === $file ||
            Str::endsWith($file, DIRECTORY_SEPARATOR . $this->fileName);
    }

}
