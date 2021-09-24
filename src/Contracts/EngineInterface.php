<?php

namespace Escherchia\ProcessEngineCore\Contracts;

interface EngineInterface
{
    /**
     * @param ModelInterface $model
     */
    public function __construct(ModelInterface $model);

    /**
     * @return ModelInterface
     */
    public function getModel(): ModelInterface;

    /**
     * @param string $action
     * @param array $params
     */
    public function runAction(string $action, array $params = array()): void;
}
