<?= $this->extend('layouts/default') ?>

<?php
$inputClass = "p-2 flex flex-row items-center w-full border border-gray-400 rounded-sm focus-within:outline-1 focus-within:outline-red-300";
$inputOutlineClass = "w-full focus:outline-none";

$topupBtnClass = "py-2 px-4 border border-gray-400 rounded-md hover:bg-gray-200";

$errorClass = "text-red-600";
$successClass = "text-green-600";
?>

<?= $this->section('content') ?>

<script type="text/javascript">
  const setProfileInfo = (fname, lname, imgLink) => {
    const namaPenggunaEl = document.getElementById("general_nama_pengguna");
    const ikonPenggunaEl = document.getElementById("general_ikon_pengguna");

    namaPenggunaEl.innerHTML = `${fname} ${lname}`;

    if (imgLink !== "https://minio.nutech-integrasi.com/take-home-test/null") {
      ikonPenggunaEl.src = imgLink;
    }
  };

  const numberDotSeparator = (x) =>
    x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

  const setBalanceInfo = (balance) => {
    const balanceEl = document.getElementById("saldoId");

    balanceEl.innerHTML = numberDotSeparator(balance);
  };

  const getFetch = async (url, token) => {
    return await fetch(url, {
      method: "GET",
      headers: {
        "Content-type": "application/json",
        Authorization: `Bearer ${token}`,
      },
    }).then((resp) => resp.json());
  };

  const getProfileInfo = async () => {
    const profileUrl =
      "https://take-home-test-api.nutech-integrasi.com/profile";
    const balanceUrl =
      "https://take-home-test-api.nutech-integrasi.com/balance";

    const bannerUrl = "https://take-home-test-api.nutech-integrasi.com/banner";

    try {
      const token = sessionStorage.getItem("token");

      getFetch(profileUrl, token).then((response) => {
        const respData = response.data;
        setProfileInfo(
          respData.first_name,
          respData.last_name,
          respData.profile_image
        );
      });

      getFetch(balanceUrl, token).then((response) => {
        const balance = response.data.balance;
        setBalanceInfo(balance);
      });
    } catch (error) {
      console.error(error.message);
    }
  };

  const postTopupBalance = async () => {
    const balance = parseInt(document.getElementById("topup_pengguna").value)
    const topupUrl =
      "https://take-home-test-api.nutech-integrasi.com/topup";
    try {
      const token = sessionStorage.getItem("token");

      const response = await fetch(topupUrl, {
        method: "POST",
        body: JSON.stringify({
          top_up_amount: balance,
        }),
        headers: {
          "Content-type": "application/json",
          Authorization: `Bearer ${token}`
        },
      }).then((resp) => resp.json());

      if (response.status !== 0) {
        throw new Error(response.message);
      }

      tampilkanKonfirmasiTopup(balance, true)

    } catch (error) {
      tampilkanKonfirmasiTopup(balance, false, error.message)
    }
  };

  window.onload = () => {
    if (!sessionStorage.getItem("token")) {
      window.location.assign("/");
    } else {
      getProfileInfo();

      document.getElementById("bgTopup").addEventListener("click", function(event) {
        let element = document.getElementById("konfirmasiTopup"); // Replace with your element's ID

        if (!element.contains(event.target)) {
          batalkanTopUp()
        }
      });
    }
  };

  function onInputTopup(balance) {
    const topupEl = document.getElementById("topup_pengguna");
    topupEl.value = parseInt(balance);

    topupChangeValue(topupEl)
  }

  function topupChangeValue(el) {
    const topupVal = parseInt(el.value)
    document.getElementById("topupBtn").disabled = !(10000 <= topupVal && topupVal <= 1000000)
  }

  function tampilkanTopup() {
    document.getElementById("bgTopup").classList.remove("hidden")

    const topupVal = document.getElementById("topup_pengguna").value;

    document.getElementById("nilaiTopUp").innerHTML = numberDotSeparator(topupVal);
  }

  function tampilkanKonfirmasiTopup(topupVal, isSuccess, msg = "Sukses") {
    const successClass = ["fa-circle-check", "text-green-500"];
    const errorClass = ["fa-circle-xmark", "text-red-500"];

    document.getElementById("bgHasilTopup").classList.remove("hidden")

    document.getElementById("nilaiTopUpFinal").innerHTML = numberDotSeparator(topupVal);

    if (isSuccess) {
      document.getElementById("hasilTopupSpan").classList.add(...successClass)
      document.getElementById("errorMessage").innerHTML = msg
    } else {
      document.getElementById("hasilTopupSpan").classList.add(...errorClass)
    }
  }

  function batalkanTopUp() {
    document.getElementById("bgTopup").classList.add("hidden");
  }
</script>

<?= $this->include('includes/header') ?>

<div class="w-5/6 flex flex-col justify-self-center">
  <?= $this->include('includes/general') ?>

  <p class="text-lg">Silahkan masukkan</p>
  <h2 class="font-semibold text-2xl mb-4">Nominal Top-Up</h2>
  <div class="gap-2 flex flex-row">
    <div class="grow space-y-2">
      <div class="<?= $inputClass ?>">
        <span class="text-gray-400 fa-regular fa-credit-card pr-2 pl-1"></span>
        <input
          type="number"
          name="topup_pengguna"
          id="topup_pengguna"
          class="<?= $inputOutlineClass ?>"
          placeholder="Masukkan nominal top-up"
          oninput="topupChangeValue(this)" />
      </div>
      <button id="topupBtn" class="w-full py-2 bg-red-300 hover:bg-red-400 rounded-sm cursor-pointer disabled:cursor-not-allowed disabled:bg-gray-300" type="button" onclick="tampilkanTopup()" disabled>Top Up</button>
    </div>
    <div class="grid grid-cols-3 gap-2">
      <button class="<?= $topupBtnClass ?>" onclick="onInputTopup(10000)">Rp. 10.000</button>
      <button class="<?= $topupBtnClass ?>" onclick="onInputTopup(20000)">Rp. 20.000</button>
      <button class="<?= $topupBtnClass ?>" onclick="onInputTopup(50000)">Rp. 50.000</button>
      <button class="<?= $topupBtnClass ?>" onclick="onInputTopup(100000)">Rp. 100.000</button>
      <button class="<?= $topupBtnClass ?>" onclick="onInputTopup(250000)">Rp. 250.000</button>
      <button class="<?= $topupBtnClass ?>" onclick="onInputTopup(500000)">Rp. 500.000</button>
    </div>
  </div>
</div>

<div id="bgTopup" class="fixed h-full top-0 right-0 left-0 bg-gray-500/75 hidden">
  <div class="flex flex-col min-h-screen justify-center items-center">
    <div id="konfirmasiTopup" class="flex flex-col justify-center items-center size-72 bg-white rounded-lg space-y-2">
      <img class="size-12 object-contain" src="https://raw.githubusercontent.com/marsyadi14/HIS-PPOB-MArsyadI/refs/heads/main/assets/img/logo_sims.png" />
      <p>Anda yakin untuk Top-Up sebesar</p>
      <p class="text-xl font-bold">Rp. <span id="nilaiTopUp">0</span> ?</p>
      <button class="text-red-600 font-semibold hover:cursor-pointer" onclick="postTopupBalance()">Ya, lanjutkan Top-up</button>
      <button class="text-gray-500 font-semibold hover:cursor-pointer" onclick="batalkanTopUp()">Batalkan</button>
    </div>
  </div>
</div>

<div id="bgHasilTopup" class="fixed h-full top-0 right-0 left-0 bg-gray-500/75 hidden">
  <div class="flex flex-col min-h-screen justify-center items-center">
    <div id="hasilTopup" class="flex flex-col justify-center items-center size-72 bg-white rounded-lg space-y-2">
      <span id="hasilTopupSpan" class="text-6xl fa-solid"></span>
      <span class="text-6xl fa-solid"></span>
      <p>Top-Up sebesar</p>
      <p class="text-xl font-bold">Rp. <span id="nilaiTopUpFinal">0</span></p>
      <p class="font-semibold text-lg" id="errorMessage"></p>
      <a href="/home">Kembali ke beranda</a>
    </div>
  </div>
</div>

<?= $this->endSection() ?>