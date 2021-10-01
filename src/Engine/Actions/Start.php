<?php

namespace Escherchia\PhpWorkflowCore\Engine\Actions;

use Escherchia\PhpWorkflowCore\Contracts\ActionInterface;
use Escherchia\PhpWorkflowCore\Model\Elements\ElementInterface;

class Start extends ActionAbstract  implements ActionInterface
{
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
        /** @var ElementInterface $element */
        foreach ($model->getModelElementContainer()->all() as $element) {
            if (count($element->getSources()) == 0) {
                if ($element->getStatus() == ElementInterface::STATUS_INACTIVE) {
                    $element->updateStatus(ElementInterface::STATUS_ACTIVE);
                }
            }
        }
    }

    public static function getActionKey(): string
    {
        return 'start';
    }
}
