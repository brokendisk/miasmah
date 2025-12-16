# Redirect Manifest Instructions

## Where to Add New URLs

Add the new WordPress URLs in the **`new_url` column (column 2)** of the `redirect-manifest.csv` file.

## URL Format

Based on your WordPress setup, release URLs will likely follow this format:

- **Releases**: `/release/{slug}` where `{slug}` is generated from `{artist}-{title}`
  - Example: `/release/erik-k-skodvin-from-darkness-original-motion-picture-soundtrack`
  - Example: `/release/olga-anna-markowska-iskra`

- **Main Pages**: These should redirect to your WordPress pages:
  - `/recordings/index.html` → `/` (homepage) or `/releases`
  - `/recordings/cat.html` → `/releases` (releases catalog page)
  - `/recordings/info.html` → `/info` (info page)
  - `/recordings/artists.html` → `/artists` (artists page)

## How to Find the Actual WordPress URLs

1. **In WordPress Admin:**
   - Go to Posts → Releases
   - Click on a release to edit it
   - Look at the permalink shown below the title
   - Copy the URL path (e.g., `/release/erik-k-skodvin-from-darkness`)

2. **Using get_permalink() function:**
   - The slug is generated from `display_artist` and `title` fields
   - Format: `sanitize_title(display_artist) + '-' + sanitize_title(title)`
   - Example: "Erik K Skodvin" + "From Darkness" → "erik-k-skodvin-from-darkness"

3. **Check WordPress Permalink Settings:**
   - Go to Settings → Permalinks
   - Check if custom post types use a prefix (e.g., `/release/` or just `/`)

## CSV Structure

The CSV file has these columns:
- `old_url` - The old URL from miasmah.com (already filled in)
- **`new_url`** - **ADD YOUR NEW WORDPRESS URLS HERE** (currently empty)
- `catalog` - Catalog number (for reference)
- `artist` - Artist name (for reference)
- `title` - Release title (for reference)
- `year` - Release year (for reference)
- `notes` - Additional notes (for reference)

## Example

```csv
old_url,new_url,catalog,artist,title,year,notes
/recordings/miacd062.html,/release/erik-k-skodvin-from-darkness-original-motion-picture-soundtrack,MIA 062,Erik K Skodvin,From Darkness (Original Motion Picture Soundtrack),2025,
```

## Next Steps

1. Fill in the `new_url` column with the actual WordPress URLs for each release
2. Import the CSV into your redirect plugin (e.g., Redirection plugin)
3. Or use the JSON file to programmatically create redirects
4. Test the redirects before going live

