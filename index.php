<?php
/** @var object $user */
include "partials/session.php";

if (LOGGED_IN) {
?>

    <a href="logout.php">logout</a>

    <h1>
        welcome, <?= $user->username ?><span style="color: grey;">#<?= $user->discriminator ?></span>

        <ul>
            <li>ID: <?= $user->id ?></li>
            <li>Banner Hash: <?= $user->banner ?></li>
            <li>Avatar Hash: <?= $user->avatar ?></li>
        </ul>

        <img alt="banner" src="https://cdn.discordapp.com/banners/<?= $user->id ?>/<?= $user->banner ?>?size=512" >
        <img alt="avatar" src="https://cdn.discordapp.com/avatars/<?= $user->id ?>/<?= $user->avatar ?>?size=512" >
    </h1>

<?php } else { ?>
    <a href="login.php">Login</a>
<?php } ?>
