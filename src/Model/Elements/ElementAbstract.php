<?php

namespace Escherchia\ProcessEngine\Model\Elements;

abstract class ElementAbstract
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $sources = array();

    /**
     * @var array
     */
    private $targets = array();

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
        //todo: call observers
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
}
