<?php if ($error) : ?>
    <span> <?= $error ?> </span>
<?php endif ?>

<form action="" method="post">
    <input type="text" name="first_name" id="" placeholder="first Name"><br>
    <input type="text" name="last_name" id="" placeholder="Last Name"><br>
    <input type="email" name="email" id="" placeholder="Email"> <br>
    <input type="password" name="password" id="" placeholder="password"><br>
    <input type="submit" name="valider" value="Valider">
</form>