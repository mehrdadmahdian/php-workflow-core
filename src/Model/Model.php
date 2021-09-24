<?php

namespace Escherchia\ProcessEngineCore\Model;

use Escherchia\ProcessEngineCore\Contracts\ModelElementContainerInterface;
use Escherchia\ProcessEngineCore\Contracts\ModelInterface;
use Escherchia\ProcessEngineCore\Model\Elements\ElementInterface;

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
     * @return ElementInterface
     */
    public function getElement(string $key): ElementInterface
    {
        return $this->modelElementContainer->get($key);
    }
}
