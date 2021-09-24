<?php

namespace Escherchia\ProcessEngineCore\Engine\Actions;

class Transition
{
    /**
     * @var array
     */
    private $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     *
     */
    public function run(): void
    {

    }
}
