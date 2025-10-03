export const showStatus = (message, type) => {
    statusDiv.textContent = message;
    statusDiv.className = 'status show ' + type;
}

export const hideStatus = () => {
    statusDiv.classList.remove('show');
}

export const onScanSuccess = async (decodedText, decodedResult) => {
    barcodeId.textContent = decodedText;
    resultBox.classList.add('show');
    showStatus('✓ Scan successful!', 'success');
    const response = await fetch(`https://world.openfoodfacts.org/api/v0/product/${decodedText}.json`);
    const data = await response.json();
    console.log(data.product.product_name);
    const dialogContent = document.getElementById('dialog-content');
    const dialogInput = document.getElementById('dialog-input');
    const addDialog = document.getElementById('addDialog');
    const removeDialog = document.getElementById('removeDialog');
    const closeDialog = document.getElementById('closeDialog');
    dialogContent.textContent = data.product.product_name;
    addDialog.addEventListener('click', () => {
        const quantity = dialogInput.value;
        console.log(quantity);
    });
    removeDialog.addEventListener('click', () => {
        const quantity = dialogInput.value;
        console.log(quantity);
    });
    closeDialog.addEventListener('click', () => {
        resultBox.classList.remove('show');
        dialog.close();
    });
    dialog.showModal();
    closeDialog.addEventListener('click', () => {
        dialog.close();
    });
    // Vibrate if supported
    if (navigator.vibrate) {
        navigator.vibrate(200);
    }
}

export const onScanError = (errorMessage) => {
    // Ignore scan errors (they happen constantly while scanning)
}


export const stopBtn = async () => {
    if (isScanning && html5QrcodeScanner) {
        try {
            await html5QrcodeScanner.stop();
            html5QrcodeScanner.clear();
        } catch (err) {
            console.error('Error stopping scanner:', err);
        }
    }

    isScanning = false;
    startBtn.style.display = 'block';
    stopBtn.style.display = 'none';
    hideStatus();
};