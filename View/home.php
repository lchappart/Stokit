<div class="container">
    <h1 class="page-title">Scanner le code‑barres</h1>
    <section class="card stack-md" style="margin-bottom:16px;">
        <h2 style="margin:0; font-size:20px;">Gérez votre garde‑manger en un scan</h2>
        <p class="muted">Stok'it vous aide à suivre vos produits alimentaires grâce au scan du code‑barres. Scannez un produit, confirmez la quantité à ajouter ou retirer, et votre stock se met à jour instantanément.</p>
        <ul class="muted" style="margin:0; padding-left:18px;">
            <li>Scan rapide des codes (EAN/UPC/QR).</li>
            <li>Ajout et retrait de quantités en quelques clics.</li>
            <li>Vue stock pour ajuster vos produits à tout moment.</li>
        </ul>
    </section>
    <div class="grid-responsive">
        <section class="card stack-md">
            <div class="instructions">
                <h3>Instructions</h3>
                <ul>
                    <li>Cliquez sur « Démarrer le scanner » et autorisez l'accès à la caméra.</li>
                    <li>Tenez le code‑barres stable devant la caméra.</li>
                    <li>Le code‑barres sera automatiquement détecté.</li>
                </ul>
            </div>
            <div class="controls">
                <button id="startBtn" class="btn primary">Démarrer le scanner</button>
                <button id="stopBtn" class="btn ghost is-hidden">Arrêter</button>
            </div>
            <div id="status" class="status" role="status" aria-live="polite"></div>
            <div id="reader" aria-label="Visionneuse du scanner"></div>
        </section>
        <aside class="card stack-md">
            <div id="result" class="result-box" aria-live="polite">
                <div class="result-label">✓ Code détecté</div>
                <div id="barcode-id" class="result-value"></div>
            </div>
            <p class="muted"></p>
        </aside>
    </div>

    <dialog id="dialog" aria-labelledby="dialog-title">
        <h2 id="dialog-title">Produit scanné</h2>
        <p id="dialog-content"></p>
        <label for="dialog-input">Quantité</label>
        <input type="number" id="dialog-input" placeholder="Quantité" inputmode="numeric" min="0">
        <div class="controls">
            <button id="addDialog" class="btn success">Ajouter</button>
            <button id="removeDialog" class="btn danger">Retirer</button>
            <button id="closeDialog" class="btn ghost">Réessayer</button>
        </div>
    </dialog>
</div>
<script type="module">
    import { createProduct, updateProduct, deleteProduct, getProduct } from './Assets/JS/Services/home.js';
    let html5QrcodeScanner = null;
    let isScanning = false;
    let isDialogOpen = false;

    const startBtn = document.getElementById('startBtn');
    const stopBtn = document.getElementById('stopBtn');
    const resultBox = document.getElementById('result');
    const barcodeId = document.getElementById('barcode-id');
    const statusDiv = document.getElementById('status');
    const dialog = document.getElementById('dialog');
    function showStatus(message, type) {
        statusDiv.textContent = message;
        statusDiv.className = 'status show ' + type;
    }

    function hideStatus() {
        statusDiv.classList.remove('show');
    }

    async function onScanSuccess(decodedText, decodedResult) {
        if (isDialogOpen) {
            return;
        }
        try {0
            if (html5QrcodeScanner && isScanning) {
                await html5QrcodeScanner.pause(true);
            }
        } catch (e) {
            console.warn('Pause scanner failed:', e);
        }
        isDialogOpen = true;
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
        const resumeScanning = () => {
            isDialogOpen = false;
            resultBox.classList.remove('show');
            try {
                if (html5QrcodeScanner && isScanning) {
                    html5QrcodeScanner.resume();
                }
            } catch (e) {
                console.warn('Resume scanner failed:', e);
            }
        };
        addDialog.addEventListener('click', async () => {
            const quantity = dialogInput.value;
            const productResp = await getProduct(parseInt(data.code));
            const product = productResp?.product ?? [];
            if (product && product.length > 0 && parseInt(quantity) + parseInt(product[0].stored_quantity) > 0) {
                const resp = await updateProduct(parseInt(data.code), parseInt(quantity) + parseInt(product[0].stored_quantity));
                if (resp && resp.success) {
                    showStatus('✅ Produit mis à jour avec succès', 'success');
                } else {
                    showStatus('❌ Échec de la mise à jour du produit', 'error');
                }
            } else {
                const resp = await createProduct(parseInt(data.code), data.product.product_name, data.product.quantity, quantity);
                if (resp && resp.success) {
                    showStatus('✅ Produit ajouté avec succès', 'success');
                } else {
                    showStatus('❌ Échec de l\'ajout du produit', 'error');
                }
            }
            dialog.close();
            resumeScanning();
        }, { once: true });
        removeDialog.addEventListener('click', async () => {
            const quantity = dialogInput.value * (-1);
            const productResp = await getProduct(parseInt(data.code));
            const product = productResp?.product ?? [];
            if (product && product.length > 0 && parseInt(quantity) + parseInt(product[0].stored_quantity) > 0) {
                const resp = await updateProduct(parseInt(data.code), parseInt(quantity) + parseInt(product[0].stored_quantity));
                if (resp && resp.success) {
                    showStatus('✅ Quantité retirée avec succès', 'success');
                } else {
                    showStatus('❌ Échec de la mise à jour de la quantité', 'error');
                }
            }
            dialog.close();
            resumeScanning();
        }, { once: true });
        closeDialog.addEventListener('click', () => {
            resultBox.classList.remove('show');
            dialog.close();
            resumeScanning();
        }, { once: true });
        dialog.showModal();
        // Vibrate if supported
        if (navigator.vibrate) {
            navigator.vibrate(200);
        }
    }

    function onScanError(errorMessage) {
        // Ignore scan errors (they happen constantly while scanning)
    }

    startBtn.addEventListener('click', async function() {
        try {
            startBtn.classList.add('is-hidden');
            stopBtn.classList.remove('is-hidden');
            showStatus('Starting camera...', 'info');
            resultBox.classList.remove('show');

            html5QrcodeScanner = new Html5Qrcode("reader");

            const config = {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                formatsToSupport: [
                    Html5QrcodeSupportedFormats.QR_CODE,
                    Html5QrcodeSupportedFormats.UPC_A,
                    Html5QrcodeSupportedFormats.UPC_E,
                    Html5QrcodeSupportedFormats.UPC_EAN_EXTENSION,
                    Html5QrcodeSupportedFormats.EAN_13,
                    Html5QrcodeSupportedFormats.EAN_8,
                    Html5QrcodeSupportedFormats.CODE_128,
                    Html5QrcodeSupportedFormats.CODE_39,
                    Html5QrcodeSupportedFormats.CODE_93,
                    Html5QrcodeSupportedFormats.CODABAR,
                    Html5QrcodeSupportedFormats.ITF
                ]
            };

            await html5QrcodeScanner.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                onScanError
            );

            isScanning = true;
            showStatus('📸 Caméra active - Visez un code‑barres', 'info');

        } catch (err) {
            console.error('Error starting scanner:', err);
            showStatus('Erreur: impossible de démarrer la caméra. Vérifiez l’autorisation.', 'error');
            startBtn.classList.remove('is-hidden');
            stopBtn.classList.add('is-hidden');
        }
    });

    stopBtn.addEventListener('click', async function() {
        if (isScanning && html5QrcodeScanner) {
            try {
                await html5QrcodeScanner.stop();
                html5QrcodeScanner.clear();
            } catch (err) {
                console.error('Error stopping scanner:', err);
            }
        }

        isScanning = false;
        startBtn.classList.remove('is-hidden');
        stopBtn.classList.add('is-hidden');
        hideStatus();
    });
</script>