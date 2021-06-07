document.addEventListener("DOMContentLoaded", function () {
    let selectCharacter = document.querySelector('#playerShop');
    let submitBuy = document.querySelector('#submitBuy');
    let notification = document.querySelector('#notification');
    let choose_quantity = document.querySelector('#choose_quantity');

    if (selectCharacter != null) {
        selectCharacter.addEventListener('change', (e) => {
            let value = e.target.value;
            let options = Array.from(e.target.options)
            let inventory_full = options.find(element => element.value == value);

            if (value != "choose" && inventory_full.dataset.inventory == "1") {
                submitBuy.disabled = true;
                submitBuy.classList.add('disabled');
                notification.classList.remove('d-none')
                choose_quantity.classList.add('d-none');
            } else {
                submitBuy.disabled = false;
                submitBuy.classList.remove('disabled');
                notification.classList.add('d-none')
                choose_quantity.classList.remove('d-none');
            }

            if (value == "choose") {
                submitBuy.disabled = false;
                submitBuy.classList.remove('disabled');
                notification.classList.add('d-none')
                choose_quantity.classList.remove('d-none');
            }
        })
    }
});