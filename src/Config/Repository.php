<?php
/**
 * This file is part of Reflect\Autoloader package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reflect\Autoloader\Config;

use Reflect\Autoloader\Matcher\Rule;
use Reflect\Autoloader\Matcher\RuleMatcherInterface;

/**
 * Class Repository
 * @package Reflect\Autoloader\Config
 */
class Repository implements
    RepositoryInterface,
    RuleMatcherInterface
{
    use ConfigGetter;
    use ConfigMatcherBridge;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * Repository constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @return Rule
     */
    public function rule(): Rule
    {
        $rule     = new Rule();

        $this->config[$rule->getProtocol()->getName()] = $rule;

        return $rule;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->config;
    }
}