<?php

namespace Escherchia\PhpWorkflowCore\Engine\Actions;

use Escherchia\PhpWorkflowCore\Contracts\ActionInterface;
use Escherchia\PhpWorkflowCore\Contracts\EngineInterface;
use Escherchia\PhpWorkflowCore\Exceptions\ActionParametersIsNotValid;
use Illuminate\Notifications\Action;

abstract class ActionAbstract
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var EngineInterface
     */
    protected $engine;

    /**
     * @param array $params
     * @throws ActionParametersIsNotValid
     */
    public function __construct(array $params = array())
    {
        $this->setParams($params);
    }

    /**
     * @param array $params
     * @throws ActionParametersIsNotValid
     */
    protected function setParams(array $params = array())
    {
        if (!$this->validateParams()) {
            throw new ActionParametersIsNotValid();
        }
        $this->params = $params;
    }

    /**
     * @param EngineInterface $engine
     */
    public function setEngine(EngineInterface $engine): void
    {
        $this->engine = $engine;
    }

    /**
     * @return EngineInterface
     */
    protected function getEngine(): EngineInterface
    {
        return $this->engine;
    }

    /**
     * @param array $params
     * @return bool
     */
    abstract protected function validateParams(array $params = array()): bool;
}
