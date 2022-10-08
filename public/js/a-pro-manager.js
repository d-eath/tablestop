// Fichier : a-pro-manager.js
// Date : 2021-05-16
// Auteur : Davis Eath
// But : Appliquer des masques de champs pour les formulaires de gestion de produits (admin)

(() => {
    const MASK_OPTIONS = {
        min: 0,
        max: 10000,
        allowMinus: false,
        groupSeparator: ' ',
        rightAlign: true,
        numericInput: true,
        removeMaskOnSubmit: true
    };

    $('#product_price').inputmask('decimal', MASK_OPTIONS);
    $('#product_inventoryStock').inputmask('integer', MASK_OPTIONS);
    $('#product_minRestock').inputmask('integer', MASK_OPTIONS);
})();