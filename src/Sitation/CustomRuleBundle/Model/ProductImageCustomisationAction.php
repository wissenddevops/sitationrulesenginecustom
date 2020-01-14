<?php

namespace Sitation\CustomRuleBundle\Model;

use Akeneo\Tool\Bundle\RuleEngineBundle\Model\ActionInterface;
use Akeneo\Pim\Automation\RuleEngine\Component\Model\FieldImpactActionInterface;

class ProductImageCustomisationAction implements ActionInterface, FieldImpactActionInterface
{
    const ACTION_TYPE = 'imagecustomisation';

    /** @var string */
    protected $field;

    /** @var array */
    protected $attributes= [];

    /** @var string */
    protected $imagecustomisation;

    /** @var array */
    protected $options = [];
     /** @var string */
    protected $width;
     /** @var string */
    protected $height;

    /**
     * {@inheritdoc}
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * {@inheritdoc}
     */
    public function setField(string $field)
    {
        $this->field = $field;
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * {@inheritdoc}
     */
    public function setWidth(int $width)
    {
        $this->width = $width;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * {@inheritdoc}
     */
    public function setHeight(int $height)
    {
        $this->height = $height;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getImageCustomisation()
    {
        return $this->imagecustomisation;
    }

    /**
     * @param string $imageCustomisation
     */
    public function setImageCustomisation(string $imagecustomisation)
    {
        $this->imagecustomisation = $imagecustomisation;
    }

    /**
     * {@inheritdoc}
     */
    public function getImpactedFields()
    {
        return [$this->getField()];
    }
}