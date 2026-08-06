// public/js/leaflet-report.js

document.addEventListener('DOMContentLoaded', function() {
    // Default Map Center (Kabupaten Demak)
    const defaultLat = -6.8936;
    const defaultLng = 110.6382;
    const defaultZoom = 13;

    // Get input elements
    const inputLat = document.getElementById('inputLatitude');
    const inputLng = document.getElementById('inputLongitude');
    const inputAddress = document.getElementById('inputAddress');
    const btnGetLocation = document.getElementById('btnGetCurrentLocation');

    // Init map
    const map = L.map('map').setView([defaultLat, defaultLng], defaultZoom);

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Init Marker (Draggable)
    let initialLat = inputLat.value ? parseFloat(inputLat.value) : defaultLat;
    let initialLng = inputLng.value ? parseFloat(inputLng.value) : defaultLng;
    
    const marker = L.marker([initialLat, initialLng], {
        draggable: true
    }).addTo(map);

    // Update inputs when marker is dragged
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        updateInputs(position.lat, position.lng);
        reverseGeocode(position.lat, position.lng);
        debouncedCheckDuplicate(position.lat, position.lng);
    });

    // Update marker when map is clicked
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateInputs(e.latlng.lat, e.latlng.lng);
        reverseGeocode(e.latlng.lat, e.latlng.lng);
        debouncedCheckDuplicate(e.latlng.lat, e.latlng.lng);
    });

    // Get Current Location via HTML5 Geolocation API
    btnGetLocation.addEventListener('click', function() {
        const originalText = btnGetLocation.innerHTML;
        btnGetLocation.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mendeteksi...';
        btnGetLocation.disabled = true;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                // Success
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    map.setView([lat, lng], 16);
                    marker.setLatLng([lat, lng]);
                    updateInputs(lat, lng);
                    reverseGeocode(lat, lng);
                    debouncedCheckDuplicate(lat, lng);
                    
                    btnGetLocation.innerHTML = '<i class="bi bi-check-circle-fill me-2 text-success"></i>Lokasi Ditemukan';
                    setTimeout(() => {
                        btnGetLocation.innerHTML = originalText;
                        btnGetLocation.disabled = false;
                    }, 3000);
                },
                // Error
                function(error) {
                    alert("Gagal mendeteksi lokasi. Pastikan fitur GPS aktif dan browser Anda mengizinkan akses lokasi.");
                    btnGetLocation.innerHTML = originalText;
                    btnGetLocation.disabled = false;
                },
                // Options
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            alert("Browser Anda tidak mendukung Geolocation.");
            btnGetLocation.innerHTML = originalText;
            btnGetLocation.disabled = false;
        }
    });

    // Helper: Update Hidden Inputs
    function updateInputs(lat, lng) {
        inputLat.value = lat.toFixed(8);
        inputLng.value = lng.toFixed(8);
    }

    // Helper: Reverse Geocoding using Nominatim (OpenStreetMap)
    function reverseGeocode(lat, lng) {
        // Prevent spamming API, only if address is empty or user confirms
        const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if(data && data.display_name) {
                    // Cuma auto-fill jika address masih kosong, biarkan user edit detailnya
                    if(inputAddress.value.trim() === '') {
                        inputAddress.value = data.display_name;
                    }
                }
            })
            .catch(err => console.error("Geocoding error:", err));
    }

    // Duplicate Check Helpers
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), wait);
        };
    }

    function checkDuplicateReport(lat, lng) {
        const categoryInputs = document.querySelectorAll('input[name="category_id"]');
        let categoryId = null;
        categoryInputs.forEach(input => {
            if (input.checked) categoryId = input.value;
        });

        if (!categoryId) return;
        
        const csrfToken = document.querySelector('input[name="_token"]');
        if (!csrfToken) return;

        fetch('/api/check-duplicate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.value
            },
            body: JSON.stringify({ lat: lat, lng: lng, category_id: categoryId })
        })
        .then(response => response.json())
        .then(data => {
            const alertBox = document.getElementById('duplicateAlert');
            if (alertBox) {
                if (data.is_duplicate) {
                    alertBox.classList.remove('d-none');
                } else {
                    alertBox.classList.add('d-none');
                }
            }
        })
        .catch(err => console.error("Error checking duplicate:", err));
    }

    const debouncedCheckDuplicate = debounce(checkDuplicateReport, 500);
    
    // Also trigger duplicate check when category is changed
    document.querySelectorAll('input[name="category_id"]').forEach(input => {
        input.addEventListener('change', function() {
            debouncedCheckDuplicate(parseFloat(inputLat.value), parseFloat(inputLng.value));
        });
    });
});
