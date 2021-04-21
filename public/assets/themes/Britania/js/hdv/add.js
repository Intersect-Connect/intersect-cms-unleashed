document.addEventListener("DOMContentLoaded", function () {
    let $choose_character = document.querySelector('#choose_character');
    let $choose_items = document.querySelector('#choose_items');
    let $choose_item_group = document.querySelector('#choose_item_group');
    let $choose_quantity_group = document.querySelector('#choose_quantity_group');
    let $choose_price_group = document.querySelector('#choose_price_group');
    let $button_submit = document.querySelector('#button_submit');

    if ($choose_character != null) {
        $choose_character.addEventListener('change', (e) => {
            let $value = e.target.value;
            setTimeout(() => {
                if ($value != null && $value != "choose") {
                    $choose_item_group.classList.remove('d-none');
                    $choose_quantity_group.classList.remove('d-none');
                    $choose_price_group.classList.remove('d-none');
                    $button_submit.classList.remove('d-none');
                    getInventory($value);
                } else {
                    $choose_item_group.classList.add('d-none');
                    $choose_quantity_group.classList.add('d-none');
                    $choose_price_group.classList.add('d-none');
                    $button_submit.classList.add('d-none');
                }
            }, 500);

        });
    }

    function getInventory($id) {

        let $formData = new FormData();
        $formData.append('ajax', 'getInventory');
        $formData.append('character_id', $id);
        $choose_items.innerHTML = "";
        fetch(window.location.href, {
            method: 'POST',
            body: $formData
        }).then(function (response) {
            return response.json();
        }).then(function ($data) {
            if ($data.success) {

                Object.entries($data.data).forEach(($item) => {
                    console.log($item[1])

                    $choose_items.options[$choose_items.options.length] = new Option($item[1].name + ' x' + $item[1].quantity, $item[1].id + '/' + $item[1].quantity);
                });
            }

        });
    }
});