<table>
    <tr>
        <th>Identifiant</th>
        <th>Date de commande</th>
        <th>Livraison</th>
        <th>Total HT</th>
        <th>Total TTC</th>
        <th>Action</th>
    </tr>

    <?php foreach($orders as $order): ?>
        <tr>
            <td><a href="<?= REQUEST_URL ?>/admin/order/<?= $order->getId() ?>"><?= $order->getId() ?></a></td>
            <td><?= $order->getCreated_at() ?></td>
            <td><?php if($order->getSubmitted_at() == null) echo "Livraison en cours"; else echo "commande livré à {$order->getCreated_at()}"; ?></td>
            <td><?= $order->getTotal_ht() ?></td>
            <td><?= $order->getTotal_ttc() ?></td>
            <td><?php if($order->getSubmitted_at() == null):?><a href="<?= REQUEST_URL ?>/admin/shipped/<?= $order->getId() ?>">Marquer comme livrée</a><?php endif ?></td>
        </tr>
    <?php endforeach ?>
</table>