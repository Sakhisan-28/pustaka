import * as pdfjsLib from 'pdfjs-dist';
import workerUrl from 'pdfjs-dist/build/pdf.worker.min.mjs?url';

pdfjsLib.GlobalWorkerOptions.workerSrc = workerUrl;

/**
 * Render buku PDF ke dalam container.
 * @param {Object} opts
 * @param {HTMLElement} opts.container
 * @param {string} opts.url
 * @param {number} opts.initialPage
 * @param {(page:number, percent:number, total:number)=>void} opts.onPageChange
 * @param {HTMLButtonElement|null} opts.prevBtn
 * @param {HTMLButtonElement|null} opts.nextBtn
 */
export function mountPdf({ container, url, initialPage = 1, onPageChange, prevBtn, nextBtn }) {
    let pdfDoc = null;
    let pageNum = Math.max(1, initialPage || 1);
    let rendering = false;
    let pending = null;
    const scale = 1.3;

    const showError = (msg) => {
        const err = document.getElementById('reader-error');
        const errMsg = document.getElementById('reader-error-msg');
        const loading = document.getElementById('reader-loading');
        if (loading) loading.classList.add('hidden');
        if (errMsg) errMsg.textContent = msg || '';
        if (err) { err.classList.remove('hidden'); err.classList.add('flex'); }
    };

    function drawPage(num) {
        if (rendering) { pending = num; return; }
        rendering = true;

        pdfDoc.getPage(num).then((page) => {
            const viewport = page.getViewport({ scale });
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            canvas.className = 'pdf-page-canvas mx-auto bg-white shadow-sm mb-4';
            container.innerHTML = '';
            container.appendChild(canvas);

            return page.render({ canvasContext: ctx, viewport }).promise.then(() => {
                rendering = false;
                const total = pdfDoc.numPages;
                if (onPageChange) onPageChange(num, Math.round((num / total) * 100), total);
                if (pending !== null) { const p = pending; pending = null; drawPage(p); }
            });
        }).catch((e) => {
            rendering = false;
            showError(e?.message || 'Gagal merender halaman.');
        });
    }

    pdfjsLib.getDocument(url).promise.then((doc) => {
        pdfDoc = doc;
        const start = Math.min(Math.max(1, pageNum || 1), doc.numPages);
        drawPage(start);
    }).catch((e) => showError(e?.message || 'Gagal memuat PDF.'));

    const goNext = () => { if (pdfDoc && pageNum < pdfDoc.numPages) { pageNum += 1; drawPage(pageNum); } };
    const goPrev = () => { if (pdfDoc && pageNum > 1) { pageNum -= 1; drawPage(pageNum); } };

    if (prevBtn) prevBtn.addEventListener('click', goPrev);
    if (nextBtn) nextBtn.addEventListener('click', goNext);

    // Mode gelap: invert canvas
    document.addEventListener('reader:theme', (e) => {
        container.style.filter = e.detail.dark ? 'invert(1) hue-rotate(180deg)' : '';
    });
}