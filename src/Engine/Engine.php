<?php

namespace Escherchia\ProcessEngineCore\Engine;

use Escherchia\ProcessEngineCore\Contracts\ActionInterface;
use Escherchia\ProcessEngineCore\Contracts\EngineInterface;
use Escherchia\ProcessEngineCore\Contracts\ModelInterface;
use Escherchia\ProcessEngineCore\Engine\Actions\Start;
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
    public function runAction(string $action, array $params = array()): void
    {
        $actionTypeClass = static::getActionClass($action);

        /** @var ActionInterface $actionTypeClass */
        $action =  new $actionTypeClass($params);
        $action->setEngine($this);

        $action->run();
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
            'start'      => Start::class,
        ];

        if (isset($list[$type])) {
            return $list[$type];
        }

        throw new ActionNotFoundException();
    }



}
