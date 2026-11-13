import ePub from 'epubjs';

/**
 * Render buku EPUB ke dalam container.
 * @param {Object} opts
 * @param {HTMLElement} opts.container
 * @param {string} opts.url
 * @param {number} opts.initialPercent
 * @param {(page:number, percent:number, total:number)=>void} opts.onPageChange
 * @param {HTMLButtonElement|null} opts.prevBtn
 * @param {HTMLButtonElement|null} opts.nextBtn
 */
export function mountEpub({ container, url, initialPercent = 0, onPageChange, prevBtn, nextBtn }) {
    const showError = (msg) => {
        const err = document.getElementById('reader-error');
        const errMsg = document.getElementById('reader-error-msg');
        const loading = document.getElementById('reader-loading');
        if (loading) loading.classList.add('hidden');
        if (errMsg) errMsg.textContent = msg || '';
        if (err) { err.classList.remove('hidden'); err.classList.add('flex'); }
    };

    let book;
    try {
        book = ePub(url);
    } catch (e) {
        showError(e?.message || 'Gagal membuka EPUB.');
        return;
    }

    const rendition = book.renderTo(container, {
        width: '100%',
        height: '100%',
        spread: 'auto',
        flow: 'paginated',
    });

    rendition.themes.register('light', { body: { background: '#ffffff', color: '#1f2937' } });
    rendition.themes.register('dark', { body: { background: '#171412', color: '#e5e1da' } });
    rendition.themes.select('light');

    let currentPercent = initialPercent || 0;

    rendition.on('relocated', (location) => {
        const pct = Math.round((location?.start?.percentage || 0) * 100);
        currentPercent = pct;
        if (onPageChange) onPageChange(Math.round(pct), pct, 0);
    });

    // Tampilkan awal
    rendition.display().then(() => {
        // Buka di posisi progress terakhir (berdasarkan persen)
        if (initialPercent && initialPercent > 0) {
            book.locations.generate(1024).then(() => {
                const loc = book.locations.locationFromPercentage(initialPercent / 100);
                if (loc) rendition.display(loc);
            }).catch(() => {});
        }
    }).catch((e) => showError(e?.message || 'Gagal menampilkan EPUB.'));

    if (prevBtn) prevBtn.addEventListener('click', () => rendition.prev());
    if (nextBtn) nextBtn.addEventListener('click', () => rendition.next());

    // Mode gelap & ukuran font
    document.addEventListener('reader:theme', (e) => {
        rendition.themes.select(e.detail.dark ? 'dark' : 'light');
    });
    document.addEventListener('reader:font', (e) => {
        rendition.themes.fontSize(`${e.detail.size}%`);
    });
}