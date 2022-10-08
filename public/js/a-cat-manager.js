// Fichier : a-cat-manager.js
// Date : 2021-05-16
// Auteur : Davis Eath
// But : Gérer la modification des catégories de façon dynamique (admin)

(() => {
    const EDIT_HTML = `
    <div class="ui fluid action input">
        <input type="text" maxlength="50">
        <button class="ui positive icon button confirm-button">
            <i class="check icon"></i>
        </button>
        <button class="ui negative icon button cancel-button">
            <i class="times icon"></i>
        </button>
    </div>`;

    function post(fields) {
        const form = $(`<form method="POST"></form>`);

        for (const [key, value] of Object.entries(fields)) {
            const attributes = {
                type: 'hidden',
                name: key,
                value: value
            };

            $('<input>').attr(attributes).appendTo(form);
        }

        $('body').append(form);
        form.submit();
    }

    $('.edit-button').click(function () {
        $('.edit-button').prop('disabled', true);
        $('.delete-button').prop('disabled', true);

        const row = $(this).closest('tr');
        const name = row.children().eq(1);

        name.html(EDIT_HTML);

        const input = name.find('input');

        input.val(row.data('category-name'));
        input.focus();

        $('input').keydown(function (e) {
            switch (e.key) {
                case 'Enter':
                    $('.confirm-button').click();
                    break;

                case 'Escape':
                    $('.cancel-button').click();
                    break;
            }
        });

        $('.cancel-button').click(function () {
            $('.edit-button').prop('disabled', false);
            $('.delete-button').prop('disabled', false);

            name.text(row.data('category-name'));
        });

        $('.confirm-button').click(function () {
            if (input.val().length === 0) {
                input.parent().addClass('error');

                return;
            }

            $('.confirm-button').prop('disabled', true);
            $('.cancel-button').prop('disabled', true);

            post({
                action: 'edit',
                id: row.data('category-id'),
                name: input.val()
            });
        });
    });

    $('.delete-button').click(function() {
        const row = $(this).closest('tr');
        const id = row.data('category-id');
        const name = row.data('category-name');

        $('.category-prompt').text(name);
        $('.ui.basic.modal').data('category-id', id);
        $('.ui.basic.modal').modal('show');
    });

    $('.confirm-delete-button').click(function() {
        post({
            action: 'delete',
            id: $('.ui.basic.modal').data('category-id')
        });
    })
})();