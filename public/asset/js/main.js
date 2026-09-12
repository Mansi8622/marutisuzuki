const updateQuantity = (id, action) => {
    const quantityInput = document.querySelector(`.quantity-input[data-id="${id}"]`);
    let currentQuantity = parseInt(quantityInput.value);

    let newQuantity = currentQuantity;
    if (action === "increase") {
        newQuantity++;
    } else if (action === "decrease" && currentQuantity > 1) {
        newQuantity--;
    } else {
        console.log("Cannot decrease below 1");
        return;
    }

    fetch('/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ id, quantity: newQuantity }),
    })
    .then(response => response.json())
    .then(data => {
        console.log("Server Response:", data); // Debugging Response

        if (data.success) {
            quantityInput.value = data.new_quantity;
        } else {
            console.error("Failed to update quantity:", data.message);
        }
    })
    .catch(error => {
        console.error("AJAX Error:", error);
    });
};
