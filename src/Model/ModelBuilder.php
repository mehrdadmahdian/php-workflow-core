<?php

namespace Escherchia\PhpWorkflowCore\Model;

use Escherchia\PhpWorkflowCore\Exceptions\ProcessModelConfigurationIsNotValid;
use Escherchia\PhpWorkflowCore\Model\Elements\Activity;
use Escherchia\PhpWorkflowCore\Model\Elements\ElementInterface;

class ModelBuilder
{
    /**
     * @var Model
     */
    protected $model;

    /**
     *
     */
    private function __construct()
    {
        $this->model = new Model(new ModelElementContainer());
    }

    /**
     * @param array $configuration
     * @return Model
     */
    public static function buildFromArray(array $configuration): Model
    {
        if (!ModelValidator::validateConfigurationArray($configuration)) {
            throw new ProcessModelConfigurationIsNotValid();
        }

        $modelBuilder = new static();

        $modelBuilder->registerActivities(
            isset($configuration['activities']) ? $configuration['activities'] : []
        );

        $modelBuilder->registerConnections(
            isset($configuration['activities']) ? $configuration['activities'] : []
        );

        $modelBuilder->registerObservers(
            isset($configuration['activities']) ? $configuration['activities'] : []
        );

        $modelBuilder->registerExtraActions(
            isset($configuration['activities']) ? $configuration['activities'] : []
        );

        return $modelBuilder->model;
    }

    /**
     * @param array $activities
     */
    private function registerActivities(array $activities = []): void
    {
        if (count($activities)) {
            foreach ($activities as $activity) {
                $this->model->addElement(
                    new Activity(
                        $activity['name'],
                        isset($activity['status']) ? $activity['status'] : ElementInterface::STATUS_INACTIVE
                    )
                );
            }
        }
    }

    /**
     * @param array $activities
     */
    private function registerConnections(array $activities = []): void
    {
        if (count($activities)) {
            foreach ($activities as $activity) {
                $activityElement = $this->model->getElement($activity['name']);
                if (isset($activity['sources']) and count($activity['sources'])) {
                    foreach ($activity['sources'] as $sourceKey) {
                        $sourceElement = $this->model->getElement($sourceKey);
                        if ($sourceElement) {
                            $activityElement->addSource($sourceElement);
                        }
                    }
                }
                if (isset($activity['targets']) and count($activity['targets'])) {
                    foreach ($activity['targets'] as $targetKey) {
                        $targetElement = $this->model->getElement($targetKey);
                        if ($targetElement) {
                            $activityElement->addTarget($targetElement);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param array $configuration
     */
    private function registerObservers(array $activities = []): void
    {
        if (count($activities)) {
            foreach ($activities as $activity) {
                if (isset($activity['observers']) and count($activity['observers'])) {
                    $activityElement = $this->model->getElement($activity['name']);
                    foreach ($activity['observers'] as $observerClass) {
                        $activityElement->attach(new $observerClass());
                    }

                }
            }
        }
    }

    /**
     * @param array $configuration
     */
    private function registerExtraActions(array $activities = []): void
    {
        if (count($activities)) {
            foreach ($activities as $activity) {
                if (isset($activity['extra-actions']) and count($activity['extra-actions'])) {
                    $activityElement = $this->model->getElement($activity['name']);
                    foreach ($activity['extra-actions'] as $extraActionClass) {
                        $activityElement->addExtraAction($extraActionClass);
                    }

                }
            }
        }
    }
}
