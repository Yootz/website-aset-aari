import QRCode from 'qrcode';

document.addEventListener('DOMContentLoaded', () => {
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
