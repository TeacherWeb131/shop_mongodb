function cart_management() {
    var buttons = document.querySelectorAll(".add_to_cart");

    buttons.forEach(function(button) {
        button.addEventListener('click', function() {

            var div = this.parentNode

            // // récupérer l'id de la classe product
            var id = div.getAttribute('id');
            var children = div.childNodes;
            
            for (var i = 0; i < children.length; i++) {
                if (children[i].className == "product_name") {
                    // // récupérer product name
                    var name = children[i].textContent;
                }
                if (children[i].className == "product_price") {
                    // // récupérer le prix
                    var price = children[i].textContent;
                }
                if (children[i].className == "quantity") {
                    // // récupérer le prix
                    var quantity = children[i].value;
                }
            }

            // // verifier s'il existe notre variable cart dans Cookies

            var content = getCookiesContent('cart');
            if (content == null) {
                setCookie('cart', id + ',' + name + ',' + price + ',' + quantity, 4);

            } else {
                // ancien contenu récupéré => '14,produit 14,5,40|2,produit2,1,10'
                // // convertir en tableau 
                var products = content.split('|');
                var exist = false;
                for (var i = 0; i < products.length; i++) {
                    var simple_product = products[i].split(',');

                    if (simple_product[0] == id) {

                        simple_product[3] = parseInt(simple_product[3]) + parseInt(quantity);
                        products[i] = simple_product;
                        exist = true
                    }
                }
                if (exist) {
                    setCookie("cart", products.join('|'), 4);
                } else {
                    content += '|' + id + ',' + name + ',' + price + ',' + quantity;
                    // nouveau contenu => '14,produit 14,5,40|2,produit2,1,10|6,produit 6,20,1'
                    setCookie("cart", content, 4);
                }
            }
            

        })
    })
}
//cart
function getCookiesContent(name) {
    var cookies_content = document.cookie.split(";");
    for (var i = 0; i < cookies_content.length; i++) {
        var x;
        if (cookies_content[i].charAt(0) == " ") {
            x = cookies_content[i].substring(1, name.length + 1)
        } else {
            x = cookies_content[i].substring(0, name.length)
        }
        if (x == name) {

            return cookies_content[i].replace(name + "=", '');
        }
    }
    return null;
}

function show_cart() {
    var content = getCookiesContent('cart')
    var emplacement = document.querySelector("#show_cart");
    var emplacement_total = document.querySelector("#total");
    if (content == null) {
        emplacement.innerHTML = "<span> Votre panier est vide </span>";
    } else {
        var products = content.split('|');
        var html_content = '';
        var total = 0;
        for (var i = 0; i < products.length; i++) {
            var product = products[i].split(",")
            total += parseFloat(product[2]) * parseFloat(product[3])
            html_content += "<div><h3> Nom du produit: " + product[1] + "</h3><br><span>Prix unitaire" + product[2] +
                "</span><br><span> Quantité: " + product[3] + "</span><br><span> Prix total: " + (parseFloat(product[2]) * parseFloat(product[3])) + "€</span></div>"
        }
        emplacement.innerHTML = html_content;
        emplacement_total.innerHTML = "<h1> Montant Total TTC: " + total + " €</h1>";
        var valid = document.querySelector("#valid");
         valid.innerHTML = "<a href="+window.location.origin+"/index.php/user/validateorder> Valider la commande </a>"
       
    }
}

function deleteCart() {
    
    document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); });
    location.reload();
}

function setCookie(nom, valeur, nbre_jours) {
    var date = new Date()
    date.setTime(date.getTime() + (nbre_jours * 24 * 60 * 60 * 1000))
    document.cookie = nom + "=" + valeur + "; expires=" + date.toGMTString() + "UTC; path=/";
}
