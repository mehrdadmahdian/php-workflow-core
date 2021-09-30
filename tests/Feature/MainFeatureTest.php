<?php

use App\ActivitySampleObserver;
use Escherchia\ProcessEngineCore\Contracts\ActionInterface;
use Escherchia\ProcessEngineCore\Contracts\ActivityObserverInterface;
use Escherchia\ProcessEngineCore\Contracts\ModelInterface;
use Escherchia\ProcessEngineCore\Engine\Actions\ActionAbstract;
use Escherchia\ProcessEngineCore\Model\Elements\Activity;
use Escherchia\ProcessEngineCore\Model\Elements\ElementInterface;
use Escherchia\ProcessEngineCore\ProcessEngineCoreFacade;
use PHPUnit\Framework\TestCase;

class MainFeatureTest extends TestCase
{
    /**
     * @test
     */
    public function it_tests_model_builder_for_simple_process()
    {
        $configuration = [
            'activities' => [
                [
                    'name' => 'act1',
                    'sources' => [],
                    'targets' => ['act2'],
                    'status'  => ElementInterface::STATUS_INACTIVE,
                    'observers' => [MySampleObserver::class],
                    'extra-actions' => [MyCustomAction::class]

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

        $this->assertInstanceOf(ModelInterface::class, $model);
        $this->assertCount(3,  $model->getModelElementContainer()->all());
        $this->assertInstanceOf(ElementInterface::class,  $model->getElement('act1'));
        $this->assertNull($model->getElement('a1234123ct1'));
    }
}

class MyCustomAction extends ActionAbstract implements ActionInterface {

    protected function validateParams(array $params = array()): bool
    {
        return true;
    }

    public static function getActionKey(): string
    {
        return 'custom';
    }

    public function run(): void
    {
        dd('here');
    }
}

class MySampleObserver implements ActivityObserverInterface {

    public function update(Activity $activity): void
    {
        // TODO: Implement update() method.
    }
}
