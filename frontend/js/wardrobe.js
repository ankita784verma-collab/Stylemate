// Wardrobe management functions
const WardrobeService = {
    async getItems() {
        try {
            const response = await apiRequest('/clothing/list');
            return response.items || [];
        } catch (error) {
            console.error('Error fetching wardrobe:', error);
            return [];
        }
    },

    async addItem(formData) {
        try {
            const response = await fetch(`${API_BASE}/clothing/add`, {
                method: 'POST',
                body: formData,
                credentials: 'include'
            });
            if (!response.ok) throw new Error('Failed to add item');
            return response.json();
        } catch (error) {
            console.error('Error adding item:', error);
            throw error;
        }
    },

    async deleteItem(itemId) {
        try {
            const response = await apiRequest(`/clothing/delete?id=${itemId}`, {
                method: 'DELETE'
            });
            return response;
        } catch (error) {
            console.error('Error deleting item:', error);
            throw error;
        }
    }
};

// Display wardrobe items
async function displayWardrobe() {
    const container = document.getElementById('wardrobeContainer');
    const items = await WardrobeService.getItems();

    if (items.length === 0) {
        container.innerHTML = '<p class="text-center text-muted col-12">No clothing items yet. Add your first item!</p>';
        return;
    }

    container.innerHTML = items.map(item => `
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100">
                <img src="${item.image}" class="card-img-top" alt="${item.name}">
                <div class="card-body">
                    <span class="badge bg-light text-dark mb-2">${item.category_name}</span>
                    <h5 class="card-title fw-bold">${item.name}</h5>
                    <p class="card-text text-muted small">
                        Color: ${item.color || 'N/A'} | Season: ${item.season || 'N/A'}
                    </p>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteItem(${item.id})">Delete</button>
                </div>
            </div>
        </div>
    `).join('');
}

// Delete item
async function deleteItem(itemId) {
    if (!confirm('Are you sure you want to delete this item?')) return;
    
    try {
        await WardrobeService.deleteItem(itemId);
        displayWardrobe();
    } catch (error) {
        alert('Failed to delete item');
    }
}

// Setup wardrobe page
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('wardrobeContainer')) {
        if (!isAuthenticated()) {
            window.location.href = '/frontend/pages/login.html';
            return;
        }

        displayWardrobe();

        // Add clothing button
        const addBtn = document.getElementById('addClothingBtn');
        if (addBtn) {
            addBtn.addEventListener('click', (e) => {
                e.preventDefault();
                new bootstrap.Modal(document.getElementById('addClothingModal')).show();
            });
        }

        // Add clothing form
        const addForm = document.getElementById('addClothingForm');
        if (addForm) {
            addForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(addForm);
                try {
                    await WardrobeService.addItem(formData);
                    bootstrap.Modal.getInstance(document.getElementById('addClothingModal')).hide();
                    addForm.reset();
                    displayWardrobe();
                } catch (error) {
                    alert('Failed to add item');
                }
            });
        }
    }
});
