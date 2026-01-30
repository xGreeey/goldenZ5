<?php
/**
 * Global Favicon: Serves a circular SVG favicon with the logo embedded (base64).
 * Browsers often don't load external images in SVG favicons, so we embed the image.
 * This favicon is used globally across all pages.
 * Uses mask for better browser compatibility to ensure circular shape.
 */
$logoPath = __DIR__ . '/images/goldenz-logo.jpg';
$mime = 'image/jpeg';

if (!is_file($logoPath)) {
    // Fallback: try PNG if JPG doesn't exist
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
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" viewBox="0 0 64 64">
  <defs>
    <!-- ClipPath to create perfect circle -->
    <clipPath id="circleClip">
      <circle cx="32" cy="32" r="32"/>
    </clipPath>
  </defs>
  <!-- Image clipped to perfect circle - no background, transparent edges -->
  <g clip-path="url(#circleClip)">
    <image href="<?php echo htmlspecialchars($dataUri); ?>" 
           x="0" y="0" 
           width="64" height="64" 
           preserveAspectRatio="xMidYMid slice"/>
  </g>
</svg>
