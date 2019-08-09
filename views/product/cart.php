<div>
    <a href="<?= REQUEST_URL ?>/product">Afficher les produits</a><br>
    <button onclick="deleteCart()">Vider le panier</button>
</div>
<div id="show_cart"></div>
<div id="total"></div>
<div id="valid"></div>


<script src="<?= ASSETS ?>js/cart.js"></script>
<script>
    show_cart()
</script>