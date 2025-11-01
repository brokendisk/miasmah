# Morr Music Image Caching System

## Overview
This system caches images from `https://resources.morrmusic.com` locally to improve performance and reduce external API calls.

## How It Works

### 1. Image Caching Function
- `get_cached_morr_image($morr_uuid, $size)` - Downloads and caches individual images
- Images are stored in `/wp-content/uploads/morr-cache/`
- Cache expires after 24 hours
- Falls back to original URL if caching fails

### 2. Batch Caching
- `batch_cache_morr_images($releases, $size)` - Pre-caches multiple images at once
- Used on the releases page to cache all cover images before display
- More efficient than individual requests

### 3. Cache Management
- Admin interface at **Tools > Morr Music Cache**
- View cache status (file count, total size)
- Clear cache manually when needed

## Image Sizes Available
- `full.jpeg` - Original size
- `50_square.jpeg` through `1400_square.jpeg` - Various square sizes
- `200_square.jpeg` - Used for release listings (smaller, faster)
- `1000_square.jpeg` - Used for single release pages (higher quality)

## Performance Benefits
- **Reduced external requests**: Images served from local cache
- **Faster page loads**: No waiting for external CDN responses
- **Bandwidth savings**: Images downloaded once and reused
- **Better reliability**: Fallback to original URL if cache fails

## Implementation Details

### Releases Page (`releases.php`)
```php
// Batch cache all images before display
$cached_images = batch_cache_morr_images($releases, '200_square');

// Use cached URL in template
$morr_cover = isset($cached_images[$release->morr_uuid]) 
    ? $cached_images[$release->morr_uuid] 
    : get_cached_morr_image($release->morr_uuid, '200_square');
```

### Single Release Page (`single-release.php`)
```php
// Cache individual image
$morr_cover = get_cached_morr_image($the_morr_id, '1000_square');
```

## Cache Directory Structure
```
/wp-content/uploads/morr-cache/
├── {uuid}_200_square.jpeg
├── {uuid}_400_square.jpeg
├── {uuid}_100_square.jpeg
└── ...
```

## Maintenance
- Cache automatically expires after 24 hours
- Use admin interface to clear cache manually
- Cache directory is created automatically
- No manual cleanup required (WordPress handles uploads directory)

