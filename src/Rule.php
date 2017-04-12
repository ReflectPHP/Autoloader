<?php
/**
 * This file is part of Reflect\Autoloader package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader;

use LogicException;
use Reflect\Autoloader\Rules\Comparator;
use Reflect\Autoloader\Rules\MatchClassRegex;
use Reflect\Autoloader\Rules\MatchEvery;
use Reflect\Autoloader\Rules\Group;
use Reflect\Autoloader\Rules\MatchClass;
use Reflect\Autoloader\Rules\MatchFileName;
use Reflect\Autoloader\Rules\MatchFileRegex;
use Reflect\Autoloader\Rules\MatchName;
use Reflect\Autoloader\Rules\MatchNamespace;
use Reflect\Autoloader\Support\ExceptionDecorator;
use Reflect\Autoloader\Support\Str;
use Reflect\Streaming\Protocol\ProtocolInterface;
use Reflect\Streaming\Protocol\ProtocolReadEventsInterface;
use Reflect\Streaming\Stream;

/**
 * Class Rule
 * @package Reflect\Autoloader
 */
class Rule implements Comparator
{
    /**
     * @var ProtocolInterface|ProtocolReadEventsInterface
     */
    private $protocol;

    /**
     * @var Group
     */
    private $group;

    /**
     * @var ClassLoader
     */
    private $loader;

    /**
     * @var bool
     */
    private $booted = false;

    /**
     * Rule constructor.
     * @param ClassLoader $loader
     * @param ProtocolInterface|null $protocol
     * @throws \InvalidArgumentException
     */
    public function __construct(ClassLoader $loader, ProtocolInterface $protocol = null)
    {
        $this->protocol = $protocol ?? $this->createStream();
        $this->loader = $loader;
        $this->group = new Group();
    }

    /**
     * @param string $file
     * @return mixed
     * @throws \Throwable
     */
    public function require(string $file)
    {
        $sources = '';

        if (!$this->booted) {
            $this->booted = true;

            $this->protocol->subscribe(function(string $source, string $file) use (&$sources) {
                $sources = $source;
                return $source;
            });
        }

        try {
            require $this->protocol->path($file);
        } catch (\Throwable $e) {
            throw $this->decorateException($sources, $e);
        }

        return true;
    }

    /**
     * @param string $sources
     * @param \Throwable $e
     * @return \Throwable
     * @throws \ReflectionException
     */
    private function decorateException(string $sources, \Throwable $e): \Throwable
    {
        $decorator = new ExceptionDecorator($e);

        $decorator->file(function (string $file) {
            return $this->decorateFilePath($file);
        });

        $decorator->trace(function (array $trace) {
            $trace['file'] = $this->decorateFilePath($trace['file'] ?? '');

            return $trace;
        });

        $decorator->injectDecoratedCode($sources);
        $decorator->injectOriginalCode(file_get_contents($e->getFile()));


        return $decorator->getException();
    }


    /**
     * @param string $path
     * @return string
     */
    private function decorateFilePath(string $path): string
    {
        $name = $this->protocol->getName();

        return str_replace($name . '://', '', $path);
    }

    /**
     * @return ProtocolInterface
     * @throws \InvalidArgumentException
     */
    private function createStream(): ProtocolInterface
    {
        return Stream::create(Str::random(8));
    }

    /**
     * @return Rule
     */
    public function ever(): Rule
    {
        return $this->comparedBy(new MatchEvery());
    }

    /**
     * @param string $class
     * @param string $file
     * @return bool
     */
    public function compare(string $class, string $file): bool
    {
        if ($this->group->compare($class, $file)) {
            return true;
        }

        return false;
    }

    /**
     * @param Comparator[] ...$comparators
     * @return $this|Rule
     */
    public function comparedBy(Comparator ...$comparators): Rule
    {
        $this->group->addComparator(...$comparators);

        return $this;
    }

    /**
     * @param string $name
     * @return Rule
     */
    public function name(string $name): Rule
    {
        return $this->comparedBy(new MatchName($name));
    }

    /**
     * @param string $namespace
     * @return Rule
     */
    public function inNamespace(string $namespace): Rule
    {
        return $this->comparedBy(new MatchNamespace($namespace));
    }

    /**
     * @param string $name
     * @return Rule
     */
    public function className(string $name): Rule
    {
        return $this->comparedBy(new MatchClass($name));
    }

    /**
     * @param string $fileName
     * @return Rule
     */
    public function fileName(string $fileName): Rule
    {
        return $this->comparedBy(new MatchFileName($fileName));
    }

    /**
     * @param string $regex
     * @return Rule
     */
    public function matchFile(string $regex): Rule
    {
        return $this->comparedBy(new MatchFileRegex($regex));
    }

    /**
     * @param string $regex
     * @return Rule
     */
    public function matchClass(string $regex): Rule
    {
        return $this->comparedBy(new MatchClassRegex($regex));
    }

    /**
     * @param \Closure $then
     * @return ClassLoader
     * @throws \LogicException
     */
    public function then(\Closure $then): ClassLoader
    {
        if ($this->group->count() === 0) {
            throw new LogicException('Rule are empty: No available comparators');
        }

        $this->protocol->subscribe($then);

        return $this->loader;
    }
}
