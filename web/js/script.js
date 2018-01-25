'use strict';

(
    function () {
        // cibler la croix du cookies disclaimer
        let cookiesDisclaimerButton = $('.close-cookies-disclaimer');

        // tester l'existance du button
        if(cookiesDisclaimerButton) {
            // écouter d'événement
            cookiesDisclaimerButton.on('click', onClickCloseCookiesDisclaimer);
        }


        // clic sur la croix du cookies disclaimer

        function onClickCloseCookiesDisclaimer(e) {
            // requête AJAX

            $.ajax({
                dataType: 'json',
                url: '/fr/ajax/cookies-disclaimer',
                type: 'post',
                data: { 'disclaimerValue' : false },
                success: onSuccessCloseCookiesDisclaimer
            })
        }

        function onSuccessCloseCookiesDisclaimer(response){
            console.log(response);
        }
}
)();