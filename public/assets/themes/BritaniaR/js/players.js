$(document).ready(function () {
    let allPlayers = document.querySelector("#all");
    let onlinePlayers = document.querySelector("#online");
    let content = document.querySelector("#content-page");
    let links = document.querySelectorAll(".page-link");

    loadContentDefault();

    if (allPlayers != null) {
        allPlayers.addEventListener("click", () => {
            // Quand on clique sur Tout les joueurs
            onlinePlayers.classList.remove("active");
            allPlayers.classList.add("active");
            loadContentDefault();
        })
    }

    if (onlinePlayers != null) {
        onlinePlayers.addEventListener("click", () => {
            // Quand on clique sur Tout les joueurs
            onlinePlayers.classList.add("active");
            allPlayers.classList.remove("active");
            loadContentOnline();
        })
    }


    function loadContentDefault(page = 0) {
        content.innerHTML = `<div class="text-center"><div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
      </div></div>`;
        let formData = new FormData();
        formData.append("ajax", "all");
        formData.append("page", page)
        fetch(window.location.href, {
            method: "POST",
            body: formData
        }).then(function (response) {
            return response.json();
        }).then(function (data) {
            if (data.success) {
                content.innerHTML = data.content;
                links = document.querySelectorAll(".page-link");
                console.log(links)
                if (links != null) {
                    links.forEach((link) => {
                        link.addEventListener("click", (e) => {
                            let page = e.target;
                            loadContentDefault(page.innerText);
                        })
                    });
                }
            }

        });
    }

    function loadContentOnline() {
        content.innerHTML = `<div class="text-center"><div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
      </div></div>`;
        let formData = new FormData();
        formData.append("ajax", "online");
        fetch(window.location.href, {
            method: "POST",
            body: formData
        }).then(function (response) {
            return response.json();
        }).then(function (data) {
            console.log(data.success)
            if (data.success) {
                content.innerHTML = data.content;
            }

        });
    }
});