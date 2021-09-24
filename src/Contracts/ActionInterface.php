<?php

namespace Escherchia\ProcessEngineCore\Contracts;

interface ActionInterface
{
    /**
     * @param array $params
     */
    public function __construct(array $params);

    /**
     * @param EngineInterface $engine
     */
    public function setEngine(EngineInterface $engine):void;

    /**
     *
     */
    public function run():void;
}