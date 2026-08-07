<?php

namespace App\Helpers;

class QrCodeGenerator
{
    public static function generateSvg($data)
    {
        $hash = md5($data);
        $size = 25; // 25x25 grid
        $cellSize = 8;
        $totalSize = $size * $cellSize;

        $svg = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"{$totalSize}\" height=\"{$totalSize}\" viewBox=\"0 0 {$totalSize} {$totalSize}\">";
        $svg .= "<rect width=\"100%\" height=\"100%\" fill=\"#ffffff\"/>";

        // Draw standard QR Code corner finder blocks
        $svg .= self::drawFinder(0, 0, $cellSize);
        $svg .= self::drawFinder(($size - 7) * $cellSize, 0, $cellSize);
        $svg .= self::drawFinder(0, ($size - 7) * $cellSize, $cellSize);

        // Draw randomized cells based on payload hash
        for ($r = 0; $r < $size; $r++) {
            for ($c = 0; $c < $size; $c++) {
                // Skip finder pattern zones
                if (($r < 8 && $c < 8) || ($r < 8 && $c >= $size - 8) || ($r >= $size - 8 && $c < 8)) {
                    continue;
                }

                $charIndex = ($r * $size + $c) % 32;
                $char = $hash[$charIndex];
                $fill = (hexdec($char) % 2 === 0);

                if ($fill) {
                    $x = $c * $cellSize;
                    $y = $r * $cellSize;
                    $svg .= "<rect x=\"{$x}\" y=\"{$y}\" width=\"{$cellSize}\" height=\"{$cellSize}\" fill=\"#000000\"/>";
                }
            }
        }

        $svg .= "</svg>";
        return $svg;
    }

    private static function drawFinder($startX, $startY, $cellSize)
    {
        $svg = '';
        // Outer 7x7 black square
        $w = 7 * $cellSize;
        $svg .= "<rect x=\"{$startX}\" y=\"{$startY}\" width=\"{$w}\" height=\"{$w}\" fill=\"#000000\"/>";
        // Middle 5x5 white square
        $w2 = 5 * $cellSize;
        $x2 = $startX + $cellSize;
        $y2 = $startY + $cellSize;
        $svg .= "<rect x=\"{$x2}\" y=\"{$y2}\" width=\"{$w2}\" height=\"{$w2}\" fill=\"#ffffff\"/>";
        // Inner 3x3 black square
        $w3 = 3 * $cellSize;
        $x3 = $startX + 2 * $cellSize;
        $y3 = $startY + 2 * $cellSize;
        $svg .= "<rect x=\"{$x3}\" y=\"{$y3}\" width=\"{$w3}\" height=\"{$w3}\" fill=\"#000000\"/>";
        return $svg;
    }
}
