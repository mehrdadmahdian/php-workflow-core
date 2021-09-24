<?php

namespace Escherchia\ProcessEngineCore\Engine;

use Escherchia\ProcessEngineCore\Contracts\ActionInterface;
use Escherchia\ProcessEngineCore\Contracts\EngineInterface;
use Escherchia\ProcessEngineCore\Contracts\ModelInterface;
use Escherchia\ProcessEngineCore\Engine\Actions\Transition;
use Escherchia\ProcessEngineCore\Exceptions\ActionNotFoundException;

class Engine implements EngineInterface
{
    /**
     * @var ModelInterface
     */
    private $model;

    /**
     * @param ModelInterface $model
     */
    public function __construct(ModelInterface $model)
    {
        $this->model = $model;
    }

    /**
     * @return ModelInterface
     */
    public function getModel(): ModelInterface
    {
        return $this->model;
    }

    /**
     * @param string $action
     * @param array $params
     * @throws \Escherchia\ProcessEngineCore\Exceptions\ActionNotFoundException
     */
    public function runAction(string $action, array $params): void
    {
        $action = $this->actionHandlerFactory($action, $params);
        $action->run();
    }

    /**
     * @param string $type
     * @param array $params
     * @return ActionInterface
     * @throws ActionNotFoundException
     */
    private function actionHandlerFactory(string $type, array $params = array()): ActionInterface
    {
        $actionTypeClass = static::getActionClass($type);

        /** @var ActionInterface $actionTypeClass */
        $action =  new $actionTypeClass($params);
        $action->setEngine($this);

        return $action;
    }

    /**
     * @param string $type
     * @return string
     * @throws ActionNotFoundException
     */
    private static function getActionClass(string $type): string
    {
        $list = [
            'transition' => Transition::class,
        ];

        if (isset($list[$type])) {
            return $list[$type];
        }

        throw new ActionNotFoundException();
    }



}
