<?php // I:\ptw\lab-f\yaml.php

$data = [
    'name' => 'Dmytro Ziailyk',
    'index' => '55644',
    'date' => date(DATE_ATOM),
];

$yaml = yaml_emit($data);

echo $yaml;
