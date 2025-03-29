async function showModal(fetchUrl) {
    const modal = document.getElementById('itemModal');
    const title = document.getElementById('modalTitle');
    const shortContent = document.getElementById('shortModalContent');
    const content = document.getElementById('modalContent');
    const price = document.getElementById('modalPrice');
    const createdBy = document.getElementById('modalCreatedBy');
    const image = document.getElementById('modalImage');
    const purchaseButton = document.querySelector('button[onclick="purchaseItem()"]');

    modal.classList.remove('hidden');
    purchaseButton.classList.add('hidden');
    image.style.display = 'none';
    title.textContent = 'Loading...';
    shortContent.textContent = '';
    content.textContent = '';
    price.textContent = '';
    createdBy.textContent = '';
    image.src = '';
    purchaseButton.dataset.url = fetchUrl;

    try {
        const response = await fetch(fetchUrl);
        if (!response.ok) {
            throw new Error('Failed to fetch modal data');
        }

        const data = await response.json();
        title.textContent = data.name;
        shortContent.textContent = data.shortDescription;
        content.textContent = data.description;
        price.textContent = `${data.price} HUF`;
        createdBy.textContent = data.createdBy || 'Unknown user';
        if (data.image) {
            image.setAttribute('src', '/uploads/images/' + data.image);
        } else {
            image.setAttribute('src', '/images/product/no-image-available.jpg');
        }
        image.style.display = 'block';
        purchaseButton.classList.remove('hidden');
        modal.classList.remove('hidden');
        image.style.display = 'block';
    } catch (error) {
        console.error('Error:', error);
        alert('Load error...');
    }
}

function closeModal() {
    const modal = document.getElementById('itemModal');
    modal.classList.add('hidden');
}

function purchaseItem() {
    const purchaseButton = document.querySelector('button[onclick="purchaseItem()"]');
    const url = purchaseButton.dataset.url;
    alert(`Purchase initiated by URL: ${url}`);
}