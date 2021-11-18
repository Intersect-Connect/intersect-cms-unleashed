    $(document).ready(function () {
        let table = $('.table').bootstrapTable();

        table.on('page-change.bs.table', () => {
            window.scrollTo(0, 0)
        })

        let searchPlaceholder = null;

        if (local == 'fr') {
            searchPlaceholder = "Rechercher..."
        } else {
            if (local == 'en') {
                searchPlaceholder = "Search..."
            } else {
                if (local == 'en') {
                    searchPlaceholder = "Search..."
                }
            }
        }

        // document.querySelector('.search-input').classList.remove('form-control');
        document.querySelector('.search-input').classList.add('m-0');
        document.querySelector('.search-input').setAttribute('placeholder', searchPlaceholder)
    });