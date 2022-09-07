window.onload = () => {
    const stripe = Stripe('pk_test_51HHSojEazPxq2OFGhzPrh447zuUlnqfJQqF9WhQxH1BXpGvmmicmH6BwNeGGnz1qP4jPEFexydf0rMFtyxHsPW2x0095Et3dpu');
    // Récupère l'élement button
    let cardButton = document.getElementById('card-button');
    // Récupère l'élément qui contiendra le nom du titulaire de la carte
    let cardholderName = document.getElementById('cardholder-name');
    // Récupère l'attribut data-secret du bouton
    const clientSecret = cardButton.dataset.secret;

    const appearance = {
        theme: 'stripe'
    };
    const options = {
        // clientSecret: clientSecret,
        // appearance: appearance
    };

    const elements = stripe.elements(options);
    const cardBuy = document.querySelector(".card");
    // Crée les éléments de carte et les stocke dans la variable card
    let card = elements.create("card");
    card.mount("#card-element");
    card.addEventListener('change', function(event) {
        // On récupère l'élément qui permet d'afficher les erreurs de saisie
        let displayError = document.getElementById('card-errors');

        // Si il y a une erreur
        if (event.error) {
            // On l'affiche
            displayError.textContent = event.error.message;
        } else {
            // Sinon on l'efface
            displayError.textContent = '';
        }
    });
    cardButton.addEventListener('click', () => {
        // On envoie la promesse contenant le code de l'intention, l'objet "card" contenant les informations de carte et le nom du client
        stripe.handleCardPayment(
            clientSecret, card, {
                payment_method_data: {
                    billing_details: { name: cardholderName.value }
                }
            }
        ).then(function(result) {
            // Quand on reçoit une réponse
            if (result.error) {
                // On vérifie si le status est déjà payé ou non
                if (result.error.status === "succeeded") {
                    console.log(result.error.status)
                    const formData = new FormData();
                    formData.append("amount", cardBuy.dataset.amount);
                    formData.append("price", cardBuy.dataset.price);
                    formData.append("name", cardBuy.dataset.name);
                    // Sinon on redirige l'utilisateur
                    fetch(window.location.origin + "/fr/account/buy/confirm/credits", {
                        method: 'POST',
                        body: formData
                    }).then(function(response) {
                        if (response.ok) {
                            return response.json();
                        }
                    }).then(function(data) {
                        console.log(data);
                        if (data.success) {
                            document.location.href = window.location.origin;
                        }
                    })
                } else {
                    // Si on a une erreur, on l'affiche
                    document.getElementById("errors").classList.remove("d-none");
                    document.getElementById("errors").innerText = result.error.message;
                }
            } else {
                if (result.paymentIntent.status) {
                    const formData = new FormData();
                    formData.append("amount", cardBuy.dataset.amount);
                    formData.append("price", cardBuy.dataset.price);
                    formData.append("name", cardBuy.dataset.name);
                    // Sinon on redirige l'utilisateur
                    fetch(window.location.origin + "/fr/account/buy/confirm/credits", {
                        method: 'POST',
                        body: formData
                    }).then(function(response) {
                        if (response.ok) {
                            return response.json();
                        }
                    }).then(function(data) {
                        console.log(data);
                        if (data.success) {
                            document.location.href = window.location.origin;
                        }
                    })
                }

            }
        }).catch(error => {
            console.log(error)
        });
    });
}