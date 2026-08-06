// public/js/leaflet-admin-map.js

document.addEventListener('DOMContentLoaded', function() {
    // Pusat Kabupaten Demak
    const centerLat = -6.8936;
    const centerLng = 110.6382;
    
    // Inisialisasi Peta
    const map = L.map('adminMap').setView([centerLat, centerLng], 12);

    // Layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Inisialisasi Marker Cluster Group
    const markerCluster = L.markerClusterGroup({
        chunkedLoading: true,
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true
    });

    map.addLayer(markerCluster);

    // Fungsi fetch data dari API
    function loadMapData(status = '', categoryId = '') {
        // Tampilkan loading state jika perlu
        markerCluster.clearLayers();

        let url = mapDataUrl; // Variabel dari window global (blade)
        let params = new URLSearchParams();
        if (status) params.append('status', status);
        if (categoryId) params.append('category_id', categoryId);
        
        if (params.toString()) {
            url += '?' + params.toString();
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {
                const markers = [];
                
                data.forEach(report => {
                    // Tentukan warna pin berdasarkan status
                    let pinClass = 'pin-pending';
                    if (report.status === 'diproses') pinClass = 'pin-diproses';
                    if (report.status === 'selesai') pinClass = 'pin-selesai';
                    if (report.status === 'ditolak') pinClass = 'pin-ditolak';

                    // Buat custom icon HTML
                    const iconHtml = `
                        <div class="marker-pin ${pinClass}"></div>
                        <i class="${report.category_icon}"></i>
                    `;

                    const customIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: iconHtml,
                        iconSize: [30, 42],
                        iconAnchor: [15, 42],
                        popupAnchor: [0, -35]
                    });

                    // Buat popup content
                    const popupContent = `
                        <div style="min-width: 200px;">
                            <div class="mb-2">
                                <span class="badge" style="background: ${report.category_color}"><i class="${report.category_icon}"></i> ${report.category_name}</span>
                                <span class="badge bg-secondary ms-1">${report.status_label}</span>
                            </div>
                            <h6 class="fw-bold mb-1">${report.ticket_code}</h6>
                            <p class="small text-muted mb-2"><i class="bi bi-geo-alt"></i> ${report.address}</p>
                            <a href="${report.url}" class="btn btn-sm btn-outline-primary w-100">Buka Detail</a>
                        </div>
                    `;

                    // Tambahkan marker ke array
                    const marker = L.marker([report.lat, report.lng], { icon: customIcon })
                                    .bindPopup(popupContent);
                    
                    markers.push(marker);
                });

                // Tambahkan semua marker ke cluster sekaligus
                markerCluster.addLayers(markers);

                // Auto zoom ke wilayah yang ada markernya (jika ada)
                if (markers.length > 0) {
                    map.fitBounds(markerCluster.getBounds(), { padding: [50, 50] });
                } else {
                    // Reset ke tengah jika kosong
                    map.setView([centerLat, centerLng], 12);
                }
            })
            .catch(err => console.error('Error fetching map data:', err));
    }

    // Load data pertama kali
    loadMapData();

    // Event Listener Filter Button
    document.getElementById('btnApplyFilter').addEventListener('click', function() {
        const status = document.getElementById('filterStatus').value;
        const categoryId = document.getElementById('filterCategory').value;
        loadMapData(status, categoryId);
    });
});
