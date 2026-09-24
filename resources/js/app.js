import QRCode from 'qrcode';

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('qrCanvas');

    if (!canvas) {
        return;
    }

    const qrUrl = canvas.dataset.qrUrl;
    const sizeSelect = document.getElementById('size');

    if (!qrUrl) {
        return;
    }

    const renderQrCode = (text, size) => {
        if (!canvas) {
            return;
        }

        canvas.width = size;
        canvas.height = size;

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
});
