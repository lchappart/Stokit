export const createProduct = async (productBarCode, name, quantity, stored_quantity) => {
    const response = await fetch(`index.php?component=home&action=createProduct&productBarCode=${productBarCode}&name=${name}&quantity=${quantity}&stored_quantity=${stored_quantity}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    });
    return response.json();
}


export const updateProduct = async (productBarCode, stored_quantity) => {
    const response = await fetch(`index.php?component=home&action=updateProduct&productBarCode=${productBarCode}&stored_quantity=${stored_quantity}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    });
    return response.json();
}

export const getProduct = async (productBarCode) => {
    const response = await fetch(`index.php?component=home&action=getProduct&productBarCode=${productBarCode}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    });
    return response.json();
}

export const deleteProduct = async (productBarCode) => {
    const response = await fetch(`index.php?component=home&action=deleteProduct&productBarCode=${productBarCode}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    return response.json()
}