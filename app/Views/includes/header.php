<?php
$navLinkClass = "font-semibold text-gray-800 text-lg"
?>

<header class="grid grid-cols-2 p-3 border-b-2 border-gray-300">
    <a href="/home" class="flex flex-row items-center space-x-2">
        <img class="p-1 object-contain" src="https://raw.githubusercontent.com/marsyadi14/HIS-PPOB-MArsyadI/refs/heads/main/assets/img/logo_sims.png" />
        <h1 class="font-bold text-2xl">SIMS PPOB</h1>
    </a>
    <div class="flex flex-row justify-end space-x-8 my-2 mx-20">
        <a href="/topup" class="<?= $navLinkClass ?>">Top Up</a>
        <a href="/transaksi" class="<?= $navLinkClass ?>">Transaksi</a>
        <a href="/transaksi/history" class="<?= $navLinkClass ?>">History</a>
        <a href="/akun" class="<?= $navLinkClass ?>">Akun</a>
    </div>
</header>