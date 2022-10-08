// Fichier : atc-manager.js
// Date : 2021-03-07
// Auteur : Davis Eath
// But : Gérer l'ajout des produits au panier de façon dynamique

(() => {
    let __canAddToCart = true;

    function addToCart(id, callback) {
        $.get(`${ATC_URL}&id=${id}`, data => {
            $('body').toast({
                class: data.success ? 'success' : 'error',
                title: data.message,
                message: data.product,
                showProgress: 'bottom'
            });

            $('.cart-item-count').text(data.itemCount);
            callback(data);
        });
    }

    $('.atc-button').click(function () {
        if (!__canAddToCart) {
            return;
        }

        __canAddToCart = false;

        const button = $(this).find('.hidden.content');
        
        button.html('<i class="circle notch icon spin-animation"></i> Ajouter au panier');

        addToCart($(this).closest('.product-details').data('product-id'), data => {
            button.html((data.success ? '<i class="check icon"></i>' : '<i class="cart plus icon"></i>') + ' Ajouter au panier');
            __canAddToCart = true;
        });
    });

    $('.atc-modal-button').click(function () {
        $(this).html('Ajouter au panier <i class="circle notch icon spin-animation"></i>');
        $(this).prop('disabled', true);

        addToCart($('#product-modal').find('.product-details').data('product-id'), data => {
            $(this).html('Ajouter au panier ' + (data.success ? '<i class="check icon"></i>' : '<i class="cart plus icon"></i>'));
            $(this).prop('disabled', false);
        });
    });
})();