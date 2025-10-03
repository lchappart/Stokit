export const getProducts = async () => {
    const response = await fetch('index.php?component=stock&action=getProducts',
        {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        }
    );
    return response.json();
}

export const updateProduct = async (productBarCode, stored_quantity) => {
    const response = await fetch(`index.php?component=stock&action=updateProduct&productBarCode=${productBarCode}&stored_quantity=${stored_quantity}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    });
    return response.json();
}