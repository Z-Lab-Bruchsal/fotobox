import QRCode from 'qrcode';

window.generateQR = (url, canvas) => {
    QRCode.toCanvas(canvas, url, { width: 180, margin: 1 });
};
