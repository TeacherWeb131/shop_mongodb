<table>
    <tr>
        <th>Identifiant</th>
        <th>Date de commande</th>
        <th>Livraison</th>
        <th>Total HT</th>
        <th>Total TTC</th>
    </tr>

    <?php foreach($orders as $order): ?>
        <tr>
            <td><a href="<?= REQUEST_URL ?>/user/order/<?= $order->getId() ?>"><?= $order->getId() ?></a></td>
            <td><?= $order->getCreated_at() ?></td>
            <td><?php if($order->getSubmitted_at() == null) echo "Livraison en cours"; else echo "commande livré à {$order->getCreated_at()}"; ?></td>
            <td><?= $order->getTotal_ht() ?></td>
            <td><?= $order->getTotal_ttc() ?></td>
        </tr>
    <?php endforeach ?>
</table>