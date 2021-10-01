<?php

namespace Escherchia\PhpWorkflowCore\Contracts;

use Escherchia\PhpWorkflowCore\Model\Elements\ElementInterface;

interface ModelElementContainerInterface
{
    /**
     * @param $element
     * @return mixed
     */
    public function add(ElementInterface $element);

    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param string $key
     * @return null|ElementInterface
     */
    public function get(string $key): ?ElementInterface;

    /**
     * @param string $key
     */
    public function remove(string $key): void;

}
