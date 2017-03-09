<?php
/**
 * This file is part of Reflect\Autoloader package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader;

use Composer\Autoload\ClassLoader as ComposerClassLoader;
use Reflect\Autoloader\Config\ConfigMatcherBridge;
use Reflect\Autoloader\Config\Repository;
use Reflect\Autoloader\Config\RepositoryInterface;
use Reflect\Autoloader\Matcher\Rule;
use Reflect\Autoloader\Matcher\RuleMatcherInterface;

/**
 * Class ClassLoader
 * @package Reflect\Autoloader
 */
class ClassLoader implements RuleMatcherInterface
{
    use ConfigMatcherBridge;

    /**
     * @var ComposerClassLoader
     */
    private $loader;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * ReflectClassLoader constructor.
     * @param ComposerClassLoader $loader
     * @param array               $config
     */
    public function __construct(ComposerClassLoader $loader, array $config = [])
    {
        $this->loader = $loader;
        $this->repository = new Repository($config);

        $this->register(true);
    }

    /**
     * @param bool $prepend
     * @return $this
     */
    public function register(bool $prepend = true)
    {
        $this->registerAllProtocols();

        spl_autoload_register([$this, 'loadClass'], true, $prepend);

        return $this;
    }

    /**
     * @return void
     */
    private function registerAllProtocols()
    {
        /** @var Rule $rule */
        foreach ($this->repository as $rule) {
            $rule->getProtocol()->register();
        }
    }

    /**
     * @return $this
     */
    public function unregister()
    {
        spl_autoload_unregister([$this, 'loadClass']);

        $this->unregisterAllProtocols();

        return $this;
    }

    /**
     * @return void
     */
    private function unregisterAllProtocols()
    {
        /** @var Rule $rule */
        foreach ($this->repository as $rule) {
            $rule->getProtocol()->unregister();
        }
    }

    /**
     * @param string $class
     * @return mixed
     */
    public function loadClass(string $class)
    {
        if ($this->isSameNamespace($class)) {
            return false;
        }

        $rule = $this->getRule($class);

        if ($rule !== null) {
            $file = $rule->decorate($this->loader->findFile($class));

            return require_file($file);
        }

        return false;
    }

    /**
     * @param string $class
     * @return bool
     */
    private function isSameNamespace(string $class): bool
    {
        return 0 === strpos($class, 'Reflect\\');
    }

    /**
     * @param string $class
     * @return null|Rule
     */
    private function getRule(string $class)
    {
        /** @var Rule $rule */
        foreach ($this->repository as $rule) {
            if ($rule->check($class)) {
                return $rule;
            }
        }

        return null;
    }

    /**
     * @return Rule
     */
    protected function rule(): Rule
    {
        return $this->repository->rule();
    }
}