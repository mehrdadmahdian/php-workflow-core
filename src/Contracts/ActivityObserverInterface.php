<?php

namespace Escherchia\PhpWorkflowCore\Contracts;

use Escherchia\PhpWorkflowCore\Model\Elements\Activity;

interface ActivityObserverInterface
{
    /**
     * @param Activity $activity
     */
    public function update(Activity $activity): void;
}
