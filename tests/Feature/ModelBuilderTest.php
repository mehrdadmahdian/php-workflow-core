<?php

use Escherchia\PhpWorkflowCore\Contracts\ActionInterface;
use Escherchia\PhpWorkflowCore\Contracts\ModelInterface;
use Escherchia\PhpWorkflowCore\Engine\Actions\ActionAbstract;
use Escherchia\PhpWorkflowCore\Model\Elements\ElementInterface;
use Escherchia\PhpWorkflowCore\PhpWorkflowCoreFacade;
use PHPUnit\Framework\TestCase;


class ModelBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function it_tests_building_a_basic_model_from_configuration_array()
    {
        $configuration = [
            'activities' => [
                [
                    'name' => 'act1',
                    'sources' => [],
                    'targets' => ['act2'],
                    'status' => ElementInterface::STATUS_INACTIVE
                ],
                [
                    'name' => 'act2',
                    'sources' => ['act1'],
                    'targets' => ['act3'],
                    'status' => ElementInterface::STATUS_INACTIVE
                ],
                [
                    'name' => 'act3',
                    'sources' => ['act2'],
                    'targets' => [],
                    'status' => ElementInterface::STATUS_INACTIVE
                ],
            ]
        ];
        $model = PhpWorkflowCoreFacade::buildProcessModel($configuration);

        $this->assertInstanceOf(ModelInterface::class, $model);
        $this->assertCount(3, $model->getModelElementContainer()->all());
        $this->assertInstanceOf(ElementInterface::class, $model->getElement('act1'));
        $this->assertNull($model->getElement('a1234123ct1'));

        /** @var ElementInterface $element */
        foreach ($model->getModelElementContainer()->all() as $element) {
            $this->assertEquals(ElementInterface::STATUS_INACTIVE, $element->getStatus());

        }
    }

    /**
     * @test
     */
    public function it_tests_building_a_basic_model_with_correct_source_and_targets()
    {
        $configuration = [
            'activities' => [
                [
                    'name' => 'act1',
                    'sources' => [],
                    'targets' => ['act2'],
                    'status' => ElementInterface::STATUS_INACTIVE
                ],
                [
                    'name' => 'act2',
                    'sources' => ['act1'],
                    'targets' => ['act3'],
                    'status' => ElementInterface::STATUS_INACTIVE
                ],
                [
                    'name' => 'act3',
                    'sources' => ['act2'],
                    'targets' => [],
                    'status' => ElementInterface::STATUS_INACTIVE
                ],
            ]
        ];
        $model = PhpWorkflowCoreFacade::buildProcessModel($configuration);
        $act1Element = $model->getElement('act1');

        $this->assertCount(0, $act1Element->getSources());
        $this->assertCount(0, $act1Element->getSources());
        /** @var ElementInterface $targetElement */
        foreach ($act1Element->getTargets() as $targetElement) {
            $this->assertEquals($targetElement->getName(), 'act2');

            $this->assertInstanceOf(ElementInterface::class, $targetElement);
        }

        $act2Element = $model->getElement('act2');

        $this->assertCount(1, $act2Element->getSources());
        $this->assertCount(1, $act2Element->getSources());
        /** @var ElementInterface $sourceElement */
        foreach ($act2Element->getSources() as $sourceElement) {
            $this->assertEquals($sourceElement->getName(), 'act1');
            $this->assertInstanceOf(ElementInterface::class, $sourceElement);
        }
    }

    /**
     * @test
     */
    public function it_tests_model_builder_could_set_status_for_activities()
    {
        $configuration = [
            'activities' => [
                [
                    'name' => 'act1',
                    'sources' => [],
                    'targets' => ['act2'],
                    'status' => ElementInterface::STATUS_ACTIVE
                ],
                [
                    'name' => 'act2',
                    'sources' => ['act1'],
                    'targets' => ['act3'],
                    'status' => ElementInterface::STATUS_INACTIVE
                ]
            ]
        ];
        $model = PhpWorkflowCoreFacade::buildProcessModel($configuration);
        $this->assertEquals(ElementInterface::STATUS_ACTIVE, $model->getElement('act1')->getStatus());
    }
}

