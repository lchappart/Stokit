<div class="container">
    <h1 class="page-title">Mon stock</h1>
    <div class="table-wrap card">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Quantité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<script type="module" src="./Assets/JS/Services/stock.js"></script>
<script type="module">
    import { getProducts, updateProduct } from './Assets/JS/Services/stock.js';
    document.addEventListener('DOMContentLoaded', async () => {
        const products = await getProducts();
        products.products.forEach(product => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${product.name}</td>
                <td class="stored_quantity">${product.stored_quantity}</td>
                <td>
                    <div class="qty-actions">
                        <button class="plus-btn" aria-label="Augmenter">+</button>
                        <button class="minus-btn" aria-label="Diminuer">-</button>
                    </div>
                </td>
            `;
            document.querySelector('tbody').appendChild(row);
            row.querySelector('.plus-btn').addEventListener('click', () => {
                updateProduct(product.barcode, product.stored_quantity + 1);
                product.stored_quantity += 1;
                row.querySelector('.stored_quantity').textContent = product.stored_quantity.toString();
            });
            row.querySelector('.minus-btn').addEventListener('click', () => {
                if (product.stored_quantity > 0) {
                    updateProduct(product.barcode, product.stored_quantity - 1);
                    product.stored_quantity -= 1;
                    row.querySelector('.stored_quantity').textContent = product.stored_quantity.toString();
                }
            });
        });
    });
</script>