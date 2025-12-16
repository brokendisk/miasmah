# Morr Music Image Caching System

## Overview
This system caches images from `https://resources.morrmusic.com` locally to improve performance and reduce external API calls.

## How It Works

### 1. Image Caching Function
- `get_cached_morr_image($morr_uuid, $size)` - Downloads and caches individual images
- Images are stored in `/wp-content/uploads/morr-cache/`
- Cache expires after 24 hours
- Falls back to original URL if caching fails

### 2. Batch Caching (Optimized)
- `batch_cache_morr_images($releases, $size)` - Pre-caches multiple images at once
- Used on the releases page to cache all cover images before display
- **Performance optimized**: Uses `glob()` to check all cached files in a single filesystem operation
- Only fetches images that aren't cached or have expired (24 hours)
- Dramatically faster than individual `file_exists()` checks for each release

### 3. Cache Management
- Admin interface at **Tools > Morr Music Cache**
- View cache status (file count, total size)
- Clear cache manually when needed

## Image Sizes Available
- `full.jpeg` - Original size
- `50_square.jpeg` through `1400_square.jpeg` - Various square sizes
- `400_square.jpeg` - Used for release listings (smaller, faster)
- `1000_square.jpeg` - Used for single release pages (higher quality)

## Performance Benefits
- **Reduced external requests**: Images served from local cache
- **Faster page loads**: No waiting for external CDN responses
- **Bandwidth savings**: Images downloaded once and reused
- **Better reliability**: Fallback to original URL if cache fails
- **Optimized batch checking**: Uses `glob()` pattern matching to check all cached files in a single operation instead of individual filesystem calls
- **Minimal I/O overhead**: Only fetches images that aren't cached or have expired, reducing blocking operations during page load

## Implementation Details

### Releases Page (`releases.php`)
```php
// Batch cache all images before display (optimized with glob() pattern matching)
$cached_images = batch_cache_morr_images($releases, '400_square');

// Use cached URL in template
$morr_cover = isset($cached_images[$release->morr_uuid]) 
    ? $cached_images[$release->morr_uuid] 
    : get_cached_morr_image($release->morr_uuid, '400_square');
```

**How the optimization works:**
1. Uses `glob()` to find all cached files matching the pattern `*_400_square.jpeg` in a single filesystem operation
2. Builds a lookup array of valid cached files (checking expiry times)
3. Only calls `get_cached_morr_image()` for images that aren't in the cache or have expired
4. Reduces filesystem I/O from O(n) individual checks to O(1) glob + array lookups

### Single Release Page (`single-release.php`)
```php
// Cache individual image
$morr_cover = get_cached_morr_image($the_morr_id, '1000_square');
```

## Cache Directory Structure
```
/wp-content/uploads/morr-cache/
├── {uuid}_400_square.jpeg
├── {uuid}_1000_square.jpeg
├── {uuid}_100_square.jpeg
└── ...
```

**File naming pattern:** `{morr_uuid}_{size}.jpeg`

## Maintenance
- Cache automatically expires after 24 hours
- Use admin interface to clear cache manually
- Cache directory is created automatically
- No manual cleanup required (WordPress handles uploads directory)

