<?php

namespace Escherchia\ProcessEngineCore\Contracts;

use Escherchia\ProcessEngineCore\Model\Elements\Activity;

interface ActivityObserverInterface
{
    /**
     * @param Activity $activity
     */
    public function update(Activity $activity): void;
}