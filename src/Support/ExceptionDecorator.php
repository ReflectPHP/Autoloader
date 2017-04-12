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
 * Class ExceptionDecorator
 * @package Reflect\Autoloader\Support
 */
class ExceptionDecorator
{
    /**
     * @var \Throwable
     */
    private $exception;

    /**
     * @var \ReflectionClass
     */
    private $reflection;

    /**
     * ExceptionDecorator constructor.
     * @param \Throwable $e
     * @throws \ReflectionException
     */
    public function __construct(\Throwable $e)
    {
        $this->exception = $e;

        $this->exception->decorated = '';
        $this->exception->original  = '';

        $this->reflection = new \ReflectionClass($this->exception);
    }

    /**
     * @param string $code
     * @return ExceptionDecorator
     */
    public function injectDecoratedCode(string $code): ExceptionDecorator
    {
        $this->exception->decorated = $this->getLines($code);

        return $this;
    }

    /**
     * @param string $code
     * @return ExceptionDecorator
     */
    public function injectOriginalCode(string $code): ExceptionDecorator
    {
        $this->exception->original = $this->getLines($code);

        return $this;
    }

    /**
     * @param string $code
     * @param int $size
     * @return string
     */
    private function getLines(string $code, int $size = 2): string
    {
        $line  = (int)$this->exception->getLine();
        $lines = explode("\n", str_replace("\r", '', $code));

        $result = [''];

        for ($i = $line - $size; $i <= $line + $size; $i++) {
            if (isset($lines[$i - 1])) {
                $codeLine = ' ' . $i . ' | ' . $lines[$i - 1];

                if ($i === $line) {
                    $codeLine .= ' <--- Error on line ' . $line;
                }

                $result[] = $codeLine;
            }
        }

        $result[] = '';

        return implode("\n", $result);
    }

    /**
     * @return \Throwable
     */
    public function getException(): \Throwable
    {
        return $this->exception;
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflection(): \ReflectionClass
    {
        return $this->reflection;
    }

    /**
     * @param \Closure $then
     * @return $this|ExceptionDecorator
     */
    public function file(\Closure $then): ExceptionDecorator
    {
        $this->setProperty('file', $then);

        return $this;
    }

    /**
     * @param \Closure $then
     * @return $this|ExceptionDecorator
     */
    public function trace(\Closure $then): ExceptionDecorator
    {
        $this->setProperty('trace', function (array $trace) use ($then) {
            $result = [];

            foreach ($trace as $line) {
                $result[] = $then($line);
            }

            return $result;
        });

        return $this;
    }

    /**
     * @param string $propertyName
     * @param \ReflectionClass|null $class
     * @return null|\ReflectionProperty
     */
    private function getProperty(string $propertyName, \ReflectionClass $class = null)
    {
        if ($class === null) {
            $class = $this->reflection;
        }

        if ($class->hasProperty($propertyName)) {
            return $class->getProperty($propertyName);
        }

        if ($parent = $class->getParentClass()) {
            return $this->getProperty($propertyName, $parent);
        }

        return null;
    }

    /**
     * @param string $propertyName
     * @param \Closure $resolver
     */
    private function setProperty(string $propertyName, \Closure $resolver)
    {
        $property = $this->getProperty($propertyName);

        if ($property !== null) {
            $property->setAccessible(true);
            $original = $property->getValue($this->exception);
            $property->setValue($this->exception, $resolver($original));
        }
    }
}
