<h1> Liste des produits interface admin </h1>

<div>

    <?php foreach($products as $product): ?>

        <?php if($product->getQuantity()>0): ?>
            <div class="product" id="<?= $product->getId() ?>">
                <a class="product_name" href="<?= REQUEST_URL ?>/product/show/<?= $product->getId() ?>"><h2><?= $product->getName() ?></h2></a> <br>
                <img src="<?= $product->getPicture_url() ?>" alt="">
                <p class="product_description"><?= $product->getDescription() ?></p><br>
                <span class="product_price"><?= $product->getPrice() ?></span><br>
                <span>Quantité disponible <?= $product->getQuantity() ?></span><br>
            </div>
        <?php endif ?>

    <?php endforeach ?>
</div>