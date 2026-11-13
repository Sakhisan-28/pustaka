import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Muat reader hanya ketika elemen #reader-root ada di halaman (code-split),
// supaya PDF.js / epub.js tidak ikut ter-load di halaman lain (mis. katalog).
if (document.getElementById('reader-root')) {
    import('./reader')
        .then((module) => module.initReader())
        .catch((e) => console.error('Gagal memuat reader:', e));
}