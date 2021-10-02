<?php

namespace MehrdadMahdian\PhpWorkflowCore\Model;

use MehrdadMahdian\PhpWorkflowCore\Contracts\ModelElementContainerInterface;
use MehrdadMahdian\PhpWorkflowCore\Contracts\ModelInterface;
use MehrdadMahdian\PhpWorkflowCore\Model\Elements\ElementInterface;

class Model implements ModelInterface
{
    /**
     * @var ModelElementContainerInterface
     */
    private $modelElementContainer;

    /**
     * @param ModelElementContainerInterface $modelElementContainer
     */
    public function __construct(ModelElementContainerInterface $modelElementContainer )
    {
        $this->modelElementContainer = $modelElementContainer;
    }

    /**
     * @param ElementInterface $element
     */
    public function addElement(ElementInterface $element): void
    {
        $this->modelElementContainer->add($element);
    }

    /**
     * @param string $key
     * @return null|ElementInterface
     */
    public function getElement(string $key): ?ElementInterface
    {
        return $this->modelElementContainer->get($key);
    }

    /**
     * @return ModelElementContainerInterface
     */
    public function getModelElementContainer(): ModelElementContainerInterface
    {
        return $this->modelElementContainer;
    }
}
