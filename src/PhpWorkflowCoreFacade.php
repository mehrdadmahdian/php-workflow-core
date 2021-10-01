<?php

namespace Escherchia\PhpWorkflowCore;

use Escherchia\PhpWorkflowCore\Contracts\ModelInterface;
use Escherchia\PhpWorkflowCore\Engine\Engine;
use Escherchia\PhpWorkflowCore\Model\Model;
use Escherchia\PhpWorkflowCore\Model\ModelBuilder;

class PhpWorkflowCoreFacade
{
    /**
     * @throws Exceptions\ProcessModelConfigurationIsNotValid
     */
    public static function buildProcessModel(array $configuration): Model
    {
        return ModelBuilder::buildFromArray($configuration);
    }

    /**
     * @param string $action
     * @param array $params
     * @throws Exceptions\ActionNotFoundException
     */
    public static function runEngineAction(ModelInterface $model, string $action, array $params = array()): ModelInterface
    {
        $engine = new Engine($model);
        $engine->runAction($action, $params);

        return $engine->getModel();
    }

    /**
     * @param ModelInterface $model
     * @param string $activityKey
     * @return array
     */
    public static function getActivityActions(ModelInterface $model, string $activityKey): array
    {
        $activity = $model->getElement($activityKey);
        return $activity->getActions();
    }
}
