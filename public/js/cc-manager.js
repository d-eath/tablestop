// Fichier : cc-manager.js
// Date : 2021-04-23
// Auteur : Davis Eath
// But : Gérer la validation de carte de crédit

(() => {
    function applyFormError(field, message) {
        $(field).removeClass('error');
        $(field + ' > .form-error').remove();

        if (!message) {
            return;
        }

        $(field).addClass('error');
        $(field).append($('<span class="form-error"></span>').text(message));
    }

    function validateCardNumber(number, type) {
        const typesRegex = {
            visa: /^4\d{12}(?:\d{3})?$/,
            mastercard: /^5[1-5]\d{14}$/,
            american_express: /^3[47]\d{13}$/
        };

        if (!typesRegex[type].test(number)) {
            return false;
        }

        // début du test de la formule de Luhn (mod 10)
        const digits = { odd: [], even: [] };
        let isOddIndex = number.length % 2 == 0;        // si la longeur du nombre est paire, premier indice = impair

        number.split('').forEach(d => {
            digits[isOddIndex ? 'odd' : 'even'].push(parseInt(d));
            isOddIndex = !isOddIndex;                   // inversement indice pair/impair pour prochain chiffre
        });

        const evenSum = digits.even
            .reduce((a, c) => a + c);                   // addition des chiffres

        const oddSum = digits.odd
            .map(d => d * 2)                            // chiffres * 2
            .map(d => d > 9 ? ~~(d / 10) + d % 10 : d)  // addition des chiffres des nombres
            .reduce((a, c) => a + c);                   // addition des chiffres

        return (evenSum + oddSum) % 10 === 0;
    }

    function validateExpiration(date) {
        // date d'expiration incomplète
        if (date.length === 0 || date.includes('_')) {
            return false;
        }

        const split = date.split('/');
        const month = parseInt(split[0]);
        const year = parseInt(split[1]);

        if (month == 0 || month > 12) {
            return false;
        }

        const currentYear = new Date().getFullYear();
        const validYears = {};

        // obtient l'année courante + 4 prochaines années en format YY (2 chiffres)
        // ex.: année 2998 = 98, 2999 = 99, 3000 = 00, 3001 = 01, 3002 = 02
        for (let i = 0; i < 5; i++) {
            validYears[(currentYear % 100 + i) % 100] = currentYear + i;
        }

        if (!validYears.hasOwnProperty(year)) {
            return false;
        }

        // mois + 1 et jour = 0 : dernière date du mois
        // Date() : mois #0 = janvier, mois #1 = février, ...
        // (le mois donné [format humain] par l'utilisateur donne déjà mois + 1 [pour Date()])
        // heure finale : 23:59:59.999
        const lastDate = new Date(validYears[year], month, 0, 23, 59, 59, 999);

        return lastDate >= Date.now();
    }

    $('#expiration input').inputmask('99/99');

    $('#cardType input').change(() => {
        // réinitialisation d'erreur pour le type de carte
        applyFormError('#cardType');

        // activation des champs après sélection de type de carte
        $('#cardNumber input').prop('disabled', false);
        $('#expiration input').prop('disabled', false);
        $('#verification input').prop('disabled', false);

        // vidage des champs sur changement de type de carte
        $('#cardNumber input').val('');
        $('#expiration input').val('');
        $('#verification input').val('');

        const type = $('#cardType input').val();

        // application des masques (+ label pour code de vérification)
        switch (type) {
            case 'visa':
                $('#cardNumber input').inputmask('9999 9999 9999 9999', { autoUnmask: true });
                $('#verification input').inputmask('999');
                $('#verification label').text('CVV');
                break;

            case 'mastercard':
                $('#cardNumber input').inputmask('9999 9999 9999 9999', { autoUnmask: true });
                $('#verification input').inputmask('999');
                $('#verification label').text('CVC');
                break;

            case 'american_express':
                $('#cardNumber input').inputmask('9999 999999 99999', { autoUnmask: true });
                $('#verification input').inputmask('9999');
                $('#verification label').text('CVV');
                break;
        }
    });

    /**
     * Changement de masque pour un carte Visa de 13 chiffres
     */
    $('#cardNumber input').focus(() => {
        if ($('#cardType input').val() === 'visa') {
            $('#cardNumber input').inputmask('9999 9999 9999 9999', { autoUnmask: true });
        }
    });

    $('#cardNumber input').blur(() => {
        const type = $('#cardType input').val();
        const number = $('#cardNumber input').val();

        if (type === 'visa' && number.length === 13) {
            $('#cardNumber input').inputmask('9999 999 999 999', { autoUnmask: true });
        } else if (type === 'visa') {
            $('#cardNumber input').inputmask('9999 9999 9999 9999', { autoUnmask: true });
        }
    });

    /**
     * Réinitialisation des erreurs après un changement d'entrée
     */
    $('#cardNumber input').change(() => {
        applyFormError('#cardNumber');
    });

    $('#expiration input').change(() => {
        applyFormError('#expiration');
    });

    $('#verification input').change(() => {
        applyFormError('#verification');
    });

    /**
     * Événement sur clic du bouton Payer
     */
    $('#submit').click(() => {
        const form = $('form').serializeArray();

        if (form.length < 4) {
            applyFormError('#cardType', 'Veuillez choisir le type de carte.');
            return;
        }

        const type = form[0].value;
        const number = form[1].value;
        const expiration = form[2].value;
        const code = form[3].value;

        let success = true;

        if (!validateCardNumber(number, type)) {
            applyFormError('#cardNumber', 'Numéro de carte invalide.');
            success = false;
        }

        if (!validateExpiration(expiration)) {
            applyFormError('#expiration', 'Date d\'expiration invalide ou dépassée.');
            success = false;
        }

        if (code.length === 0 || code.includes('_')) {
            applyFormError('#verification', `${$('#verification label').text()} invalide.`);
            success = false;
        }

        if (!success) {
            return;
        }

        $.post(window.location, {}, data => {
            if (data === 'ok') {
                window.location.href = SUCCESS_URL;
            }
        });
    });
})();