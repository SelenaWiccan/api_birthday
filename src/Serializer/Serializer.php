<?php

namespace App\Serializer;

use DateTime;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class Serializer implements NormalizerInterface
{
    public function normalize($object, $format = null, array $context = [])
    {
        return $object instanceof DateTime ? $object->format('Y-m-d') : '';
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof DateTime;
    }
}