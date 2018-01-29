'use strict';

(
    () => {
        let selectCurrency = $('span.selectCurrency');


        selectCurrency.on('click', onSelectCurrency);

        function onSelectCurrency (e) {
            let value = $(this).html();
            $.ajax({
                dataType: 'text',
                data: {'selectValue': value},
                type: 'POST',
                url: '/fr/ajax/currency',
                success: onSuccessSelectCurrency
            })
        }

        function onSuccessSelectCurrency(response) {
            console.log(response);
        }
    }

)();