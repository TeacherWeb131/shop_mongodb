<div class="product" id="<?= $product->getId() ?>">
    <h2 class="product_name"><?= $product->getName() ?></h2> <br>
    <img src="<?= $product->getPicture_url() ?>" alt="">
    <p class="product_description"><?= $product->getDescription() ?></p><br>
    <span class="product_price"><?= $product->getPrice() ?></span><br>
    <select class="quantity">
        <?php for($i=1; $i<= $product->getQuantity(); $i++): ?>
            <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor ?>
    </select><br>
    
    <button class="add_to_cart">Ajouter au panier</button><br>
</div>

<script src="<?= ASSETS ?>js/cart.js"></script>
<script>
    cart_management();
</script>