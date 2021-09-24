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

        if ($currentElement = $model->getElement($this->params['current_element_key'])) {
            $this->currentElement = $currentElement;
        }
        if ($targetElement = $model->getElement($this->params['target_element_key'])) {
            $this->targetElement = $targetElement;
        }

        if ($this->currentElement->getStatus() == ElementInterface::STATUS_ACTIVE) {
            $this->currentElement->updateStatus(ElementInterface::STATUS_DONE);
            $this->targetElement->updateStatus(ElementInterface::STATUS_ACTIVE);
        }  else {
            throw new \Exception('current element is not in active status');
        }
    }
}
