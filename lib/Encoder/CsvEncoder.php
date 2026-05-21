<?php
namespace Lib\Encoder;

class CsvEncoder implements EncoderInterface {

    public function supports(string $format): bool {
        return in_array($format, ['csv', 'ssv', 'tsv']);
    }

    private function getDelimiter(string $format): string {
        return match ($format) {
            'csv' => ',',
            'ssv' => ';',
            'tsv' => "\t",
        };
    }

    public function decode(string $input): array {
        $lines = array_filter(array_map('trim', explode("\n", $input)));
        if (empty($lines)) return [];

        $delimiter = $this->getDelimiter($_POST['inputFormat'] ?? 'csv');
        $headers = str_getcsv(array_shift($lines), $delimiter, '"', '\\');

        $result = [];
        foreach ($lines as $line) {
            $row = str_getcsv($line, $delimiter, '"', '\\');
            $result[] = array_combine($headers, $row);
        }
        return $result;
    }

    public function encode(array $data): string {
        if (empty($data)) return '';

        $delimiter = $this->getDelimiter($_POST['outputFormat'] ?? 'csv');
        $headers = array_keys($data[0]);
        $output = implode($delimiter, $headers) . "\n";

        foreach ($data as $row) {
            $output .= implode($delimiter, $row) . "\n";
        }

        return trim($output);
    }
}
