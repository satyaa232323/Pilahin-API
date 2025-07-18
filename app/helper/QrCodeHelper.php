<?php

namespace App\Helper;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Color\Color;
use Illuminate\Support\Facades\Storage;

class QrCodeHelper
{
    /**
     * Generate QR Code as PNG
     */
    public static function generatePng(string $data, int $size = 300, int $margin = 10): string
    {
        $qrCode = new QrCode(
            $data,
            new Encoding('UTF-8'),
            ErrorCorrectionLevel::High,
            $size,
            $margin,
            RoundBlockSizeMode::Margin,
            new Color(0, 0, 0),
            new Color(255, 255, 255)
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return $result->getString();
    }

    /**
     * Generate QR Code as SVG
     */
    public static function generateSvg(string $data, int $size = 300, int $margin = 10): string
    {
        $qrCode = new QrCode(
            $data,
            new Encoding('UTF-8'),
            ErrorCorrectionLevel::High,
            $size,
            $margin,
            RoundBlockSizeMode::Margin,
            new Color(0, 0, 0),
            new Color(255, 255, 255)
        );

        $writer = new SvgWriter();
        $result = $writer->write($qrCode);

        return $result->getString();
    }

    /**
     * Generate and save QR Code
     */
    public static function generateAndSave(string $data, string $filename, string $format = 'png'): string
    {
        if ($format === 'svg') {
            $qrContent = self::generateSvg($data);
            $path = "qr_codes/{$filename}.svg";
        } else {
            $qrContent = self::generatePng($data);
            $path = "qr_codes/{$filename}.png";
        }

        Storage::disk('public')->put($path, $qrContent);

        return Storage::url($path);
    }
}
