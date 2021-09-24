<?php

namespace Escherchia\ProcessEngineCore\Model\Elements;

class Activity extends ElementAbstract implements ElementInterface
{
    /**
     * @param string $name
     * @param string $status
     */
    public function __construct(string $name, string $status)
    {
        $this->setName($name);
        $this->setStatus($status);
    }


}
