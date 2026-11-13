import { mountPdf } from './pdf-viewer';
import { mountEpub } from './epub-viewer';

/**
 * Inisialisasi halaman reader jika elemen #reader-root ada.
 */
export function initReader() {
    const root = document.getElementById('reader-root');
    if (!root) return;

    const fileUrl = root.dataset.fileUrl;
    const fileType = root.dataset.fileType;
    const progressUrl = root.dataset.progressUrl;
    const initialPage = parseInt(root.dataset.initialPage || '0', 10);
    const initialPercent = parseFloat(root.dataset.initialPercent || '0');

    const loading = document.getElementById('reader-loading');
    const pageIndicator = document.getElementById('page-indicator');
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    const themeToggle = document.getElementById('theme-toggle');
    const fontInc = document.getElementById('font-inc');
    const fontDec = document.getElementById('font-dec');

    let saveTimer = null;
    let currentPage = initialPage;
    let percent = initialPercent;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    function scheduleSave() {
        if (!progressUrl) return;
        clearTimeout(saveTimer);
        saveTimer = setTimeout(() => {
            fetch(progressUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    current_page: currentPage,
                    percent_completed: percent,
                }),
            }).catch(() => { /* diamkan */ });
        }, 2000); // debounce 2 detik
    }

    function handlePageChange(page, pct, total) {
        currentPage = page;
        percent = pct;
        if (pageIndicator) {
            pageIndicator.textContent = total ? `${page} / ${total}` : `${pct}%`;
        }
        scheduleSave();
    }

    const common = { onPageChange: handlePageChange, prevBtn, nextBtn };

    try {
        if (fileType === 'pdf') {
            const pdfEl = document.getElementById('pdf-viewer');
            pdfEl?.classList.remove('hidden');
            mountPdf({ container: pdfEl, url: fileUrl, initialPage, ...common });
        } else if (fileType === 'epub') {
            const epubEl = document.getElementById('epub-viewer');
            epubEl?.classList.remove('hidden');
            mountEpub({ container: epubEl, url: fileUrl, initialPercent, ...common });
        }
    } catch (e) {
        const err = document.getElementById('reader-error');
        const errMsg = document.getElementById('reader-error-msg');
        if (errMsg) errMsg.textContent = e?.message || 'Terjadi kesalahan.';
        if (err) { err.classList.remove('hidden'); err.classList.add('flex'); }
    } finally {
        // Sembunyikan loading setelah inisialisasi
        setTimeout(() => loading?.classList.add('hidden'), 300);
    }

    // Toggle mode gelap
    themeToggle?.addEventListener('click', () => {
        const isDark = document.documentElement.classList.toggle('dark');
        themeToggle.textContent = isDark ? '☀️' : '🌙';
        document.dispatchEvent(new CustomEvent('reader:theme', { detail: { dark: isDark } }));
    });

    // Ukuran font (khusus EPUB)
    let fontSize = 100;
    const applyFont = () => document.dispatchEvent(new CustomEvent('reader:font', { detail: { size: fontSize } }));
    fontInc?.addEventListener('click', () => { fontSize = Math.min(200, fontSize + 10); applyFont(); });
    fontDec?.addEventListener('click', () => { fontSize = Math.max(70, fontSize - 10); applyFont(); });

    // Navigasi keyboard
    document.addEventListener('keydown', (e) => {
        if (e.target.matches('input, textarea')) return;
        if (e.key === 'ArrowRight') nextBtn?.click();
        if (e.key === 'ArrowLeft') prevBtn?.click();
    });
}