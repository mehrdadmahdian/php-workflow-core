<?php

namespace Escherchia\ProcessEngineCore\Model;

use Escherchia\ProcessEngineCore\Model\Elements\Activity;
use Escherchia\ProcessEngineCore\Model\Elements\ElementInterface;

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
        $modelBuilder = new static();

        $modelBuilder->registerActivities(
            isset($configuration['activities']) ? $configuration['activities'] : []
        );

        $modelBuilder->registerConnections(
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
                        isset($activity['status']) ? $activity['status'] : ElementInterface::STATUS_NOT_STARTED
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
}
