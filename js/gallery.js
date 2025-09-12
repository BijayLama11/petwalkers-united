var lightbox = document.querySelector('.lightbox');
var lightboxImage = document.getElementById('lb-img');
var lightboxTitle = document.getElementById('lb-title');
var thumbnailImages = document.querySelectorAll('.thumb');

if (lightbox) {
    
    function openLightbox(fullImageUrl, imageCaption) {
        lightboxImage.src = fullImageUrl;
        
        if (imageCaption) {
            lightboxTitle.textContent = imageCaption;
        } else {
            lightboxTitle.textContent = '';
        }
        
        lightbox.hidden = false;
        
        document.body.style.overflow = 'hidden';
    }
    
    function closeLightbox() {
        lightbox.hidden = true;
        
        lightboxImage.src = '';
        lightboxTitle.textContent = '';
        
        document.body.style.overflow = '';
    }
    
    for (var i = 0; i < thumbnailImages.length; i++) {
        var thumbnail = thumbnailImages[i];
        
        thumbnail.addEventListener('click', function() {
            var fullImageUrl = this.dataset.full;
            var caption = this.dataset.caption;
            openLightbox(fullImageUrl, caption);
        });
        
        thumbnail.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                var fullImageUrl = this.dataset.full;
                var caption = this.dataset.caption;
                openLightbox(fullImageUrl, caption);
            }
        });
    }
    
    lightbox.addEventListener('click', function(event) {
        var clickedElement = event.target;
        if (clickedElement.hasAttribute('data-close')) {
            closeLightbox();
        }
    });
    
    document.addEventListener('keydown', function(event) {
        var isLightboxOpen = !lightbox.hidden;
        if (isLightboxOpen && event.key === 'Escape') {
            closeLightbox();
        }
    });
}