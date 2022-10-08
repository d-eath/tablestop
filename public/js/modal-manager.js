// Fichier : modal-manager.js
// Date : 2021-02-04
// Auteur : Davis Eath
// But : Gérer le modal pour l'affichage d'un produit

(() => {
    let __canOpenModal = true;

    $('.ui.modal').modal({
        onHide: () => {
            const path = window.location.pathname;
            const query = window.location.search;
            const catalogPath = HOMEPAGE_URL;
    
            // la fermeture du modal n'est pas causé par le bouton retour ; on pousse un état d'historique
            if (path !== catalogPath) {
                window.history.pushState({ modalHtml: null }, null, catalogPath + query);
            }
    
            $('.atc-modal-button').html('Ajouter au panier <i class="cart plus icon"></i>');
        }
    });
    
    $('.product').click(function () {
        if (!__canOpenModal) {
            return false;
        }
    
        __canOpenModal = false;
    
        const path = $(this).attr('href');
        const query = window.location.search;
    
        $('#product-modal').load(path + '?modal-render-from-catalog', () => {
            $('.ui.modal').modal('show');
    
            __canOpenModal = true;
    
            // on pousse un état d'historique et on enregistre le html du modal dedans
            window.history.pushState({ modalHtml: $('#product-modal').html() }, null, path + query);
        });
    
        return false;
    });
    
    // lorsque l'utilisateur utilise le bouton page précédent ou suivant sur le fureteur
    window.onpopstate = event => {
        if (event.state?.modalHtml) {
            $('#product-modal').html(event.state.modalHtml);
            $('.ui.modal').modal('show');
        } else {
            $('.ui.modal').modal('hide');
    
            // correction d'un bug qui rend le modal non-fonctionnel s'il se fait fermer durant l'animation d'ouverture
            setTimeout(() => {
                $('.ui.modal').removeClass('active');
            }, 500);
        }
    };
})();
