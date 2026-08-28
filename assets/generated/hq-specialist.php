<?php
declare(strict_types=1);

header('Content-Type: image/webp');
header('Cache-Control: public, max-age=31536000, immutable');

$parts = glob(__DIR__ . '/hq/specialist/*.b64') ?: [];
sort($parts, SORT_STRING);
$encoded = '';
foreach ($parts as $part) {
    $encoded .= trim((string)file_get_contents($part));
}
$binary = base64_decode($encoded, true);
if ($binary === false || strlen($binary) < 10000 || substr($binary, 0, 4) !== 'RIFF' || substr($binary, 8, 4) !== 'WEBP') {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Generated specialist image is unavailable';
    exit;
}
header('Content-Length: ' . strlen($binary));
echo $binary;
