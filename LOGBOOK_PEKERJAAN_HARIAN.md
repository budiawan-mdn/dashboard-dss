# LOGBOOK AKTIVITAS PENGEMBANGAN SISTEM SPATIAL DSS URBAN AGRICULTURE
**Program Riset dan Inovasi untuk Indonesia Maju (RIIM) Kompetisi - BRIN**  
**Judul Kegiatan**: Pengembangan Spatial Decision Support System (Spatial DSS) Berbasis Machine Learning dan Multi-Criteria Decision Making untuk Masterplan Pangan Perkotaan Kota Semarang  
**Alokasi Waktu**: 40 Hari Kerja (Hingga 20 Oktober 2026)  

---

### RINGKASAN TAHAPAN PENGEMBANGAN (40 HARI KERJA)

| Fase | Alokasi Hari | Fokus Utama Kegiatan |
| :--- | :--- | :--- |
| **Fase I** | Hari 1 – 5 | Telaah Proposal, Persiapan Lingkungan Sistem, dan Akuisisi Citra Satelit & DEM |
| **Fase II** | Hari 6 – 10 | Pre-processing Raster, Kalkulasi Indeks Spektral (NDVI) & Slope, Eksplorasi Ground Truth |
| **Fase III** | Hari 11 – 15 | Ekstraksi Fitur Spasial, Pelatihan, Validasi & Evaluasi Model ML Random Forest |
| **Fase IV** | Hari 16 – 20 | Pengembangan Modul MCDM (AHP & TOPSIS) dan Rule-Engine Tipologi 1.0 vs 2.0 |
| **Fase V** | Hari 21 – 25 | Pembangunan Backend FastAPI Core, Vektorisasi Poligon & Kalkulasi Luas Geodesik |
| **Fase VI** | Hari 26 – 30 | Penyelarasan Wilayah Kajian (3 Kecamatan), Integrasi Web GIS & Instrumen Survei Lapangan |
| **Fase VII** | Hari 31 – 35 | Akuisisi Ground Truth Drone (9 Kelas), Penyelarasan 6 Raster Sosial-Ekonomi & Aksesibilitas |
| **Fase VIII** | Hari 36 – 40 | Pelatihan Model RF Real (8 Fitur), Ekspansi 16 Kecamatan se-Semarang & Penyempurnaan UI/UX Web GIS |

---

### DETAIL LOGBOOK HARIAN

#### MINGGU 1: Telaah Proposal, Setup Environment & Akuisisi Data Spasial
* **Hari 1 (Senin, 13 Juli 2026)**
  * **Kegiatan**: Bedah dan telaah teknis proposal penelitian DSS (`proposal-dss.pdf`), mengidentifikasi target luaran tahunan (Tahun 1: Baseline spasial & model ML, Tahun 2: Engine MCDM & Web GIS, Tahun 3: Modul RAG Kebijakan).
  * **Output**: Dokumen matriks dekomposisi kebutuhan sistem dan arsitektur data.
  * **Keterangan**: Berhasil merumuskan batas sistem dan alur integrasi data satelit ke web dashboard.

* **Hari 2 (Selasa, 14 Juli 2026)**
  * **Kegiatan**: Setup virtual environment Python (`env/`), instalasi dependency geospasial (`rasterio`, `geopandas`, `shapely`, `pyproj`, `scikit-learn`, `fastapi`, `uvicorn`, `jupyter`).
  * **Output**: Lingkungan pengembangan terisolasi yang stabil di server lokal.
  * **Keterangan**: Mengatasi dependensi pustaka GDAL/GEOS pada environment Ubuntu Linux.

* **Hari 3 (Rabu, 15 Juli 2026)**
  * **Kegiatan**: Eksplorasi dan script akuisisi data satelit Sentinel-2 multi-spektral (Kanal Red/B4 dan NIR/B8) serta Digital Elevation Model (DEM SRTM/Copernicus 30m) untuk Kota Semarang.
  * **Output**: Notebook `Unduh_Data_Satelit.ipynb` dan file awal `sentinel2_pilot.tif` serta `dem_pilot.tif`.
  * **Keterangan**: Memastikan citra memiliki tutupan awan < 5% pada koordinat bbox Semarang.

* **Hari 4 (Kamis, 16 Juli 2026)**
  * **Kegiatan**: Pembuatan skrip resampling spasial DEM agar seragam dengan resolusi spasial Sentinel-2 (10m x 10m) serta reproyeksi sistem koordinat ke WGS84 (EPSG:4326).
  * **Output**: Raster komposit awal dengan grid spasial yang selaras.
  * **Keterangan**: Evaluasi toleransi distorsi grid pixel saat dilakukan interpolasi bilinear.

* **Hari 5 (Jumat, 17 Juli 2026)**
  * **Kegiatan**: Perhitungan matematis raster Normalized Difference Vegetation Index (NDVI = (NIR - Red) / (NIR + Red)) dan turunan kemiringan lereng (Slope in percentage/degrees) menggunakan Rasterio & NumPy.
  * **Output**: Skrip kalkulasi indeks spektral dan kemiringan lereng otomatis.
  * **Keterangan**: Validasi rentang nilai NDVI (-1 s/d +1) dan Slope (0 s/d >40%).

---

#### MINGGU 2: Pre-processing Citra Luas Semarang & Dataset Ground Truth
* **Hari 6 (Senin, 20 Juli 2026)**
  * **Kegiatan**: Perluasan mosaik citra Sentinel-2 dan DEM mencakup seluruh wilayah Kota Semarang (BBox: 110.269 s/d 110.506 BT, -7.114 s/d -6.938 LS).
  * **Output**: Notebook `unduh_data_semarang.ipynb` dan file raster gabungan 2-band `semarang_ndvi_slope.tif` (ukuran ~24 MB).
  * **Keterangan**: Band 1 menyimpan nilai NDVI dan Band 2 menyimpan kemiringan lereng (Slope).

* **Hari 7 (Selasa, 21 Juli 2026)**
  * **Kegiatan**: Kompilasi dan verifikasi dataset ground truth awal tutupan lahan Kota Semarang dalam format GeoJSON (`20072026_ground_truth.geojson`).
  * **Output**: File dataset ground truth berisi 177 sampel titik terlabeli (Sawah, Terbangun, Lahan Terbuka, Badan Air).
  * **Keterangan**: Membersihkan atribut anomali dan memastikan kesesuaian proyeksi koordinat geografis.

* **Hari 8 (Rabu, 22 Juli 2026)**
  * **Kegiatan**: Eksplorasi Data Analitik (EDA) hubungan antara sebaran nilai NDVI dan kemiringan lereng terhadap klasifikasi tutupan lahan pada Jupyter Notebook.
  * **Output**: Visualisasi distribusi sebaran kelas tutupan lahan (`01_Eksperimen_Random_Forest.ipynb`).
  * **Keterangan**: Lahan sawah dan vegetasi lebat terkonsentrasi pada NDVI > 0.45; kawasan terbangun pada NDVI < 0.25.

* **Hari 9 (Kamis, 23 Juli 2026)**
  * **Kegiatan**: Ekstraksi nilai piksel raster (`rasterio.sample`) pada koordinat titik ground truth untuk membentuk matriks data tabular fitur training (NDVI, Slope, Target Kelas).
  * **Output**: Dataset training tabular siap latih (`X` matriks fitur dan `y` label kelas).
  * **Keterangan**: Menghapus data titik yang berada pada piksel NoData atau di luar batas citra.

* **Hari 10 (Jumat, 24 Juli 2026)**
  * **Kegiatan**: Pembagian dataset (Stratified Train-Test Split 80:20) dan standarisasi fitur awal untuk eksperimen Machine Learning.
  * **Output**: Pipeline data preprocessing pada `02_Eksperimen_Random_Forest.ipynb`.
  * **Keterangan**: Memastikan proporsi kelas minoritas (badan air & semak) tetap seimbang pada data latih dan uji.

---

#### MINGGU 3: Pemodelan Machine Learning Random Forest & Export Model
* **Hari 11 (Senin, 27 Juli 2026)**
  * **Kegiatan**: Pelatihan algoritma Random Forest Classifier dasar dengan 100 pohon keputusan (estimators) dan analisis baseline akurasi awal.
  * **Output**: Model awal Random Forest dengan pencatatan akurasi cross-validation.
  * **Keterangan**: Mengidentifikasi over-fitting pada pohon keputusan tanpa batas kedalaman (max_depth).

* **Hari 12 (Selasa, 28 Juli 2026)**
  * **Kegiatan**: Hyperparameter tuning menggunakan GridSearchCV (optimasi `n_estimators`, `max_depth`, `min_samples_split`, `criterion`).
  * **Output**: Konfigurasi parameter optimal Random Forest pada `03 Latih Model RF Semarang.ipynb`.
  * **Keterangan**: Menetapkan `n_estimators=100`, `max_depth=12` untuk efisiensi inferensi real-time.

* **Hari 13 (Rabu, 29 Juli 2026)**
  * **Kegiatan**: Evaluasi performa model: perhitungan confusion matrix, precision, recall, F1-score, dan Overall Accuracy pada data uji independen.
  * **Output**: Laporan performa model (Overall Accuracy > 86%).
  * **Keterangan**: Model melampaui target minimum proposal BRIN (syarat akurasi > 85%).

* **Hari 14 (Kamis, 30 Juli 2026)**
  * **Kegiatan**: Serialisasi dan ekspor artefak model terlatih ke format binary menggunakan joblib (`models/rf_model_semarang_v1.pkl`).
  * **Output**: File model binary `rf_model_semarang_v1.pkl` (ukuran ~2.4 MB).
  * **Keterangan**: Model siap di-load oleh backend API tanpa perlu pelatihan ulang saat runtime.

* **Hari 15 (Jumat, 31 Juli 2026)**
  * **Kegiatan**: Uji inferensi beban (inference latency test) terhadap citra piksel tunggal dan pengujian pipeline klasifikasi end-to-end.
  * **Output**: Skrip pengujian mandiri inferensi model (`main.old.py` / `main_ok.py`).
  * **Keterangan**: Rata-rata waktu inferensi per titik koordinat tercatat < 15 milidetik.

---

#### MINGGU 4: Rancang Bangun Engine MCDM (AHP & TOPSIS) & Tipologi Pertanian Perkotaan
* **Hari 16 (Senin, 03 Agustus 2026)**
  * **Kegiatan**: Formulasi matematis Analytic Hierarchy Process (AHP) untuk pembobotan kriteria biofisik (Ketersediaan Air, Kesuburan/Akses Tanah, Kemiringan Lereng, dan Aksesibilitas Pasar).
  * **Output**: Draf algoritma perbandingan berpasangan (pairwise comparison matrix) dan eigenvector.
  * **Keterangan**: Menetapkan skala Saaty 1–9 sebagai standar konversi preferensi pengguna.

* **Hari 17 (Selasa, 04 Agustus 2026)**
  * **Kegiatan**: Kodifikasi modul AHP ke dalam package Python terstruktur (`dss_engine/ahp.py`), dilengkapi kalkulasi Principal Eigenvalue ($\lambda_{max}$), Consistency Index ($CI$), dan Consistency Ratio ($CR$).
  * **Output**: Modul `dss_engine/ahp.py` dengan validasi konsistensi otomatis ($CR < 0.1$).
  * **Keterangan**: Jika pembobotan tidak konsisten, sistem secara dinamis melakukan normalisasi aproksimatif yang valid.

* **Hari 18 (Rabu, 05 Agustus 2026)**
  * **Kegiatan**: Formulasi dan kodifikasi algoritma TOPSIS (Technique for Order Preference by Similarity to Ideal Solution) pada `dss_engine/topsis.py`.
  * **Output**: Modul `dss_engine/topsis.py` yang mencakup normalisasi vektor, penentuan solusi ideal positif ($A^+$) dan negatif ($A^-$), serta jarak Euclidean.
  * **Keterangan**: Menghasilkan skor preferensi kedekatan relatif bernilai 0.0 s/d 100.0.

* **Hari 19 (Kamis, 06 Agustus 2026)**
  * **Kegiatan**: Telaah teori Tipologi Pertanian Perkotaan Fuzhou (Wang et al., 2019) dan penerapannya pada konteks Kota Semarang (dikotomi Urban Ag 1.0 konvensional vs Urban Ag 2.0 teknologi modern).
  * **Output**: Desain rule-base inferensi keputusan berbasis kondisi biofisik dan spasial.
  * **Keterangan**: Menetapkan sub-tipologi 2.0A (Rooftop/Vertical), 2.0B (Pesisir/Salin/Aquaponik), dan 2.0C (Perbukitan/Terasering/Agroforestri).

* **Hari 20 (Jumat, 07 Agustus 2026)**
  * **Kegiatan**: Kodifikasi rule-engine tipologi ke dalam modul `dss_engine/typology.py` dan penyusunan skema kelas keluaran (`TypologyResult`).
  * **Output**: Modul `dss_engine/typology.py` yang memetakan kelas ML + biofisik ke arahan teknologi budidaya dan skema warna peta.
  * **Keterangan**: Menghubungkan secara dinamis skor TOPSIS dengan kategori: Sangat Sesuai, Cukup Sesuai, dan Tidak Sesuai.

---

#### MINGGU 5: Pembangunan Backend RESTful API (FastAPI) & Pemrosesan Spasial
* **Hari 21 (Senin, 24 Agustus 2026)**
  * **Kegiatan**: Inisialisasi arsitektur web service FastAPI (`main.py`), konfigurasi middleware CORS (`CORSMiddleware`) untuk integrasi multi-domain, dan setup logging.
  * **Output**: Skrip service FastAPI dasar dengan health-check endpoint (`GET /`).
  * **Keterangan**: Mengizinkan integrasi aman dengan client browser dashboard Laravel.

* **Hari 22 (Selasa, 25 Agustus 2026)**
  * **Kegiatan**: Implementasi Endpoint 1: Prediksi Titik Koordinat (`POST /api/v1/prediksi`) yang mengombinasikan sampling piksel raster, prediksi ML RF, kalkulasi bobot AHP, perangkingan TOPSIS, dan inferensi tipologi.
  * **Output**: Endpoint interaktif untuk event klik koordinat pada peta Web GIS.
  * **Keterangan**: Menangani kasus penanganan error saat klik koordinat berada di luar bounding box Semarang (NoData handling).

* **Hari 23 (Rabu, 26 Agustus 2026)**
  * **Kegiatan**: Riset metode komputasi luasan wilayah: integrasi pustaka `pyproj.Geod` dengan ellipsoid WGS84 untuk menghitung luas poligon riil (geodesic area) dalam satuan hektar.
  * **Output**: Modul kalkulasi luas akurat menggantikan estimasi derajat planar yang bias.
  * **Keterangan**: Menghindari distorsi perhitungan luas akibat variasi garis lintang (latitude).

* **Hari 24 (Kamis, 27 Agustus 2026)**
  * **Kegiatan**: Implementasi Endpoint 2: Analisis Spasial Poligon Wilayah (`POST /api/v1/predict_region`) dengan teknik raster masking (`rasterio.mask`) dan vektorisasi poligon tutupan lahan (`rasterio.features.shapes`).
  * **Output**: Endpoint agregasi regional yang mengembalikan GeoJSON FeatureCollection hasil prediksi spasial.
  * **Keterangan**: Berhasil mengekstrak klaster lahan secara otomatis dari citra raster.

* **Hari 25 (Jumat, 28 Agustus 2026)**
  * **Kegiatan**: Optimalisasi performa vektorisasi poligon: penerapan filter ambang batas area minimum (> 0.05 Ha) dan sampling adaptif pada poligon non-pertanian untuk mereduksi beban payload JSON.
  * **Output**: Waktu respons query wilayah terpangkas dari > 25 detik menjadi < 6 detik dengan ukuran payload ringan.
  * **Keterangan**: Mencegah browser client mengalami lag atau crash saat render ribuan fragmen poligon mikro.

---

#### MINGGU 6: Penyesuaian Wilayah Transek 3 Kecamatan, Web GIS & Instrumen Survei Lapangan
* **Hari 26 (Senin, 14 September 2026)**
  * **Kegiatan**: Penyelarasan arah penelitian pasca-proposal: transformasi wilayah kajian dari 4 kecamatan lama menjadi 3 kecamatan transek ekologis representatif (Genuk: pesisir, Pedurungan: inti kota, Tembalang: perbukitan).
  * **Output**: Dokumentasi justifikasi saintifik perubahan wilayah kajian sesuai karakteristik Urban Ag 2.0.
  * **Keterangan**: Model transek pesisir - kota padat - perbukitan jauh lebih kokoh secara akademik untuk publikasi ilmiah.

* **Hari 27 (Selasa, 15 September 2026)**
  * **Kegiatan**: Akuisisi, pembersihan topologi, dan integrasi file batas administrasi resmi GeoJSON 3 kecamatan ke sistem (`data/batas-admin/`).
  * **Output**: File `Kecamatan Genuk-KECAMATAN.geojson`, `Kecamatan Pedurungan-KECAMATAN.geojson`, dan `Kecamatan Tembalang-KECAMATAN.geojson`.
  * **Keterangan**: Mengoreksi anomali inversi koordinat (X/Y swap) pada file GeoJSON Pedurungan sehingga poligon terpasang presisi.

* **Hari 28 (Rabu, 16 September 2026)**
  * **Kegiatan**: Integrasi penuh frontend Web GIS pada dashboard Laravel (`/var/www/html/dss-pangan`): modifikasi `resources/views/dss.blade.php` (analisis titik) dan `resources/views/dssarea.blade.php` (analisis wilayah & grafik metrik).
  * **Output**: Antarmuka peta interaktif Leaflet.js dengan kontrol layer batas resmi 3 kecamatan dan panel visualisasi tipologi.
  * **Keterangan**: Tampilan antarmuka berhasil diuji coba pada jaringan lokal (`http://192.168.1.10`).

* **Hari 29 (Kamis, 17 September 2026)**
  * **Kegiatan**: Desain instrumen survei lapangan dan instrumen sosial-ekonomi: perumusan struktur data ground truth (`template_survei_lapangan.csv`) dan data profil kelurahan (`template_data_sosial_ekonomi.csv`).
  * **Output**: Dua template data terstandar dan penetapan kuota sampling ideal (150–180 titik; 50–60 titik per kecamatan).
  * **Keterangan**: Kolom template dirancang langsung cocok dengan input dataframe pandas untuk re-training model ML.

* **Hari 30 (Jumat, 18 September 2026 / Finalisasi Penyelarasan)**
  * **Kegiatan**: Telaah permohonan data kedinasan (`permohonan-data.pdf` / Surat No. 333/IX/CARITRA/2026 ke Distaru Semarang), penyusunan justifikasi integrasi data RTRW/RDTR untuk modul RAG Tahun 3, serta kompilasi dokumen teknis final.
  * **Output**: File komprehensif `Ringkasan_Penyelarasan_Sistem_DSS_dan_Panduan_Survei.pdf` dan dokumen matriks evaluasi kesesuaian proposal 100%.
  * **Keterangan**: Seluruh modul backend, model ML, engine DSS, dashboard Web GIS, dan instrumen survei dinyatakan siap operasional.

---

#### MINGGU 7: Akuisisi Ground Truth Drone (9 Kelas) & Rasterisasi Data Sosial-Ekonomi
* **Hari 31 (Senin, 12 Oktober 2026)**
  * **Kegiatan**: Pengumpulan dan kompilasi data sekunder kependudukan, sosial-ekonomi pertanian (jumlah penduduk, kepadatan penduduk, rumah tangga petani, kelompok tani, usaha tani), serta titik lokasi pasar di Kota Semarang.
  * **Output**: Dataset tabular dan vektor atribut sosial-ekonomi serta jaringan pasar Kota Semarang.
  * **Keterangan**: Data diverifikasi dan dinormalisasi per unit administrasi untuk proses konversi ke layer raster.

* **Hari 32 (Selasa, 13 Oktober 2026)**
  * **Kegiatan**: Pembuatan layer raster jarak Euclidean terhadap titik pasar terdekat (aksesibilitas pasar) dan rasterisasi variabel sosial-ekonomi pertanian menggunakan QGIS/GDAL.
  * **Output**: File raster awal variabel jarak pasar dan kependudukan/pertanian.
  * **Keterangan**: Mengukur kedekatan spasial lahan terhadap pusat distribusi pangan perkotaan.

* **Hari 33 (Rabu, 14 Oktober 2026)**
  * **Kegiatan**: Penyelarasan grid spasial (*spatial alignment*, *cropping*, dan *resampling*) seluruh layer raster prediktor terhadap raster master `semarang_ndvi_slope_croped_save_as.tif` (dimensi `1968 x 2634` piksel, CRS `EPSG:4326`).
  * **Output**: 6 file raster prediktor selaras: `jarak_titik_pasar_croped_align_2.tif`, `kepadatan_penduduk_align.tif`, `jumlah_penduduk_align.tif`, `jumlah_rt_petani_align.tif`, `jumlah_kelompok_tani_align.tif`, dan `jumlah_usaha_tani_align.tif`.
  * **Keterangan**: Memastikan seluruh 7 file raster memiliki *bounding box* dan resolusi piksel yang identik 100%.

* **Hari 34 (Kamis, 15 Oktober 2026)**
  * **Kegiatan**: Digitasi dan pelabelan poligon sampel *ground truth* beresolusi tinggi hasil pemetaan foto udara/drone ke dalam 9 kelas tutupan lahan spesifik perkotaan.
  * **Output**: Skema klasifikasi 9 kelas: (1) Pertanian Sawah, (2) Tambak/Perikanan, (3) Kebun & Perkebunan, (4) Lahan Terbangun, (5) Vegetasi & Hutan, (6) Tubuh Air & Pesisir, (7) Rooftop/Atap Beton, (8) Lahan Kosong, dan (9) Jalan Aspal/Lingkungan.
  * **Keterangan**: Penambahan kelas spesifik *Rooftop/Atap Beton* (Kelas 7) dan *Tambak* (Kelas 2) untuk mempertajam deteksi *Urban Agriculture 2.0*.

* **Hari 35 (Jumat, 16 Oktober 2026)**
  * **Kegiatan**: Validasi topologi dan finalisasi atribut dataset vektor *ground truth* drone sebanyak 290 poligon sampel lapangan.
  * **Output**: File dataset `ground_truth_drone_19102026.geojson` siap ekstraksi.
  * **Keterangan**: Memastikan tidak ada poligon yang *self-intersecting* atau memiliki label kelas kosong.

---

#### MINGGU 8: Pelatihan Model RF Real (8 Fitur), Ekspansi 16 Kecamatan & Upgrade Web GIS
* **Hari 36 (Senin, 19 Oktober 2026)**
  * **Kegiatan**: Penyusunan pipeline ekstraksi multi-raster (rasterisasi 290 poligon *ground truth* drone terhadap 8 fitur prediktor) dan tabulasi matriks pelatihan pada Jupyter Notebook (`03 Pelatihan Random Forest Real.ipynb`).
  * **Output**: Matriks dataset pelatihan multi-dimensi (8 fitur: `ndvi`, `slope`, `jarak_pasar`, `kepadatan_penduduk`, `jumlah_penduduk`, `rt_petani`, `kelompok_tani`, `usaha_tani`) bebas nilai `NoData`/`NaN`.
  * **Keterangan**: Pembagian data menggunakan *Stratified Train-Test Split* (80% latih : 20% uji).

* **Hari 37 (Selasa Pagi, 20 Oktober 2026 — Sesi 1)**
  * **Kegiatan**: Pelatihan model *Random Forest Classifier* (`n_estimators=100`, `max_depth=12`) dengan 8 fitur prediktor dan 9 kelas target, evaluasi *Confusion Matrix*, serta ekspor model produksi baru.
  * **Output**: File model terlatih `rf_model_semarang_real_v1.pkl` (~7.6 MB) dan grafik evaluasi akurasi per kelas.
  * **Keterangan**: Model mampu membedakan target utama *Urban Farming* (Kelas 1 Sawah, Kelas 3 Kebun, Kelas 7 Rooftop, Kelas 8 Lahan Kosong) dengan presisi tinggi.

* **Hari 38 (Selasa Siang, 20 Oktober 2026 — Sesi 2)**
  * **Kegiatan**: Pembaruan backend FastAPI (`main.py`) dan modul inferensi tipologi (`dss_engine/typology.py`) untuk mengintegrasikan model 8 fitur (`rf_model_semarang_real_v1.pkl`), pemrosesan *masking* 7 file raster secara simultan, serta penerapan filter `rasterio.features.sieve`.
  * **Output**: Endpoint `/api/v1/prediksi` dan `/api/v1/predict_region` versi 8 fitur yang mendukung penuh 9 kelas tutupan lahan beserta penanganan *exception* CORS.
  * **Keterangan**: Mengatasi *error* ketidakcocokan dimensi fitur (`2 features vs 8 features`) dan mempercepat waktu *rendering* poligon menjadi < 1 detik per kecamatan.

* **Hari 39 (Selasa Sore, 20 Oktober 2026 — Sesi 3)**
  * **Kegiatan**: Akuisisi dan integrasi file batas administrasi resmi GeoJSON untuk seluruh **16 kecamatan di Kota Semarang** ke dalam `data/batas-admin/` serta pembaruan logika zonasi ekologis pada backend dan dropdown Web GIS.
  * **Output**: 16 file `.geojson` kecamatan terintegrasi otomatis, dikelompokkan dalam 3 zona: **Zona Pesisir** (Genuk, Tugu, Semarang Utara, Semarang Barat), **Zona Inti Kota** (Pedurungan, Semarang Tengah, Semarang Timur, Semarang Selatan, Gayamsari, Candisari, Gajahmungkur), dan **Zona Perbukitan & Sub-Urban** (Tembalang, Banyumanik, Gunungpati, Mijen, Ngaliyan).
  * **Keterangan**: Menambahkan deteksi lereng curam (`> 12%`) otomatis untuk memisahkan rekomendasi *Urban Agriculture 2.0C (Terasering Cerdas)* di seluruh kecamatan berbukit.

* **Hari 40 (Selasa Sore, 20 Oktober 2026 — Sesi 4)**
  * **Kegiatan**: Penyempurnaan antarmuka pengguna (UI/UX) pada halaman Spatial Titik (`dss.blade.php`) dan Analisis Wilayah (`dssarea.blade.php`).
  * **Output**:
    1. Penambahan kontrol pemilih jenis peta dasar (*Peta Satelit & Citra Bumi*, *Peta OpenStreetMap*, dan *Peta Topografi & Alam*).
    2. Desain ulang sidebar yang lebih ramping (`w-72`), modern, dilengkapi *segmented pill navigation* dan tombol buka/tutup (*collapse/expand*) sidebar.
    3. Pembaruan panel **Hasil Analisis ML** menjadi lebih ringkas di pojok kanan bawah, dilengkapi fitur geser bebas (*draggable*) dan tombol *minimize/expand* agar tidak menutupi visualisasi peta.
  * **Keterangan**: Sistem Spatial DSS Pangan Kota Semarang telah beroperasi penuh untuk seluruh 16 kecamatan.

---

### RINGKASAN CAPAIAN UTAMA HINGGA HARI KE-40 (20 OKTOBER 2026)
1. **Model Machine Learning Generasi Baru (8 Fitur & 9 Kelas)**: Random Forest Classifier (`rf_model_semarang_real_v1.pkl`) yang dilatih menggunakan 290 poligon *ground truth* drone (`ground_truth_drone_19102026.geojson`) dan 7 file raster terpadu (NDVI, Slope, Jarak Pasar, Kepadatan Penduduk, Jumlah Penduduk, RT Petani, Kelompok Tani, Usaha Tani).
2. **Cakupan Penuh 16 Kecamatan se-Kota Semarang**: Sistem mendukung analisis spasial untuk seluruh 16 kecamatan di Kota Semarang yang terbagi secara adaptif ke dalam Zona Pesisir, Zona Inti Kota, dan Zona Perbukitan/Sub-Urban.
3. **Engine Multi-Kriteria & Tipologi 9 Kelas**: Modul AHP (`ahp.py`), TOPSIS (`topsis.py`), dan Tipologi (`typology.py`) telah mendukung 9 kelas tutupan lahan termasuk deteksi khusus *Rooftop/Atap Beton* (UA 2.0A), *Tambak Pesisir* (UA 2.0B), serta *Lahan Terbuka Lereng Curam > 12%* (UA 2.0C).
4. **Web GIS Interaktif & Ergonomis**: Dashboard Laravel (`dss.blade.php` & `dssarea.blade.php`) dilengkapi *multi-basemap switcher*, *collapsible compact sidebar*, serta *draggable & minimizable result panel*.
