<?php

namespace Sitation\CustomRuleBundle\Controller;

use Sitation\CustomRuleBundle\Model\ProductPatternAction;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class PatternActionDenormalizer extends GetSetMethodNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return parent::denormalize($data, 'Sitation\CustomRuleBundle\Model\ProductPatternAction');
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return isset($data['type']) && ProductPatternAction::ACTION_TYPE === $data['type'];
    }
}