#!/bin/bash

set -e

# Prompt for theme name
echo -n "Enter theme name: "
read theme_name

if [ -z "$theme_name" ]; then
  echo "Theme name cannot be empty"
  exit 1
fi

npm run dist

# Clean and create dist directory
rm -rf dist && mkdir dist

# Define files and directories to copy
files_to_copy=(
  "*.php"
  "style.css"
  "screenshot.png"
)

dirs_to_copy=(
  "content_types"
  "metaboxes"
  "relationships"
  "css"
  "js"
  "img"
)

exclude_patterns=(
  ".DS_Store"
  ".git"
  ".gitignore"
  ".gitattributes"
  ".editorconfig"
  ".env"
  ".env.local"
)

# Copy files and directories
for file in "${files_to_copy[@]}"; do
  cp -r $file dist/
done

for dir in "${dirs_to_copy[@]}"; do
  cp -r "$dir" dist/
done

# Remove source directories
rm -rf dist/css/src dist/js/src

# Remove excluded files and directories
for pattern in "${exclude_patterns[@]}"; do
  find dist -name "$pattern" -type f -delete
  find dist -name "$pattern" -type d -exec rm -rf {} + 2>/dev/null || true
done

# Create distribution package
if [ -f "${theme_name}.zip" ]; then
  rm "${theme_name}.zip"
fi

# Build zip exclusion flags from exclude_patterns array
zip_excludes=""
for pattern in "${exclude_patterns[@]}"; do
  zip_excludes="$zip_excludes -x \"*$pattern\" -x \"*$pattern/*\""
done

(cd dist && eval "zip -r \"../${theme_name}.zip\" . $zip_excludes")

echo "Build complete! Theme package is in ${theme_name}.zip"
