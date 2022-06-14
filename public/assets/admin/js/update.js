window.onload = () => {
    const verifyUpdateButton = document.querySelector("#verifyUpdate");
    const downloadUpdateButton = document.querySelector("#downloadUpdate");
    const updateDiv = document.querySelector("#updateDiv");

    if (verifyUpdateButton != null) {
        verifyUpdateButton.addEventListener("click", async (e) => {
            e.preventDefault();
            console.log("Click")
            console.log()

            const response = await fetch(window.location.href.replace("/about", "") + '/updateCheck');
            const update = await response.json();
            if (update) {
                const button = document.createElement("button");
                button.setAttribute("class", "btn btn-primary");

                updateDiv.appendChild(button);
            } else {
                const button = document.createElement("button");
                button.setAttribute("class", "btn btn-primary");
                button.innerText = "Download Update";
                updateDiv.appendChild(button);

                button.addEventListener("click", async (e) => {
                    e.preventDefault();
                    console.log("click on download button");
                    const response = await fetch(window.location.href.replace("/about", "") + '/updateDownload');
                    const update = await response.json();

                    if (update !== null) {
                        location.reload();
                    }
                });


            }
        });
    }

    if (downloadUpdateButton != null) {

    }
}

