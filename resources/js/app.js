import QRCode from 'qrcode';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const sweetAlerts = Swal.mixin({
    buttonsStyling: true,
    customClass: {
        popup: 'aari-swal-popup',
        title: 'aari-swal-title',
        htmlContainer: 'aari-swal-content',
        confirmButton: 'aari-swal-confirm',
        cancelButton: 'aari-swal-cancel',
    },
    confirmButtonColor: '#015624',
    cancelButtonColor: '#496351',
});

const showAlert = (options) => sweetAlerts.fire(options);

window.AARIAlerts = {
    show: showAlert,
    success: (message, title = 'Berhasil') => showAlert({ icon: 'success', title, text: message, confirmButtonText: 'Tutup' }),
    error: (message, title = 'Terjadi kesalahan') => showAlert({ icon: 'error', title, text: message, confirmButtonText: 'Tutup' }),
};

const queuedAlerts = window.__aariAlertsQueue || [];
delete window.__aariAlertsQueue;
queuedAlerts.forEach((alert) => showAlert(alert));

document.addEventListener('aari:notify', (event) => {
    showAlert(event.detail);
});

document.addEventListener('submit', async (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-swal-confirm')) {
        return;
    }

    if (form.dataset.swalConfirmed === 'true') {
        delete form.dataset.swalConfirmed;
        return;
    }

    event.preventDefault();

    if (form.dataset.swalPending === 'true') {
        return;
    }

    form.dataset.swalPending = 'true';
    const submitter = event.submitter;
    const confirmation = await showAlert({
        icon: form.dataset.swalIcon || 'warning',
        title: form.dataset.swalTitle || 'Konfirmasi tindakan',
        text: form.dataset.swalText || 'Apakah Anda yakin ingin melanjutkan?',
        showCancelButton: true,
        confirmButtonText: form.dataset.swalConfirmText || 'Ya, lanjutkan',
        cancelButtonText: form.dataset.swalCancelText || 'Batal',
        confirmButtonColor: form.dataset.swalConfirmColor || '#015624',
        reverseButtons: true,
        focusCancel: true,
    });

    delete form.dataset.swalPending;

    if (confirmation.isConfirmed) {
        form.dataset.swalConfirmed = 'true';

        if (submitter instanceof HTMLElement) {
            form.requestSubmit(submitter);
        } else {
            form.requestSubmit();
        }
    }
}, true);

document.addEventListener('DOMContentLoaded', () => {
    const flashNotices = [...document.querySelectorAll('.flash-message')];
    const fieldErrors = [...document.querySelectorAll('.field-error')];
    const noticeMessages = flashNotices.map((notice) => {
        const message = notice.cloneNode(true);
        message.querySelector('span')?.remove();
        return message.textContent.trim();
    });
    const validationMessages = fieldErrors.map((error) => error.textContent.trim());
    const messages = [...new Set([...noticeMessages, ...validationMessages].filter(Boolean))];
    const isError = fieldErrors.length > 0 || flashNotices.some((notice) => notice.dataset.swalType === 'error');

    flashNotices.forEach((notice) => notice.remove());
    fieldErrors.forEach((error) => error.remove());

    if (messages.length > 0) {
        showAlert({
            icon: isError ? 'error' : 'success',
            title: isError ? 'Periksa kembali' : 'Berhasil',
            text: messages.join('\n'),
            confirmButtonText: 'Tutup',
        });
    }

    const canvas = document.getElementById('qrCanvas');
    const downloadButton = document.getElementById('downloadQrPng');

    if (!canvas) {
        return;
    }

    const qrUrl = canvas.dataset.qrUrl;
    const sizeSelect = document.getElementById('size');
    let qrReady = false;

    if (!qrUrl) {
        return;
    }

    const renderQrCode = (text, size) => {
        if (!canvas) {
            return;
        }

        canvas.width = size;
        canvas.height = size;
        qrReady = false;

        if (downloadButton) {
            downloadButton.disabled = true;
        }

        QRCode.toCanvas(canvas, text, {
            width: size,
            margin: 2,
            color: {
                dark: '#111827',
                light: '#ffffff',
            },
        }, (error) => {
            if (error) {
                console.error(error);
                window.AARIAlerts.error('QR code tidak dapat dibuat. Coba muat ulang halaman.');
            }

            qrReady = !error;

            if (downloadButton) {
                downloadButton.disabled = Boolean(error);
            }
        });
    };

    const updateSize = () => {
        const currentSize = Number(sizeSelect?.value || canvas.dataset.size || 180);
        renderQrCode(qrUrl, currentSize);
    };

    updateSize();

    if (sizeSelect) {
        sizeSelect.addEventListener('change', updateSize);
    }

    if (downloadButton) {
        downloadButton.addEventListener('click', () => {
            if (!qrReady) {
                return;
            }

            const assetCode = downloadButton.dataset.assetCode || 'aset';
            const padding = Math.max(18, Math.round(canvas.width * 0.1));
            const outputCanvas = document.createElement('canvas');
            const context = outputCanvas.getContext('2d');

            outputCanvas.width = canvas.width + (padding * 2);
            outputCanvas.height = canvas.height + (padding * 2) + 64;

            if (!context) {
                return;
            }

            context.fillStyle = '#ffffff';
            context.fillRect(0, 0, outputCanvas.width, outputCanvas.height);
            context.drawImage(canvas, padding, padding);
            context.fillStyle = '#192b38';
            context.textAlign = 'center';
            context.textBaseline = 'middle';
            context.font = '600 13px sans-serif';
            context.fillText('KODE ASET', outputCanvas.width / 2, padding + canvas.height + 22);

            let codeFontSize = Math.min(22, Math.round(canvas.width * 0.13));
            context.font = `700 ${codeFontSize}px monospace`;

            while (context.measureText(assetCode).width > outputCanvas.width - (padding * 2) && codeFontSize > 9) {
                codeFontSize -= 1;
                context.font = `700 ${codeFontSize}px monospace`;
            }

            context.fillText(assetCode, outputCanvas.width / 2, padding + canvas.height + 46);

            const downloadLink = document.createElement('a');
            const safeAssetCode = assetCode.replace(/[<>:"/\\|?*\u0000-\u001F]/g, '-');
            downloadLink.download = `QR-${safeAssetCode}.png`;
            downloadLink.href = outputCanvas.toDataURL('image/png');
            downloadLink.click();
        });
    }
});
