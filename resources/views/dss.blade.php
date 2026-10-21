<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masterplan Pangan Perkotaan - DSS Dashboard</title>
    
    <!-- Memuat Tailwind CSS untuk Styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Memuat Leaflet.js untuk Peta Interaktif -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Font dari Google -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"> -->
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Mengamankan ukuran peta agar tidak tumpah */
        #map { height: 100%; min-height: 400px; z-index: 10; }
        
        /* Kustomisasi scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 h-screen flex overflow-hidden">

    <!-- SIDEBAR KIRI: Form Input & Konfigurasi AHP (Ukuran Ramping & Bisa Dibuka/Tutup) -->
    <aside id="sidebar" class="w-72 bg-white border-r border-slate-200 flex flex-col h-full shadow-lg z-20 relative transition-all duration-300 ease-in-out flex-shrink-0 overflow-hidden">
        <!-- Header Sidebar -->
        <div class="px-4 py-3.5 bg-gradient-to-r from-emerald-800 to-emerald-600 text-white flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-sm font-bold tracking-tight leading-tight">Spatial DSS</h1>
                    <p class="text-[11px] text-emerald-100 leading-tight">Masterplan Pangan Perkotaan</p>
                </div>
            </div>
            <button id="btnCloseSidebar" type="button" title="Tutup Sidebar" class="p-1.5 rounded-lg text-emerald-100 hover:text-white hover:bg-white/15 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <div class="p-4 flex-1 overflow-y-auto space-y-4">
            <!-- Navigasi Mode (Segmented Pill) -->
            <div class="grid grid-cols-2 gap-1 p-1 bg-slate-100 rounded-lg border border-slate-200/80">
                <a href="/" class="text-center py-1.5 px-2 text-xs font-semibold bg-white text-emerald-700 shadow-sm rounded-md transition-all">
                    📍 Spatial Titik
                </a>
                <a href="/dss-area" class="text-center py-1.5 px-2 text-xs font-medium text-slate-600 hover:text-slate-900 rounded-md transition-all">
                    🗺️ Wilayah
                </a>
            </div>

            <!-- Lokasi Lahan -->
            <div class="bg-slate-50/80 border border-slate-200/80 rounded-xl p-3">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">1. Lokasi Lahan</h3>
                <p class="text-[11px] text-slate-400 mb-2.5">Klik pada peta untuk memilih titik koordinat.</p>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[11px] font-medium text-slate-500 mb-1">Latitude</label>
                        <input type="text" id="lat" class="w-full text-xs border border-slate-300 rounded-lg px-2.5 py-1.5 bg-white font-mono text-slate-700" readonly placeholder="-7.0051">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-slate-500 mb-1">Longitude</label>
                        <input type="text" id="lng" class="w-full text-xs border border-slate-300 rounded-lg px-2.5 py-1.5 bg-white font-mono text-slate-700" readonly placeholder="110.4381">
                    </div>
                </div>
            </div>

            <!-- Parameter MCDM (AHP/TOPSIS) -->
            <div class="bg-slate-50/80 border border-slate-200/80 rounded-xl p-3 space-y-3">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">2. Bobot Kriteria (AHP)</h3>
                
                <div>
                    <div class="flex justify-between items-center text-xs mb-1">
                        <label class="font-medium text-slate-600">Ketersediaan Air</label>
                        <span id="val_air" class="bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded font-bold text-[11px]">0.5</span>
                    </div>
                    <input type="range" id="bobot_air" min="0" max="1" step="0.1" value="0.5" class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-600">
                </div>

                <div>
                    <div class="flex justify-between items-center text-xs mb-1">
                        <label class="font-medium text-slate-600">Kualitas Tanah (NDVI)</label>
                        <span id="val_tanah" class="bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded font-bold text-[11px]">0.3</span>
                    </div>
                    <input type="range" id="bobot_tanah" min="0" max="1" step="0.1" value="0.3" class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-600">
                </div>

                <div>
                    <div class="flex justify-between items-center text-xs mb-1">
                        <label class="font-medium text-slate-600">Aksesibilitas</label>
                        <span id="val_akses" class="bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded font-bold text-[11px]">0.2</span>
                    </div>
                    <input type="range" id="bobot_akses" min="0" max="1" step="0.1" value="0.2" class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-600">
                </div>
            </div>

            <!-- Tombol Eksekusi -->
            <button id="btn-predict" class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold py-2.5 px-4 rounded-xl transition-all shadow-sm hover:shadow flex justify-center items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" />
                </svg>
                <span>Jalankan Analisis Lahan</span>
            </button>
            
            <!-- Area Notifikasi Status -->
            <div id="status-box" class="p-2.5 rounded-lg text-xs hidden"></div>
        </div>

        <!-- Footer Info Singkat -->
        <div class="px-4 py-2.5 border-t border-slate-100 bg-slate-50 text-[11px] text-slate-400 flex items-center justify-between">
            <span>Random Forest (8 Fitur)</span>
            <span class="text-emerald-600 font-semibold">● Online</span>
        </div>
    </aside>

    <!-- KONTEN UTAMA: Peta & Hasil Analisis -->
    <main class="flex-1 flex flex-col relative bg-slate-100">
        <!-- Peta Leaflet -->
        <div class="flex-1 p-3 relative">
            <div id="map" class="rounded-xl shadow-inner border border-slate-200"></div>

            <!-- Tombol Buka Sidebar (Muncul saat Sidebar Ditutup) -->
            <button id="btnOpenSidebar" type="button" title="Buka Panel Analisis" class="hidden absolute top-6 left-16 z-[1000] bg-white/95 hover:bg-white text-slate-700 hover:text-emerald-700 backdrop-blur-sm shadow-md rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
                <span>Panel DSS</span>
            </button>

            <!-- Pemilih Jenis Peta (Ukuran Kecil di Pojok Kanan Atas) -->
            <div class="absolute top-7 right-7 z-[1000] bg-white/95 backdrop-blur-sm shadow-md rounded-lg border border-slate-200 px-2.5 py-1.5 flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-700 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
                <select id="mapTypeSelect" class="text-xs font-medium text-slate-700 bg-transparent outline-none cursor-pointer pr-1">
                    <option value="satellite" selected>Peta Satelit & Citra Bumi</option>
                    <option value="osm">Peta OpenStreetMap (OSM)</option>
                    <option value="topo">Peta Topografi & Alam</option>
                </select>
            </div>
        </div>

        <!-- Panel Hasil Analisis (Ukuran Ringkas, Bisa Digeser/Drag & Di-Minimize) -->
        <div id="result-panel" class="absolute bottom-5 right-5 w-96 max-w-[92%] bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden hidden z-[1000] select-none">
            <!-- Header Panel (Area Drag & Tombol Minimize/Close) -->
            <div id="result-panel-header" class="px-3.5 py-2.5 bg-slate-800 text-white flex justify-between items-center cursor-move" title="Klik dan tahan untuk menggeser panel">
                <div class="flex items-center gap-2 min-w-0">
                    <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                    <div class="truncate">
                        <div class="flex items-center gap-1.5">
                            <span id="res-badge" class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                                Urban Agriculture 1.0
                            </span>
                            <span id="res-kec" class="text-[10px] font-semibold text-slate-300 bg-slate-700 px-1.5 py-0.5 rounded">
                                Kecamatan -
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1 ml-2 flex-shrink-0">
                    <button type="button" id="btn-minimize-res" title="Minimize / Expand" class="p-1 rounded hover:bg-slate-700 text-slate-300 hover:text-white transition-colors">
                        <svg id="icon-minimize-res" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                    </button>
                    <button type="button" id="btn-close-res" title="Tutup" class="p-1 rounded hover:bg-slate-700 text-slate-300 hover:text-red-400 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            <!-- Body Panel Hasil -->
            <div id="result-panel-body" class="p-3.5 space-y-2.5 bg-white/95">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <div class="min-w-0 pr-2">
                        <h2 id="res-subtype" class="text-xs font-bold text-slate-800 leading-snug">Hasil Prediksi & Rekomendasi</h2>
                        <p class="text-[11px] text-slate-400">Koordinat: <span id="res-coord" class="font-mono text-slate-600"></span></p>
                    </div>
                    <div class="bg-emerald-50 rounded-lg px-2.5 py-1.5 text-center border border-emerald-100 flex-shrink-0">
                        <p class="text-[9px] text-emerald-700 uppercase font-bold leading-none">Skor TOPSIS</p>
                        <div id="res-score" class="text-lg font-extrabold text-emerald-600 leading-tight">0</div>
                    </div>
                </div>
                
                <div>
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Rekomendasi Kebijakan & Strategi:</p>
                    <p id="res-recommendation" class="text-xs text-slate-700 bg-slate-50 p-2.5 rounded-lg border border-slate-200/80 leading-relaxed"></p>
                    <div id="res-tech-box" class="mt-2 text-[11px] text-slate-600 hidden">
                        <span class="font-bold text-slate-700">Teknologi / Aksi Disarankan:</span>
                        <ul id="res-tech-list" class="list-disc list-inside mt-0.5 text-slate-500 space-y-0.5"></ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div id="result-panel" class="absolute bottom-6 left-1/2 transform -translate-x-1/2 w-3/4 max-w-3xl bg-white rounded-xl shadow-xl border border-slate-200 p-6 hidden transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Hasil Prediksi & Rekomendasi (Random Forest)</h2>
                    <p class="text-sm text-slate-500">Koordinat: <span id="res-coord" class="font-mono"></span></p>
                </div>
                <button id="btn-close-res" class="text-slate-400 hover:text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-1 bg-slate-50 rounded-lg p-4 text-center border border-slate-100">
                    <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Skor Kesesuaian</p>
                    <div id="res-score" class="text-4xl font-bold text-emerald-600">0</div>
                    <p class="text-xs text-slate-400 mt-2">Berdasarkan MCDM-TOPSIS</p>
                </div>
                <div class="col-span-2 flex flex-col justify-center">
                    <p class="text-sm text-slate-600 mb-1">Rekomendasi Strategi:</p>
                    <p id="res-recommendation" class="text-lg font-medium text-slate-800 bg-emerald-50 p-3 rounded-lg border border-emerald-100"></p>
                    <p class="text-xs text-slate-400 mt-3">* Hasil ini dikalkulasi secara *real-time* oleh ML Engine Python.</p>
                </div>
            </div>
        </div> -->
    </main>

    <script>
        // 1. Inisialisasi Peta (Difokuskan ke Kota Yogyakarta)
        // const yogyakarta = [-7.7956, 110.3695];
        // const map = L.map('map').setView(yogyakarta, 13);

        // // Menambahkan layer peta dasar dari OpenStreetMap
        // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //     attribution: '&copy; OpenStreetMap contributors'
        // }).addTo(map);
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

        let currentMarker = null;

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

        // 2. Event Klik pada Peta untuk mengambil koordinat
        map.on('click', function(e) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);

            // Update form input
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;

            // Update/Buat Marker
            if (currentMarker) {
                currentMarker.setLatLng(e.latlng);
            } else {
                currentMarker = L.marker(e.latlng).addTo(map);
            }
        });

        // 3. Update Text Label untuk Slider AHP
        const updateSliderLabel = (sliderId, valId) => {
            const slider = document.getElementById(sliderId);
            const val = document.getElementById(valId);
            slider.addEventListener('input', (e) => {
                val.textContent = e.target.value;
            });
        };
        updateSliderLabel('bobot_air', 'val_air');
        updateSliderLabel('bobot_tanah', 'val_tanah');
        updateSliderLabel('bobot_akses', 'val_akses');

        // 4. Logika Interaksi API (Simulasi ke ML Engine)
        const btnPredict = document.getElementById('btn-predict');
        const statusBox = document.getElementById('status-box');
        const resultPanel = document.getElementById('result-panel');

        btnPredict.addEventListener('click', async () => {
            const lat = document.getElementById('lat').value;
            const lng = document.getElementById('lng').value;

            if (!lat || !lng) {
                showStatus('Pilih koordinat di peta terlebih dahulu!', 'error');
                return;
            }

            // Ambil data payload
            const payload = {
                latitude: parseFloat(lat),
                longitude: parseFloat(lng),
                bobot_kriteria: {
                    air: parseFloat(document.getElementById('bobot_air').value),
                    tanah: parseFloat(document.getElementById('bobot_tanah').value),
                    akses: parseFloat(document.getElementById('bobot_akses').value)
                }
            };

            // Tampilkan Loading
            showStatus('Mengirim data ke ML Engine...', 'loading');
            resultPanel.classList.add('hidden');
            btnPredict.disabled = true;
            btnPredict.classList.add('opacity-75', 'cursor-not-allowed');

            try {
                // Memanggil REST API (FastAPI) yang berjalan di port 8000
                const currentHost = window.location.hostname || '192.168.1.11';                
                const response = await fetch(`http://${currentHost}:8000/api/v1/prediksi`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                console.log(response);

                if (!response.ok) {
                    throw new Error(`Terjadi kesalahan pada server ML Engine (Status: ${response.status})`);
                }

                // Menangkap data JSON yang dikirim balik oleh Python
                const data = await response.json();

                // Tampilkan Hasil dari ML Engine Python ke Antarmuka
                document.getElementById('res-coord').textContent = `${data.koordinat.lat}, ${data.koordinat.lng}`;
                document.getElementById('res-kec').textContent = `Kecamatan ${data.kecamatan}`;
                
                const badge = document.getElementById('res-badge');
                badge.textContent = data.tipologi;
                if (data.tipologi === 'Urban Agriculture 1.0') {
                    badge.className = "px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-800";
                } else if (data.tipologi === 'Urban Agriculture 2.0') {
                    badge.className = "px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-cyan-100 text-cyan-800";
                } else {
                    badge.className = "px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-800";
                }

                document.getElementById('res-subtype').textContent = data.sub_tipologi || data.kategori;

                // Efek Counter angka skor
                animateValue('res-score', 0, data.skor_topsis, 800);
                
                document.getElementById('res-recommendation').textContent = data.rekomendasi;

                const techBox = document.getElementById('res-tech-box');
                const techList = document.getElementById('res-tech-list');
                techList.innerHTML = '';
                if (data.teknologi_disarankan && data.teknologi_disarankan.length > 0) {
                    data.teknologi_disarankan.forEach(t => {
                        const li = document.createElement('li');
                        li.textContent = t;
                        techList.appendChild(li);
                    });
                    techBox.classList.remove('hidden');
                } else {
                    techBox.classList.add('hidden');
                }

                showStatus('Analisis selesai dari Server ML.', 'success');
                if (isResMinimized) {
                    isResMinimized = false;
                    resPanelBody.classList.remove('hidden');
                    iconMinimizeRes.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />`;
                }
                resultPanel.classList.remove('hidden');

            } catch (error) {
                showStatus('Gagal terhubung ke ML Engine. Pastikan server Python menyala!', 'error');
                console.error(error);
            } finally {
                btnPredict.disabled = false;
                btnPredict.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        });

        // Helper: Tutup, Minimize, & Drag panel hasil
        const resPanelHeader = document.getElementById('result-panel-header');
        const resPanelBody = document.getElementById('result-panel-body');
        const btnMinimizeRes = document.getElementById('btn-minimize-res');
        const iconMinimizeRes = document.getElementById('icon-minimize-res');
        let isResMinimized = false;

        btnMinimizeRes.addEventListener('click', (e) => {
            e.stopPropagation();
            isResMinimized = !isResMinimized;
            if (isResMinimized) {
                resPanelBody.classList.add('hidden');
                iconMinimizeRes.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />`;
            } else {
                resPanelBody.classList.remove('hidden');
                iconMinimizeRes.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />`;
            }
        });

        document.getElementById('btn-close-res').addEventListener('click', () => {
            resultPanel.classList.add('hidden');
        });

        let isDraggingRes = false;
        let dragResOffsetX = 0;
        let dragResOffsetY = 0;

        resPanelHeader.addEventListener('mousedown', (e) => {
            if (e.target.closest('button')) return;
            isDraggingRes = true;
            const rect = resultPanel.getBoundingClientRect();
            const parentRect = resultPanel.parentElement.getBoundingClientRect();
            dragResOffsetX = e.clientX - rect.left;
            dragResOffsetY = e.clientY - rect.top;
            resultPanel.style.left = (rect.left - parentRect.left) + 'px';
            resultPanel.style.top = (rect.top - parentRect.top) + 'px';
            resultPanel.style.right = 'auto';
            resultPanel.style.bottom = 'auto';
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDraggingRes) return;
            const parentRect = resultPanel.parentElement.getBoundingClientRect();
            let newLeft = e.clientX - parentRect.left - dragResOffsetX;
            let newTop = e.clientY - parentRect.top - dragResOffsetY;
            newLeft = Math.max(8, Math.min(newLeft, parentRect.width - resultPanel.offsetWidth - 8));
            newTop = Math.max(8, Math.min(newTop, parentRect.height - resultPanel.offsetHeight - 8));
            resultPanel.style.left = newLeft + 'px';
            resultPanel.style.top = newTop + 'px';
        });

        document.addEventListener('mouseup', () => {
            isDraggingRes = false;
        });

        // Helper: Menampilkan status pesan
        function showStatus(message, type) {
            statusBox.textContent = message;
            statusBox.classList.remove('hidden', 'bg-red-100', 'text-red-700', 'bg-blue-100', 'text-blue-700', 'bg-emerald-100', 'text-emerald-700');
            
            if (type === 'error') statusBox.classList.add('bg-red-100', 'text-red-700');
            else if (type === 'loading') statusBox.classList.add('bg-blue-100', 'text-blue-700');
            else if (type === 'success') statusBox.classList.add('bg-emerald-100', 'text-emerald-700');
        }

        // Helper: Animasi Counter Angka
        function animateValue(id, start, end, duration) {
            const obj = document.getElementById(id);
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                obj.innerHTML = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }
    </script>
</body>
</html>