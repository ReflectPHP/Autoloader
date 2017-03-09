<?php
/**
 * This file is part of Reflect\Autoloader package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader\Matcher;

use Reflect\Streaming\Protocol;
use Reflect\Streaming\ProtocolInterface;
use Reflect\Streaming\Decorator\DecoratorInterface;

/**
 * Class Rule
 * @package Reflect\Autoloader\Matcher
 *
 * @method RuleMatcherInterface|Rule orNamespace(string $prefix)
 * @method RuleMatcherInterface|Rule orClass(string $name)
 * @method RuleMatcherInterface|Rule orMatch(string $regex)
 * @method RuleMatcherInterface|Rule orCompare(Comparator $comparator)
 */
class Rule implements RuleMatcherInterface, RuleResolverInterface, DecoratorInterface
{
    /**
     * @var ProtocolInterface
     */
    private $protocol;

    /**
     * @var array|\Closure[]
     */
    private $rules = [];

    /**
     * Matcher constructor.
     * @param ProtocolInterface $protocol
     */
    public function __construct(ProtocolInterface $protocol = null)
    {
        $this->protocol = $protocol ?? $this->createProtocol();

        $this->protocol->register();
    }

    /**
     * @return ProtocolInterface
     */
    public function getProtocol(): ProtocolInterface
    {
        return $this->protocol;
    }

    /**
     * @return ProtocolInterface
     */
    private function createProtocol(): ProtocolInterface
    {
        return new Protocol($this->randomProtocolName());
    }

    /**
     * @return string
     */
    protected function randomProtocolName(): string
    {
        $hash   = md5((string)random_int(PHP_INT_MIN, PHP_INT_MAX));
        $suffix = substr($hash, 0, 8);

        return 'reflect' . $suffix;
    }

    /**
     * @param string $prefix
     * @return string
     */
    private function normalizeNamespace(string $prefix): string
    {
        if ($prefix[0] === '\\') {
            return substr($prefix, 1);
        }

        return $prefix;
    }

    /**
     * @param string $classPath
     * @return string
     */
    private function getClassName(string $classPath): string
    {
        $parts = explode('\\', $classPath);

        return end($parts);
    }

    /**
     * @param string $prefix
     * @return RuleMatcherInterface|Rule
     */
    public function namespace(string $prefix): RuleMatcherInterface
    {
        $prefix = $this->normalizeNamespace($prefix);

        $this->rules[] = function ($actual) use ($prefix) {
            return 0 === strpos($actual, $prefix);
        };

        return $this;
    }

    /**
     * @param string $className
     * @return RuleMatcherInterface|Rule
     */
    public function class(string $className): RuleMatcherInterface
    {
        $this->rules[] = function ($actual) use ($className) {
            return $this->getClassName($actual) === $className;
        };

        return $this;
    }

    /**
     * @param Comparator $comparator
     * @return RuleMatcherInterface|Rule
     */
    public function compare(Comparator $comparator): RuleMatcherInterface
    {
        $this->rules[] = $comparator;

        return $this;
    }

    /**
     * @param string $regex
     * @return RuleMatcherInterface|Rule
     */
    public function match(string $regex): RuleMatcherInterface
    {
        $pattern = sprintf('/^%s$/isu', $regex);

        $this->rules[] = function ($actual) use ($pattern) {
            return preg_match($pattern, $actual);
        };

        return $this;
    }

    /**
     * @param string $class
     * @return bool
     */
    public function check(string $class): bool
    {
        foreach ($this->rules as $rule) {
            if ($rule($class)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $file
     * @return string
     */
    public function decorate(string $file): string
    {
        return $this->protocol->resource($file);
    }

    /**
     * @param \Closure $callback
     * @return DecoratorInterface|Rule
     */
    public function restream(\Closure $callback): DecoratorInterface
    {
        $this->protocol->restream($callback);

        return $this;
    }

    /**
     * @param \Closure $callback
     * @return DecoratorInterface|Rule
     */
    public function opening(\Closure $callback): DecoratorInterface
    {
        $this->protocol->opening($callback);

        return $this;
    }

    /**
     * @param \Closure $callback
     * @return DecoratorInterface|Rule
     */
    public function overwrite(\Closure $callback): DecoratorInterface
    {
        $this->protocol->overwrite($callback);

        return $this;
    }

    /**
     * @param \Closure $callback
     * @return DecoratorInterface|Rule
     */
    public function resolved(\Closure $callback): DecoratorInterface
    {
        $this->protocol->resolved($callback);

        return $this;
    }

    /**
     * @return $this
     */
    public function cleanOutDecorators()
    {
        $this->protocol->cleanOutDecorators();

        return $this;
    }


    /**
     * @param string $name
     * @param array  $arguments
     * @return RuleMatcherInterface
     * @throws \BadMethodCallException
     */
    public function __call(string $name, array $arguments = []): RuleMatcherInterface
    {
        if (0 === strpos($name, 'or')) {
            $method = strtolower(substr($name, 2));

            if (method_exists($this, $method)) {
                return $this->$method(...$arguments);
            }
        }

        throw new \BadMethodCallException('Method ' . $name . ' not exists');
    }
}