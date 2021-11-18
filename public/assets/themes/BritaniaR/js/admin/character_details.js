window.onload = () => {
    let inventory_items = document.querySelectorAll('.inventory');

    inventory_items.forEach(element => {
        element.addEventListener('click', (event) => {
            let modal = new bootstrap.Modal(document.getElementById('inventory-' + event.target.dataset.id), {
                keyboard: false
            });
            modal.show();
        })
    });
}