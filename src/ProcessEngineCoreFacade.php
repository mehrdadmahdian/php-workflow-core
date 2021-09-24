<?php

namespace Escherchia\ProcessEngineCore;

use Escherchia\ProcessEngineCore\Contracts\ModelInterface;
use Escherchia\ProcessEngineCore\Engine\Engine;
use Escherchia\ProcessEngineCore\Model\Model;
use Escherchia\ProcessEngineCore\Model\ModelBuilder;

class ProcessEngineCoreFacade
{
    /**
     * @throws Exceptions\ProcessModelConfigurationIsNotValid
     */
    public function buildProcessModel(array $configuration): Model
    {
        return ModelBuilder::buildFromArray($configuration);
    }

    /**
     * @param string $action
     * @param array $params
     */
    public function runEngineAction(ModelInterface $model, string $action, array $params = array())
    {
        $engine = new Engine($model);
        $engine->runAction($action, $params);
    }
}
