export function initReleaseImagesLazyLoad() {
    // Check if Intersection Observer is supported
    if (!('IntersectionObserver' in window)) {
        // Fallback for older browsers - load all images immediately
        document.querySelectorAll('img[data-src]').forEach(img => {
            img.src = img.dataset.src;
            img.classList.remove('lazy');
        });
        return;
    }

    // Create intersection observer
    const imageObserver = new IntersectionObserver(
        (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;

                    // Add loading class for visual feedback
                    img.classList.add('loading');

                    // Create new image to preload
                    const newImg = new Image();
                    newImg.onload = () => {
                        // Image loaded successfully
                        img.src = img.dataset.src;
                        img.classList.remove('lazy', 'loading');
                        img.classList.add('loaded');
                    };
                    newImg.onerror = () => {
                        // Image failed to load
                        img.classList.remove('lazy', 'loading');
                        img.classList.add('error');
                        console.warn('Failed to load image:', img.dataset.src);
                    };
                    newImg.src = img.dataset.src;

                    // Stop observing this image
                    observer.unobserve(img);
                }
            });
        },
        {
            // Load images when they're 50px away from entering viewport
            rootMargin: '50px 0px',
            threshold: 0.01,
        }
    );

    // Observe all lazy images
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}
