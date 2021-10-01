<?php

use Escherchia\ProcessEngineCore\Contracts\ActionInterface;
use Escherchia\ProcessEngineCore\Contracts\ActivityObserverInterface;
use Escherchia\ProcessEngineCore\Engine\Actions\ActionAbstract;
use Escherchia\ProcessEngineCore\Model\Elements\Activity;
use Escherchia\ProcessEngineCore\Model\Elements\ElementInterface;
use Escherchia\ProcessEngineCore\ProcessEngineCoreFacade;
use PHPUnit\Framework\TestCase;

class EngineActionTest extends TestCase
{
    /**
     * @test
     */
    public function it_tests_engine_to_start_and_workflow()
    {
        $configuration = [
            'activities' => [
                [
                    'name' => 'act1',
                    'sources' => [],
                    'targets' => ['act2'],
                    'status'  => ElementInterface::STATUS_INACTIVE,
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
        $model = ProcessEngineCoreFacade::buildProcessModel($configuration);
        $this->assertEquals(ElementInterface::STATUS_INACTIVE, $model->getElement('act1')->getStatus());
        $newModel = ProcessEngineCoreFacade::runEngineAction($model, 'start');
        $this->assertEquals(ElementInterface::STATUS_ACTIVE, $newModel->getElement('act1')->getStatus());
        $this->assertEquals(ElementInterface::STATUS_INACTIVE, $newModel->getElement('act2')->getStatus());
        $this->assertEquals(ElementInterface::STATUS_INACTIVE, $newModel->getElement('act3')->getStatus());
    }

    /**
     * @test
     */
    public function it_tests_engine_to_run_transition_action()
    {
        $configuration = [
            'activities' => [
                [
                    'name' => 'act1',
                    'sources' => [],
                    'targets' => ['act2'],
                    'status'  => ElementInterface::STATUS_ACTIVE,
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
        $model = ProcessEngineCoreFacade::buildProcessModel($configuration);
        $this->assertEquals(ElementInterface::STATUS_ACTIVE, $model->getElement('act1')->getStatus());
        $this->assertEquals(ElementInterface::STATUS_INACTIVE, $model->getElement('act2')->getStatus());
        $this->assertEquals(ElementInterface::STATUS_INACTIVE, $model->getElement('act3')->getStatus());
        $newModel = ProcessEngineCoreFacade::runEngineAction($model, 'transition', ['currentActivityKey' => 'act1', 'targetActivityKey' => 'act2']);
        $this->assertEquals(ElementInterface::STATUS_DONE, $newModel->getElement('act1')->getStatus());
        $this->assertEquals(ElementInterface::STATUS_ACTIVE, $newModel->getElement('act2')->getStatus());
        $this->assertEquals(ElementInterface::STATUS_INACTIVE, $newModel->getElement('act3')->getStatus());

        $newModel = ProcessEngineCoreFacade::runEngineAction($newModel, 'transition', ['currentActivityKey' => 'act2', 'targetActivityKey' => 'act3']);
        $this->assertEquals(ElementInterface::STATUS_DONE, $newModel->getElement('act1')->getStatus());
        $this->assertEquals(ElementInterface::STATUS_DONE, $newModel->getElement('act2')->getStatus());
        $this->assertEquals(ElementInterface::STATUS_ACTIVE, $newModel->getElement('act3')->getStatus());

        $this->expectExceptionMessage('target element of transition is not available in source element.');
        ProcessEngineCoreFacade::runEngineAction($newModel, 'transition', ['currentActivityKey' => 'act3', 'targetActivityKey' => 'act1']);
    }

    /**
     * @test
     */
    public function it_tests_engine_to_run_custom_action()
    {
        $configuration = [
            'activities' => [
                [
                    'name' => 'act1',
                    'sources' => [],
                    'targets' => ['act2'],
                    'status'  => ElementInterface::STATUS_ACTIVE,
                    'extra-actions'  => [
                        NewCustomAction::class
                    ],
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
        $model = ProcessEngineCoreFacade::buildProcessModel($configuration);
        $this->expectExceptionMessage('custom action call is working');
        ProcessEngineCoreFacade::runEngineAction($model, NewCustomAction::class, ['currentActivityKey' => 'act1']);
    }
}

class NewCustomAction extends ActionAbstract implements ActionInterface {

    protected function validateParams(array $params = array()): bool
    {
        return true;
    }

    public static function getActionKey(): string
    {
        return 'this_is_the_key';
    }

    public function run(): void
    {
        throw new \Exception('custom action call is working.');
    }
}