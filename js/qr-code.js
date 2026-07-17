// QR Code Generation using QRCode.js
function generateQRCode(receiverId) {
    const qrCodeContainer = document.createElement('div');
    qrCodeContainer.id = 'qr-code-' + receiverId;
    qrCodeContainer.style.margin = '10px auto';
    qrCodeContainer.style.textAlign = 'center';
    
    // Generate QR Code
    new QRCode(qrCodeContainer, {
        text: receiverId,
        width: 128,
        height: 128,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });

    // Show QR Code in modal
    const modalContent = `
        <div class="modal fade" id="qrModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">QR Code for ${receiverId}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        ${qrCodeContainer.outerHTML}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="downloadQRCode('${receiverId}')">Download</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalContent);
    new bootstrap.Modal(document.getElementById('qrModal')).show();
}

function downloadQRCode(receiverId) {
    const canvas = document.querySelector('#qr-code-' + receiverId + ' canvas');
    const link = document.createElement('a');
    link.href = canvas.toDataURL();
    link.download = `qr-code-${receiverId}.png`;
    link.click();
}

// Include QRCode.js library
const qrScript = document.createElement('script');
qrScript.src = 'https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js';
document.head.appendChild(qrScript);
