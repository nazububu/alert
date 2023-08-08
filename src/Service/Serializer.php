<?php

namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;

class Serializer
{
    private const TYPE = 'json';

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize(array $data): string
    {
        return $this->serializer->serialize($data, self::TYPE, [
            'json_encode_options' => JSON_UNESCAPED_UNICODE,
        ]);
    }
}