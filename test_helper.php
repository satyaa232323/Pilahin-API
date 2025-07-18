<?php

require 'vendor/autoload.php';

use App\Helper\QrCodeHelper;

// Test PNG generation
try {
    $pngContent = QrCodeHelper::generatePng('TEST123', 300, 10);
    echo "PNG generation successful! Content length: " . strlen($pngContent) . " bytes\n";

    $svgContent = QrCodeHelper::generateSvg('TEST123', 300, 10);
    echo "SVG generation successful! Content length: " . strlen($svgContent) . " bytes\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
