<div>
    <h1>Commande</h1><br>
    Identifiant : <p><?=  $order[0][0] ?></p><br>
    Commandé le : <p><?= $order[0]['created_at'] ?></p><br>
    Total HT : <p><?= $order[0]['total_ht'] ?></p><br>
    Total TTC : <p><?= $order[0]['total_ttc'] ?></p><br>
</div>

<div>
    <table>
        <tr>
            <th>Quantité Commandé</th>
            <th>prix unitaire</th>
            <th>Total</th>
            <th>Produit commandé</th>
        </tr>

        <?php foreach($order as $o): ?>
            <tr>
                <td><?= $o['quantity_ordered'] ?></td>
                <td><?= $o['price_each'] ?></td>
                <td><?= $o['total_price'] ?></td>
                <td><a href="<?= REQUEST_URL ?>/product/show/<?= $o['product_id'] ?>">Afficher le produit</a></td>
            </tr>
        <?php endforeach ?>
    </table>

</div>