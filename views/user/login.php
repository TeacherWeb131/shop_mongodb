<?php if($error): ?>

    <span> <?= $error ?> </span>

<?php endif ?>

<form action="" method="post">
    <input type="email" name="email" id="" placeholder="Email" required> <br>
    <input type="password" name="password" id="" placeholder="password" required> <br>
    <input type="submit" value="Se Connecter" name="cnx">
</form>