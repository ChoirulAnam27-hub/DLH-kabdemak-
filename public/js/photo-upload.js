// public/js/photo-upload.js

document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('photoInput');
    const previewContainer = document.getElementById('photoPreviewContainer');
    const maxFiles = 3;
    const maxSize = 5 * 1024 * 1024; // 5MB

    if (!photoInput) return;

    photoInput.addEventListener('change', function(e) {
        previewContainer.innerHTML = ''; // Clear prev preview
        
        const files = Array.from(this.files);
        
        // Validate Max Files
        if (files.length > maxFiles) {
            alert(`Maksimal hanya ${maxFiles} foto yang diperbolehkan.`);
            this.value = ''; // Reset input
            return;
        }
        
        // Process each file
        files.forEach((file, index) => {
            // Validate size
            if (file.size > maxSize) {
                alert(`Ukuran foto ${file.name} melebihi batas 5MB.`);
                this.value = '';
                previewContainer.innerHTML = '';
                return;
            }

            // Create preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgWrap = document.createElement('div');
                imgWrap.style.position = 'relative';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-img shadow-sm';
                
                imgWrap.appendChild(img);
                previewContainer.appendChild(imgWrap);
            }
            reader.readAsDataURL(file);
        });
    });
});
