<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DSS Pangan - Prediksi Wilayah</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 100%; width: 100%; z-index: 1; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-gray-100 h-screen flex flex-col md:flex-row overflow-hidden font-sans">

    <!-- Sidebar Input (Ukuran Lebih Ramping & Bisa Dibuka/Tutup) -->
    <aside id="sidebar" class="w-72 bg-white border-r border-slate-200 shadow-lg flex flex-col z-20 relative transition-all duration-300 ease-in-out flex-shrink-0 overflow-hidden">
        <!-- Header Sidebar -->
        <div class="px-4 py-3.5 bg-gradient-to-r from-emerald-800 to-emerald-600 text-white flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-sm font-bold tracking-tight leading-tight">Spatial DSS</h1>
                    <p class="text-[11px] text-emerald-100 leading-tight">Analisis Wilayah Semarang</p>
                </div>
            </div>
            <button id="btnCloseSidebar" type="button" title="Tutup Sidebar" class="p-1.5 rounded-lg text-emerald-100 hover:text-white hover:bg-white/15 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <!-- Konten Form Sidebar -->
        <div class="p-4 flex-1 overflow-y-auto space-y-4">
            <!-- Navigasi Mode (Segmented Pill) -->
            <div class="grid grid-cols-2 gap-1 p-1 bg-slate-100 rounded-lg border border-slate-200/80">
                <a href="/" class="text-center py-1.5 px-2 text-xs font-medium text-slate-600 hover:text-slate-900 rounded-md transition-all">
                    📍 Spatial Titik
                </a>
                <a href="/dss-area" class="text-center py-1.5 px-2 text-xs font-semibold bg-white text-emerald-700 shadow-sm rounded-md transition-all">
                    🗺️ Wilayah
                </a>
            </div>

            <form id="dssForm" class="space-y-4">
                <!-- Pilihan Wilayah -->
                <div class="bg-slate-50/80 border border-slate-200/80 rounded-xl p-3">
                    <label for="area_select" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                        1. Pilih Wilayah Analisis
                    </label>
                    <select id="area_select" class="w-full px-2.5 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                        <option value="" disabled selected>-- Pilih Kecamatan --</option>
                        <optgroup label="Zona Pesisir">
                            <option value="genuk">Kec. Genuk (Pesisir)</option>
                            <option value="tugu">Kec. Tugu (Pesisir)</option>
                            <option value="semarang utara">Kec. Semarang Utara (Pesisir)</option>
                            <option value="semarang barat">Kec. Semarang Barat (Pesisir)</option>
                        </optgroup>
                        <optgroup label="Zona Inti Kota">
                            <option value="pedurungan">Kec. Pedurungan (Inti Kota)</option>
                            <option value="semarang tengah">Kec. Semarang Tengah (Inti Kota)</option>
                            <option value="semarang timur">Kec. Semarang Timur (Inti Kota)</option>
                            <option value="semarang selatan">Kec. Semarang Selatan (Inti Kota)</option>
                            <option value="gayamsari">Kec. Gayamsari (Inti Kota)</option>
                            <option value="candisari">Kec. Candisari (Inti Kota)</option>
                            <option value="gajahmungkur">Kec. Gajahmungkur (Inti Kota)</option>
                        </optgroup>
                        <optgroup label="Zona Perbukitan & Sub-Urban">
                            <option value="tembalang">Kec. Tembalang (Perbukitan)</option>
                            <option value="banyumanik">Kec. Banyumanik (Perbukitan)</option>
                            <option value="gunungpati">Kec. Gunungpati (Perbukitan)</option>
                            <option value="mijen">Kec. Mijen (Perbukitan)</option>
                            <option value="ngaliyan">Kec. Ngaliyan (Perbukitan)</option>
                        </optgroup>
                    </select>
                </div>

                <!-- Kriteria AHP -->
                <div class="bg-slate-50/80 border border-slate-200/80 rounded-xl p-3 space-y-3">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        2. Preferensi Bobot (AHP)
                    </label>
                    
                    <div>
                        <div class="flex justify-between items-center text-xs mb-1">
                            <span class="text-slate-600 font-medium">Akses Air</span>
                            <span id="valAir" class="bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded font-bold text-[11px]">0.7</span>
                        </div>
                        <input type="range" id="bobotAir" min="0" max="1" step="0.1" value="0.7" class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-600">
                    </div>

                    <div>
                        <div class="flex justify-between items-center text-xs mb-1">
                            <span class="text-slate-600 font-medium">Kesuburan Tanah</span>
                            <span id="valTanah" class="bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded font-bold text-[11px]">0.3</span>
                        </div>
                        <input type="range" id="bobotTanah" min="0" max="1" step="0.1" value="0.3" class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-600">
                    </div>
                </div>

                <button type="submit" id="btnSubmit" class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-sm hover:shadow transition-all flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" />
                    </svg>
                    <span>Jalankan Analisis Lahan</span>
                </button>
            </form>
        </div>

        <!-- Footer Info Singkat -->
        <div class="px-4 py-2.5 border-t border-slate-100 bg-slate-50 text-[11px] text-slate-400 flex items-center justify-between">
            <span>Random Forest (8 Fitur)</span>
            <span class="text-emerald-600 font-semibold">● Online</span>
        </div>
    </aside>

    <!-- Map Container -->
    <div class="flex-1 relative">
        <div id="map"></div>

        <!-- Tombol Buka Sidebar (Muncul saat Sidebar Ditutup) -->
        <button id="btnOpenSidebar" type="button" title="Buka Panel Analisis" class="hidden absolute top-3 left-14 z-[1000] bg-white/95 hover:bg-white text-slate-700 hover:text-emerald-700 backdrop-blur-sm shadow-md rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
            <span>Panel DSS</span>
        </button>

        <!-- Pemilih Jenis Peta (Ukuran Kecil di Pojok Kanan Atas) -->
        <div class="absolute top-3 right-3 z-[1000] bg-white/95 backdrop-blur-sm shadow-md rounded-lg border border-gray-200 px-2.5 py-1.5 flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-700 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
            </svg>
            <select id="mapTypeSelect" class="text-xs font-medium text-gray-700 bg-transparent outline-none cursor-pointer pr-1">
                <option value="satellite" selected>Peta Satelit & Citra Bumi</option>
                <option value="osm">Peta OpenStreetMap (OSM)</option>
                <option value="topo">Peta Topografi & Alam</option>
            </select>
        </div>

        <!-- Panel Hasil Analisis (Ukuran Ringkas, Bisa Digeser/Drag & Di-Minimize) -->
        <div id="resultPanel" class="hidden absolute bottom-4 right-3 w-72 glass-panel rounded-xl border border-slate-200/90 shadow-xl z-[1000] overflow-hidden select-none">
            <!-- Header Panel (Area Drag & Tombol Minimize/Close) -->
            <div id="resultPanelHeader" class="px-3 py-2 bg-slate-800 text-white flex justify-between items-center cursor-move" title="Klik dan tahan untuk menggeser panel">
                <div class="flex items-center gap-1.5 min-w-0">
                    <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                    <div class="truncate">
                        <h3 class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider leading-tight">Hasil Analisis ML</h3>
                        <p id="resAreaName" class="text-xs font-bold text-white truncate leading-tight">-</p>
                    </div>
                </div>
                <div class="flex items-center gap-1 ml-2 flex-shrink-0">
                    <button type="button" id="btnMinimizeResult" title="Minimize / Expand" class="p-1 rounded hover:bg-slate-700 text-slate-300 hover:text-white transition-colors">
                        <svg id="iconMinimize" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    </button>
                    <button type="button" onclick="document.getElementById('resultPanel').classList.add('hidden')" title="Tutup" class="p-1 rounded hover:bg-slate-700 text-slate-300 hover:text-red-400 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            
            <!-- Body Panel Hasil -->
            <div id="resultPanelBody" class="p-2.5 space-y-1.5 text-xs bg-white/95">
                <div class="flex items-center p-2 bg-green-50/90 rounded-lg border border-green-200">
                    <div class="w-2.5 h-2.5 rounded-full bg-green-500 mr-2 flex-shrink-0"></div>
                    <div class="flex-1 min-w-0 pr-1">
                        <p class="font-bold text-green-900 text-[11px] leading-tight">Urban Agriculture 1.0</p>
                        <p class="text-[10px] text-green-700 truncate">Sawah / Kebun / Lahan Terbuka</p>
                    </div>
                    <span id="statUA1" class="font-bold text-green-800 text-xs whitespace-nowrap">0 Ha</span>
                </div>
                <div class="flex items-center p-2 bg-cyan-50/90 rounded-lg border border-cyan-200">
                    <div class="w-2.5 h-2.5 rounded-full bg-cyan-500 mr-2 flex-shrink-0"></div>
                    <div class="flex-1 min-w-0 pr-1">
                        <p class="font-bold text-cyan-900 text-[11px] leading-tight">Urban Agriculture 2.0</p>
                        <p class="text-[10px] text-cyan-700 truncate">Rooftop / Hidroponik / Tambak</p>
                    </div>
                    <span id="statUA2" class="font-bold text-cyan-800 text-xs whitespace-nowrap">0 Ha</span>
                </div>
                <div class="flex items-center p-2 bg-red-50/90 rounded-lg border border-red-200">
                    <div class="w-2.5 h-2.5 rounded-full bg-red-500 mr-2 flex-shrink-0"></div>
                    <div class="flex-1 min-w-0 pr-1">
                        <p class="font-bold text-red-900 text-[11px] leading-tight">Non-Pertanian</p>
                        <p class="text-[10px] text-red-700 truncate">Terbangun / Jalan / Badan Air</p>
                    </div>
                    <span id="statNon" class="font-bold text-red-800 text-xs whitespace-nowrap">0 Ha</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inisialisasi Peta (Pusat di Semarang)
        const map = L.map('map').setView([-7.0051, 110.4381], 11);

        // Definisi Pilihan Base Layer Peta
        const baseLayers = {
            satellite: L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '© Google Satellite'
            }),
            osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }),
            topo: L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
                maxZoom: 17,
                attribution: '© OpenTopoMap (CC-BY-SA)'
            })
        };

        let currentBaseLayer = baseLayers.satellite.addTo(map);

        document.getElementById('mapTypeSelect').addEventListener('change', (e) => {
            const selected = e.target.value;
            if (baseLayers[selected]) {
                map.removeLayer(currentBaseLayer);
                currentBaseLayer = baseLayers[selected].addTo(map);
            }
        });

        // Layer Group untuk menyimpan hasil GeoJSON dari ML
        let resultLayer = L.featureGroup().addTo(map);

        // Logika Buka / Tutup Sidebar
        const sidebar = document.getElementById('sidebar');
        const btnCloseSidebar = document.getElementById('btnCloseSidebar');
        const btnOpenSidebar = document.getElementById('btnOpenSidebar');

        btnCloseSidebar.addEventListener('click', () => {
            sidebar.classList.remove('w-72', 'border-r');
            sidebar.classList.add('w-0');
            btnOpenSidebar.classList.remove('hidden');
            setTimeout(() => map.invalidateSize(), 310);
        });

        btnOpenSidebar.addEventListener('click', () => {
            sidebar.classList.remove('w-0');
            sidebar.classList.add('w-72', 'border-r');
            btnOpenSidebar.classList.add('hidden');
            setTimeout(() => map.invalidateSize(), 310);
        });

        // Update teks nilai slider
        document.getElementById('bobotAir').addEventListener('input', (e) => document.getElementById('valAir').innerText = e.target.value);
        document.getElementById('bobotTanah').addEventListener('input', (e) => document.getElementById('valTanah').innerText = e.target.value);

        // Logika Minimize & Drag pada Panel Hasil Analisis
        const resultPanel = document.getElementById('resultPanel');
        const resultPanelHeader = document.getElementById('resultPanelHeader');
        const resultPanelBody = document.getElementById('resultPanelBody');
        const btnMinimizeResult = document.getElementById('btnMinimizeResult');
        const iconMinimize = document.getElementById('iconMinimize');
        let isResultMinimized = false;

        btnMinimizeResult.addEventListener('click', (e) => {
            e.stopPropagation();
            isResultMinimized = !isResultMinimized;
            if (isResultMinimized) {
                resultPanelBody.classList.add('hidden');
                iconMinimize.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>`;
            } else {
                resultPanelBody.classList.remove('hidden');
                iconMinimize.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>`;
            }
        });

        // Fitur Drag & Drop Panel Hasil Analisis
        let isDragging = false;
        let dragOffsetX = 0;
        let dragOffsetY = 0;

        resultPanelHeader.addEventListener('mousedown', (e) => {
            if (e.target.closest('button')) return;
            isDragging = true;
            const rect = resultPanel.getBoundingClientRect();
            const parentRect = resultPanel.parentElement.getBoundingClientRect();
            dragOffsetX = e.clientX - rect.left;
            dragOffsetY = e.clientY - rect.top;
            resultPanel.style.left = (rect.left - parentRect.left) + 'px';
            resultPanel.style.top = (rect.top - parentRect.top) + 'px';
            resultPanel.style.right = 'auto';
            resultPanel.style.bottom = 'auto';
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            const parentRect = resultPanel.parentElement.getBoundingClientRect();
            let newLeft = e.clientX - parentRect.left - dragOffsetX;
            let newTop = e.clientY - parentRect.top - dragOffsetY;
            newLeft = Math.max(8, Math.min(newLeft, parentRect.width - resultPanel.offsetWidth - 8));
            newTop = Math.max(8, Math.min(newTop, parentRect.height - resultPanel.offsetHeight - 8));
            resultPanel.style.left = newLeft + 'px';
            resultPanel.style.top = newTop + 'px';
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
        });

        // Submit Form
        document.getElementById('dssForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const area_select = document.getElementById('area_select');
            if(!area_select.value) {
                alert("Silakan pilih wilayah terlebih dahulu!");
                return;
            }

            const btnSubmit = document.getElementById('btnSubmit');
            const originalText = btnSubmit.innerHTML;
            btnSubmit.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses ML...`;
            btnSubmit.disabled = true;

            const payload = {
                area_name: area_select.value,
                bobot_air: parseFloat(document.getElementById('bobotAir').value),
                bobot_tanah: parseFloat(document.getElementById('bobotTanah').value)
            };

            try {
                // Deteksi IP otomatis (Ganti port menjadi 8000 tempat ML Engine berjalan)
                const currentHost = window.location.hostname || '192.168.1.11';
                const apiUrl = `http://${currentHost}:8000/api/v1/predict_region`;

                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) throw new Error("Gagal terhubung ke ML Engine");

                const geojsonData = await response.json();
                
                // Hapus hasil sebelumnya
                resultLayer.clearLayers();

                // 1. Gambar Batas Administrasi Resmi (Garis Putus-Putus Biru)
                let boundaryBounds = null;
                if (geojsonData.boundary) {
                    const boundaryLayer = L.geoJSON(geojsonData.boundary, {
                        style: {
                            color: '#2563eb',
                            weight: 3,
                            dashArray: '6, 6',
                            fillColor: '#3b82f6',
                            fillOpacity: 0.05
                        },
                        onEachFeature: function(feature, layer) {
                            layer.bindPopup(`
                                <div class="text-center p-1">
                                    <h4 class="font-bold border-b pb-1 mb-1 text-blue-700">${feature.properties.nama}</h4>
                                    <p class="text-xs text-gray-600">Total Luas Kecamatan: <b>${feature.properties.total_luas_ha} Ha</b></p>
                                </div>
                            `);
                        }
                    }).addTo(resultLayer);
                    boundaryBounds = boundaryLayer.getBounds();
                }

                // 2. Tambahkan Poligon Hasil Klasifikasi ML
                const newLayer = L.geoJSON(geojsonData, {
                    style: function(feature) {
                        return {
                            fillColor: feature.properties.color,
                            weight: 1,
                            opacity: 1,
                            color: 'white',
                            fillOpacity: 0.7
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        const p = feature.properties;
                        const techHtml = p.teknologi && p.teknologi.length > 0 
                            ? `<div class="mt-2 pt-1.5 border-t border-gray-100 text-left text-[11px] text-slate-600">
                                 <b class="text-gray-700">Teknologi Disarankan:</b>
                                 <ul class="list-disc list-inside mt-0.5 space-y-0.5 text-gray-500">
                                   ${p.teknologi.slice(0, 3).map(t => `<li>${t}</li>`).join('')}
                                 </ul>
                               </div>`
                            : '';

                        layer.bindPopup(`
                            <div class="p-2 min-w-[240px]">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background-color: ${p.color}"></span>
                                    <span class="text-[10px] font-bold tracking-wider uppercase" style="color: ${p.color}">
                                        ${p.tipologi || 'Kesesuaian'}
                                    </span>
                                </div>
                                <h4 class="font-bold text-sm text-gray-900 border-b pb-1">${p.sub_tipologi || p.kategori}</h4>
                                <div class="flex justify-between text-xs text-gray-500 my-1">
                                    <span>Skor TOPSIS: <b class="text-emerald-700">${p.skor}%</b></span>
                                    <span>Luas: <b>${p.luas_ha} Ha</b></span>
                                </div>
                                <p class="text-[11px] text-gray-600 bg-slate-50 p-2 rounded border border-slate-100 mt-1">${p.rekomendasi}</p>
                                ${techHtml}
                            </div>
                        `);
                    }
                }).addTo(resultLayer);

                // Zoom peta tepat ke batas wilayah kecamatan resmi
                if (boundaryBounds && boundaryBounds.isValid()) {
                    map.fitBounds(boundaryBounds, { padding: [40, 40] });
                } else {
                    map.fitBounds(newLayer.getBounds(), { padding: [50, 50] });
                }

                // Tampilkan Panel Hasil dengan Tipologi 1.0 & 2.0
                document.getElementById('resAreaName').innerText = area_select.options[area_select.selectedIndex].text;
                document.getElementById('statUA1').innerText = (geojsonData.summary.luas_ua_1_ha || 0).toFixed(2) + ' Ha';
                document.getElementById('statUA2').innerText = (geojsonData.summary.luas_ua_2_ha || 0).toFixed(2) + ' Ha';
                document.getElementById('statNon').innerText = (geojsonData.summary.luas_non_pertanian_ha || 0).toFixed(2) + ' Ha';
                if (isResultMinimized) {
                    isResultMinimized = false;
                    resultPanelBody.classList.remove('hidden');
                    iconMinimize.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>`;
                }
                document.getElementById('resultPanel').classList.remove('hidden');

            } catch (error) {
                alert("Error: " + error.message + "\nPastikan Server Python berjalan di port 8000.");
                console.error(error);
            } finally {
                btnSubmit.innerHTML = originalText;
                btnSubmit.disabled = false;
            }
        });
    </script>
</body>
</html>