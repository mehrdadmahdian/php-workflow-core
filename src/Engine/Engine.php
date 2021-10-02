<?php

namespace MehrdadMahdian\PhpWorkflowCore\Engine;

use MehrdadMahdian\PhpWorkflowCore\Contracts\ActionInterface;
use MehrdadMahdian\PhpWorkflowCore\Contracts\EngineInterface;
use MehrdadMahdian\PhpWorkflowCore\Contracts\ModelInterface;
use MehrdadMahdian\PhpWorkflowCore\Engine\Actions\Start;
use MehrdadMahdian\PhpWorkflowCore\Engine\Actions\Transition;
use MehrdadMahdian\PhpWorkflowCore\Exceptions\ActionNotFoundException;

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
     * @throws \MehrdadMahdian\PhpWorkflowCore\Exceptions\ActionNotFoundException
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

        if (class_exists($type) and in_array(ActionInterface::class, class_implements($type))) {
            return $type;
        } else {
            if (isset($list[$type])) {
                return $list[$type];
            }
        }

        throw new ActionNotFoundException();
    }



}
