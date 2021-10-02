<?php

use MehrdadMahdian\PhpWorkflowCore\Contracts\ActivityObserverInterface;
use MehrdadMahdian\PhpWorkflowCore\Model\Elements\Activity;
use MehrdadMahdian\PhpWorkflowCore\Model\Elements\ElementInterface;
use MehrdadMahdian\PhpWorkflowCore\PhpWorkflowCoreFacade;
use PHPUnit\Framework\TestCase;

class ObserverCallTest extends TestCase
{
    /**
     * @test
     */
    public function it_test_engine_to_call_registered_observers_after_status_update()
    {
        $configuration = [
            'activities' => [
                [
                    'name' => 'act1',
                    'sources' => [],
                    'targets' => ['act2'],
                    'status'  => ElementInterface::STATUS_INACTIVE,
                    'observers' => [MySampleObserver::class],

                ],
                [
                    'name' => 'act2',
                    'sources' => ['act1'],
                    'targets' => ['act3'],
                    'status'  => ElementInterface::STATUS_INACTIVE
                ],
                [
                    'name' => 'act3',
                    'sources' => ['act2'],
                    'targets' => [],
                    'status'  => ElementInterface::STATUS_INACTIVE
                ],
            ]
        ];
        $model = PhpWorkflowCoreFacade::buildProcessModel($configuration);
        $this->expectException(MyException::class);
        $this->expectExceptionMessage('this is from my observer');
        $model->getElement('act1')->updateStatus(ElementInterface::STATUS_ACTIVE);
    }
}

class MySampleObserver implements ActivityObserverInterface {

    public function update(Activity $activity): void
    {
        throw new MyException('this is from my observer');
    }
}

class MyException extends \Exception {
}
