<?php
namespace Lib;

use Lib\Encoder\EncoderInterface;

class Serializer {
    private array $encoders;

    public function __construct(array $encoders) {
        $this->encoders = $encoders;
    }

    private function getEncoder(string $format): ?EncoderInterface {
        foreach ($this->encoders as $encoder) {
            if ($encoder->supports($format)) {
                return $encoder;
            }
        }
        return null;
    }

    public function convert(string $input, string $inputFormat, string $outputFormat): string {
        $decoder = $this->getEncoder($inputFormat);
        $encoder = $this->getEncoder($outputFormat);

        if (!$decoder || !$encoder) {
            return 'Nieobsługiwany format.';
        }

        $data = $decoder->decode($input);
        return $encoder->encode($data);
    }
}
