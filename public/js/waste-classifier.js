/**
 * waste-classifier.js
 * -----------------------------------------------------------------
 * Modul untuk load model TensorFlow.js hasil training MobileNetV2
 * (Organik vs Anorganik) dan menjalankan prediksi di browser.
 *
 * Cara pakai di Laravel Blade:
 *   <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.20.0/dist/tf.min.js"></script>
 *   <script src="{{ asset('js/waste-classifier.js') }}"></script>
 *   <script>
 *     WasteClassifier.load().then(() => console.log('Model siap'));
 *     WasteClassifier.classify(imgElement).then(result => console.log(result));
 *   </script>
 *
 * PENTING: sesuaikan MODEL_URL & LABELS_URL di bawah dengan lokasi
 * file model.json / labels.json Anda di folder public Laravel.
 * -----------------------------------------------------------------
 */

const WasteClassifier = (() => {
  // ================== KONFIGURASI (sesuaikan bila perlu) ==================
  const MODEL_URL = '/model/model.json';   // path ke model.json hasil konversi TFJS
  const LABELS_URL = '/model/labels.json'; // path ke labels.json
  const IMG_SIZE = 224;                    // HARUS sama dengan ukuran input saat training
  // ==========================================================================

  let model = null;
  let labels = null;
  let loadingPromise = null;

  /**
   * Load model + labels sekali saja (dipanggil otomatis oleh classify()
   * kalau belum di-load manual sebelumnya). Aman dipanggil berkali-kali,
   * hanya akan benar-benar load sekali.
   */
  function load() {
    if (model && labels) {
      return Promise.resolve({ model, labels });
    }
    if (loadingPromise) {
      return loadingPromise; // sedang proses load, jangan load dobel
    }

    loadingPromise = Promise.all([
      tf.loadLayersModel(MODEL_URL),
      fetch(LABELS_URL).then((res) => {
        if (!res.ok) {
          throw new Error(`Gagal memuat labels.json (status ${res.status})`);
        }
        return res.json();
      }),
    ]).then(([loadedModel, loadedLabels]) => {
      model = loadedModel;
      labels = loadedLabels;

      // Warm-up: jalankan satu prediksi dummy supaya prediksi PERTAMA
      // dari user tidak lambat (TFJS meng-compile graph saat run pertama).
      const warmup = tf.zeros([1, IMG_SIZE, IMG_SIZE, 3]);
      const warmupResult = model.predict(warmup);
      warmup.dispose();
      warmupResult.dispose();

      console.log('[WasteClassifier] Model & labels berhasil dimuat:', labels);
      return { model, labels };
    }).catch((err) => {
      loadingPromise = null; // reset supaya bisa dicoba ulang kalau gagal
      throw err;
    });

    return loadingPromise;
  }

  /**
   * Preprocessing gambar: HANYA resize ke 224x224, TANPA normalisasi manual.
   *
   * PENTING: normalisasi piksel (rescale ke [-1, 1]) SUDAH ditangani oleh
   * layer Rescaling yang ada DI DALAM model itu sendiri (lihat notebook
   * training). Kalau di sini juga dinormalisasi, hasilnya dinormalisasi
   * DUA KALI -> prediksi jadi salah total meski tidak ada error di console.
   * Kirim piksel mentah 0-255 (float) apa adanya ke model.
   */
  function preprocessImage(imageElement) {
    return tf.tidy(() => {
      const tensor = tf.browser.fromPixels(imageElement)
        .resizeBilinear([IMG_SIZE, IMG_SIZE])
        .toFloat();

      return tensor.expandDims(0); // tambah dimensi batch -> [1, 224, 224, 3]
    });
  }

  /**
   * Jalankan klasifikasi pada satu elemen gambar (HTMLImageElement,
   * HTMLCanvasElement, atau HTMLVideoElement — misal dari <img>, hasil
   * <canvas>, atau frame webcam).
   *
   * @param {HTMLImageElement|HTMLCanvasElement|HTMLVideoElement} imageElement
   * @returns {Promise<{topLabel: string, topConfidence: number, allResults: Array}>}
   */
  async function classify(imageElement) {
    await load(); // pastikan model sudah siap

    const inputTensor = preprocessImage(imageElement);
    const predictionTensor = model.predict(inputTensor);
    const probabilities = await predictionTensor.data();

    inputTensor.dispose();
    predictionTensor.dispose();

    const allResults = Array.from(probabilities).map((confidence, index) => ({
      label: labels[String(index)],
      confidence,
    }));

    allResults.sort((a, b) => b.confidence - a.confidence);

    return {
      topLabel: allResults[0].label,
      topConfidence: allResults[0].confidence,
      allResults,
    };
  }

  return { load, classify };
})();
