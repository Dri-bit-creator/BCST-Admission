<?php
// Simple favicon generator: embeds the PNG into an ICO container (works for modern browsers and older IE)
$pngPath = __DIR__ . '/../img/logo.png';
$outPath = __DIR__ . '/../favicon.ico';

if (!file_exists($pngPath)) {
    fwrite(STDERR, "Logo PNG not found at: $pngPath\n");
    exit(1);
}

$png = file_get_contents($pngPath);
if ($png === false) {
    fwrite(STDERR, "Failed to read PNG\n");
    exit(1);
}

$pngLen = strlen($png);

// ICO header: reserved (2 bytes), type (2 bytes = 1 for icon), count (2 bytes)
$header = pack('vvv', 0, 1, 1);

// Directory entry: width, height, colorCount, reserved, planes (2), bitCount (2), bytesInRes (4), imageOffset (4)
$width = 0; // 0 means 256
$height = 0;
$colorCount = 0;
$reserved = 0;
$planes = 1;
$bitCount = 32;
$bytesInRes = $pngLen;
$imageOffset = 6 + 16; // header (6) + one directory entry (16)

$entry = pack('C', $width) . pack('C', $height) . pack('C', $colorCount) . pack('C', $reserved)
    . pack('v', $planes) . pack('v', $bitCount) . pack('V', $bytesInRes) . pack('V', $imageOffset);

$ico = $header . $entry . $png;

if (file_put_contents($outPath, $ico) === false) {
    fwrite(STDERR, "Failed to write favicon.ico to $outPath\n");
    exit(1);
}

echo "Created favicon.ico at: $outPath\n";
exit(0);
