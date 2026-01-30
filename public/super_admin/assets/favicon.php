<?php
/**
 * Serves a circular SVG favicon with the logo embedded (base64).
 * Browsers often don't load external images in SVG favicons, so we embed the image.
 */
$logoPath = __DIR__ . '/images/goldenz-logo.jpg';
$mime = 'image/jpeg';

if (!is_file($logoPath)) {
    // Fallback: try parent images folder
    $logoPath = __DIR__ . '/goldenz-logo.jpg';
}
if (!is_file($logoPath)) {
    $logoPath = __DIR__ . '/images/goldenz-logo.png';
    $mime = 'image/png';
}

header('Content-Type: image/svg+xml');
header('Cache-Control: public, max-age=86400');

if (!is_file($logoPath) || !is_readable($logoPath)) {
    // No logo: output a simple circular placeholder (gold circle)
    echo '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
  <circle cx="32" cy="32" r="32" fill="#d4af37"/>
  <circle cx="32" cy="32" r="28" fill="#1a1f2e"/>
  <text x="32" y="38" font-family="Arial,sans-serif" font-size="14" font-weight="bold" fill="#d4af37" text-anchor="middle">GZ</text>
</svg>';
    exit;
}

$data = file_get_contents($logoPath);
$b64 = base64_encode($data);
$dataUri = 'data:' . $mime . ';base64,' . $b64;

?>
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64">
  <defs>
    <clipPath id="c">
      <circle cx="32" cy="32" r="32"/>
    </clipPath>
  </defs>
  <image href="<?php echo htmlspecialchars($dataUri); ?>" x="0" y="0" width="64" height="64" clip-path="url(#c)" preserveAspectRatio="xMidYMid slice"/>
</svg>
