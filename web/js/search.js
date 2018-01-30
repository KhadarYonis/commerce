'use strict';

(
    () => {
        let selectCategory = $('[name="select-category"]');

        selectCategory.on('change', onSelectCategory);

        function onSelectCategory (e) {
            let value = $(this).val();
            $.ajax({
                dataType: 'json',
                data: {'selectValue': value},
                type: 'POST',
                url: '/fr/ajax/search',
                success: onSuccessSelectCategory
            })
        }

        function onSuccessSelectCategory(response){
            let searchResluts = $('.search-results');

            searchResluts.empty();

            let products = response.products;

            let html = '';

            console.log(products);
            if(products.length != 0) {

                products.map( function (product) {
                    html += `
                
                    <div class="col-sm-4">
                        <img src="/img/product/${product.image}" class="img-fluid">
                    </div>
                    <div class="col-sm-8">
                        <p class="h4 my-1">${product.translations.fr.name}</p>
                        <p class="my-1">${product.translations.fr.description}</p>
                        <p class="h5 my-2">${ product.price}</p>
                        <p class="my-2 text-success">In stock</p>
                    </div>
                `;
                });


            }
            else {
                html +=`
                    <h3 class="alert alert-warning">IL N'Y PAS de produit à cette catégorie</h3>
                `
            }

            searchResluts.append(html);

            }
    }
)();