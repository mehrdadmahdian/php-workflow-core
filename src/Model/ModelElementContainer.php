<?php

namespace MehrdadMahdian\PhpWorkflowCore\Model;

use MehrdadMahdian\PhpWorkflowCore\Contracts\ModelElementContainerInterface;
use MehrdadMahdian\PhpWorkflowCore\Model\Elements\ElementInterface;

class ModelElementContainer implements ModelElementContainerInterface
{
    /**
     * @var array
     */
    private $elements;

    /**
     * @param ElementInterface $element
     * @return mixed|void
     */
    public function add(ElementInterface $element)
    {
        $this->elements[$element->getName()] = $element;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->elements;
    }

    /**
     * @param string $key
     * @return null|ElementInterface
     */
    public function get(string $key): ?ElementInterface
    {
        if (isset($this->elements[$key])) {
            return $this->elements[$key];
        }

        return null;
    }

    /**
     * @param string $key
     */
    public function remove(string $key): void
    {
        if (isset($this->elements[$key])) {
            unset($this->elements[$key]);
        }
    }

}
