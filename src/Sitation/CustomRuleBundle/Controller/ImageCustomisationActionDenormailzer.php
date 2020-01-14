<?php

namespace Sitation\CustomRuleBundle\Controller;

use Sitation\CustomRuleBundle\Model\ProductImageCustomisationAction;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class ImageCustomisationActionDenormailzer extends GetSetMethodNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return parent::denormalize($data, 'Sitation\CustomRuleBundle\Model\ProductImageCustomisationAction');
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return isset($data['type']) && ProductImageCustomisationAction::ACTION_TYPE === $data['type'];
    }
}