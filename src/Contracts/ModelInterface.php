<?php

namespace MehrdadMahdian\PhpWorkflowCore\Contracts;

use MehrdadMahdian\PhpWorkflowCore\Model\Elements\ElementInterface;

interface ModelInterface
{
    /**
     * @param ModelElementContainerInterface $modelElementContainer
     */
    public function __construct(ModelElementContainerInterface $modelElementContainer);

    /**
     * @param ElementInterface $element
     */
    public function addElement(ElementInterface $element): void;

    /**
     * @param string $key
     * @return null|ElementInterface
     */
    public function getElement(string $key): ?ElementInterface;

    /**
     * @return ModelElementContainerInterface
     */
    public function getModelElementContainer(): ModelElementContainerInterface;

}
