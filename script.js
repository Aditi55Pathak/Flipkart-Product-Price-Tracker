document.getElementById('fetch-details').addEventListener('click', async function() {
    const productUrl = document.getElementById('product-link').value;
    const productInfoDiv = document.getElementById('product-info');
    
    if (!productUrl) {
        alert('Please enter a Flipkart product URL');
        return;
    }

    productInfoDiv.innerHTML = '<p>Loading product details...</p>';

    try {
      
        const response = await fetch(`fetch_product.php?url=${encodeURIComponent(productUrl)}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error: ${response.status}`);
        }

        const product = await response.json();

        if (product.error) {
            throw new Error(product.error);
        }

        displayProductInfo(product);
        
    } catch (error) {
        console.error('Error:', error);
        productInfoDiv.innerHTML = `
            <div class="error-message">
                <p>Error fetching product details:</p>
                <p>${error.message}</p>
            </div>
        `;
    }
});

// Function to display product details
function displayProductInfo(data) {
    const productInfoDiv = document.getElementById('product-info');
    productInfoDiv.innerHTML = `
        <h2>${escapeHtml(data.title)}</h2>
        <p><strong>Price:</strong> ₹${escapeHtml(data.price)}</p>
        <p><strong>Rating:</strong> ${escapeHtml(data.rating)}</p>
        <p><strong>Description:</strong> ${escapeHtml(data.description)}</p>
        <p><strong>Reviews:</strong> ${escapeHtml(data.reviews)}</p>
        <p><strong>Total Purchases:</strong> ${escapeHtml(data.total_purchases)}</p>
    `;
}

// Helper function to escape HTML and prevent XSS
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
