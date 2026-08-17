// Outfit generation functions
const OutfitService = {
    async generateOutfit(occasion, season, style) {
        try {
            const response = await apiRequest('/outfit/generate', {
                method: 'POST',
                body: JSON.stringify({ occasion, season, style })
            });
            return response.outfit;
        } catch (error) {
            console.error('Error generating outfit:', error);
            throw error;
        }
    }
};

// Display generated outfit
function displayOutfit(outfit) {
    const resultDiv = document.getElementById('outfitResult');
    const itemsDiv = document.getElementById('outfitItems');
    const noOutfitDiv = document.getElementById('noOutfit');

    if (!outfit || !outfit.items || outfit.items.length === 0) {
        resultDiv.classList.add('d-none');
        noOutfitDiv.classList.remove('d-none');
        return;
    }

    itemsDiv.innerHTML = outfit.items.map((item, index) => `
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card">
                <img src="${item.image}" class="card-img-top" alt="${item.name}">
                <div class="card-body">
                    <h6 class="card-title">${item.name}</h6>
                    <small class="text-muted">${item.category_name || 'Item'}</small>
                </div>
            </div>
        </div>
    `).join('');

    noOutfitDiv.classList.add('d-none');
    resultDiv.classList.remove('d-none');
}

// Setup outfit page
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('outfitForm')) {
        if (!isAuthenticated()) {
            window.location.href = '/frontend/pages/login.html';
            return;
        }

        const form = document.getElementById('outfitForm');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const occasion = form.querySelector('select[name="occasion"]').value;
            const season = form.querySelector('select[name="season"]').value;
            const style = form.querySelector('select[name="style"]').value;

            try {
                const outfit = await OutfitService.generateOutfit(occasion, season, style);
                displayOutfit(outfit);
            } catch (error) {
                alert('Failed to generate outfit');
            }
        });
    }
});
