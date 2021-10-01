<?php

namespace Escherchia\ProcessEngineCore\Engine\Actions;

use Escherchia\ProcessEngineCore\Contracts\ActionInterface;
use Escherchia\ProcessEngineCore\Model\Elements\ElementInterface;

class Transition extends ActionAbstract implements ActionInterface
{
    /**
     * @var ElementInterface
     */
    private $targetElement;

    /**
     * @var ElementInterface
     */
    private $currentElement;

    /**
     * @param array $params
     * @return bool
     */
    protected function validateParams(array $params = array()): bool
    {
        return true;
    }

    /**
     *
     */
    public function run(): void
    {
        $model = $this->getEngine()->getModel();

        if ($currentElement = $model->getElement($this->params['currentActivityKey'])) {
            $this->currentElement = $currentElement;
        }
        if ($targetElement = $model->getElement($this->params['targetActivityKey'])) {
            $this->targetElement = $targetElement;
        }

        if ($this->currentElement->getStatus() == ElementInterface::STATUS_ACTIVE) {
            $this->currentElement->updateStatus(ElementInterface::STATUS_DONE);
            if (!array_key_exists($this->targetElement->getName(), $this->currentElement->getTargets())) {
                throw new \Exception('target element of transition is not available in source element.');
            }
            $this->targetElement->updateStatus(ElementInterface::STATUS_ACTIVE);
        }  else {
            throw new \Exception('current element is not in active status');
        }
    }

    public static function getActionKey(): string
    {
        return 'transition';
    }
}
