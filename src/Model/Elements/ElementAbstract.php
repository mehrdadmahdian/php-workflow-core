<?php

namespace Escherchia\ProcessEngineCore\Model\Elements;

use Escherchia\ProcessEngineCore\Contracts\ActionInterface;
use Escherchia\ProcessEngineCore\Contracts\ActivityObserverInterface;

abstract class ElementAbstract
{
    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $extraActions = array();

    /**
     * @var array
     */
    protected $sources = array();

    /**
     * @var array
     */
    protected $targets = array();

    /**
     * @var array
     */
    protected $observers = array();

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    protected function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @param string $status
     */
    public function updateStatus(string $status)
    {
        $this->status = $status;
        $this->notify();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $extraAction
     */
    public function addExtraAction(string $extraAction): void
    {
        if (in_array(ActionInterface::class, class_implements($extraAction))) {
            $this->extraActions[] = $extraAction;
        }
    }

    /**
     * @param ElementInterface $element
     */
    public function addSource(ElementInterface $element): void
    {
        $this->sources[$element->getName()] = $element;
    }

    /**
     * @param ElementInterface $element
     */
    public function addTarget(ElementInterface $element): void
    {
        $this->targets[$element->getName()] = $element;
    }

    /**
     * @param string $key
     */
    public function removeSource(string $key): void
    {
        if (isset($this->sources[$key])) {
            unset($this->sources[$key]);
        }
    }

    /**
     * @param string $key
     */
    public function removeTarget(string $key): void
    {
        if (isset($this->targets[$key])) {
            unset($this->targets[$key]);
        }
    }

    /**
     * @return array
     */
    public function getSources(): array
    {
        return $this->sources;
    }

    /**
     * @return array
     */
    public function getTargets(): array
    {
        return $this->targets;
    }

    /* Methods */
    public function attach(ActivityObserverInterface $observer): void
    {
        $this->observers[] = $observer;
    }

    /**
     *
     */
    public function notify(): void
    {
        /** @var ActivityObserverInterface $observer */
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    /**
     * @return array
     */
    public function getActions(): array
    {
        $actions  = [];
        $extraActions = $this->extraActions;

        /** @var ElementInterface $targetElement */
        foreach ($this->getTargets() as $targetElement) {
            $actions[] = [
                'key' => 'transition',
                'params' => [
                    'currentActivityKey' => $this->getName(),
                    'targetActivityKey'  => $targetElement->getName(),
                ]
            ];
        }

        /** @var ActionInterface $extraAction */
        foreach ($extraActions as $extraAction) {
            $actions[] = [
                'key' => $extraAction,
                'params' => [
                    'currentActivityKey' => $this->getName(),
                ]
            ];
        }
        return $actions;
    }
}
