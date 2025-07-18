<?php

require 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use ReflectionClass;

$reflection = new ReflectionClass(QrCode::class);
$constructor = $reflection->getConstructor();

echo "Constructor parameters:\n";
foreach ($constructor->getParameters() as $param) {
    echo "- " . $param->getName() . " (type: " . ($param->getType() ? $param->getType() : 'mixed') . ")\n";
}

echo "\nTrying default constructor:\n";
$qr = new QrCode('test');
echo "Size: " . $qr->getSize() . "\n";
echo "Margin: " . $qr->getMargin() . "\n";
