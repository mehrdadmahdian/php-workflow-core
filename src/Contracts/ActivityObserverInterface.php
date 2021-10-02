<?php

namespace MehrdadMahdian\PhpWorkflowCore\Contracts;

use MehrdadMahdian\PhpWorkflowCore\Model\Elements\Activity;

interface ActivityObserverInterface
{
    /**
     * @param Activity $activity
     */
    public function update(Activity $activity): void;
}
