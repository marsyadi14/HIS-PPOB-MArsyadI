<?= $this->extend('layouts/default') ?>

<?php
$inputClass = "p-2 flex flex-row items-center w-full border border-gray-400 rounded-sm focus-within:outline-1 focus-within:outline-red-300";
$inputOutlineClass = "w-full focus:outline-none";

$transaksiBtnClass = "py-2 px-4 border border-gray-400 rounded-md hover:bg-gray-200";

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

  const setServiceList = (services) => {
    const servisVal = (document.getElementById("servisId").value).toUpperCase();

    const imgJenis = document.getElementById("imgJenisPembayaran")
    const jenisPembayaran = document.getElementById("jenisPembayaran")
    const transaksiJenis = document.getElementById("transaksi_pengguna")
    const transaksiTipe = document.getElementById("transaksiTipe")

    let index = -1

    for (let i in services) {
      if (servisVal === services[i].service_code) {
        index = i;
        break;
      }
    }

    if (index >= 0) {
      const selectedJenis = services[index]

      imgJenis.src = selectedJenis.service_icon
      transaksiJenis.value = selectedJenis.service_tariff
      jenisPembayaran.innerHTML = selectedJenis.service_name
      transaksiTipe.innerHTML = selectedJenis.service_name

      const topupVal = document.getElementById("transaksi_pengguna").value;
      document.getElementById("nilaiTransaksi").innerHTML = numberDotSeparator(topupVal);
    } else {
      document.getElementById("containerTransaksi").classList.add("hidden");
      document.getElementById("transaksi_pengguna").classList.add("hidden");

      imgJenis.classList.add("hidden")
      jenisPembayaran.innerHTML = "Servis Tidak Ada"
      transaksiTipe.innerHTML = "Servis Tidak Ada"
      transaksiJenis.value = 0;
    }
  }

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

    const servicesUrl = "https://take-home-test-api.nutech-integrasi.com/services";

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

      getFetch(servicesUrl, token)
        .then((response) => {
          setServiceList(response.data)
        })
    } catch (error) {
      console.error(error.message);
    }
  };

  const postTransaksi = async () => {
    const kodeServis = (document.getElementById("servisId").value).toUpperCase();
    transaksi_pengguna
    const balance = Number(document.getElementById("transaksi_pengguna").value)
    const transactionUrl =
      "https://take-home-test-api.nutech-integrasi.com/transaction";
    try {
      const token = sessionStorage.getItem("token");

      const saldoKini = Number((document.getElementById("saldoId").innerText).replace(".", ""));

      if (balance > saldoKini) {
        throw new Error("Saldo Kurang");
      }

      const response = await fetch(transactionUrl, {
        method: "POST",
        body: JSON.stringify({
          service_code: kodeServis,
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
          batalkanTransaksi()
        }
      });
    }
  };

  function tampilkanTopup() {
    document.getElementById("bgTopup").classList.remove("hidden")
  }

  function tampilkanKonfirmasiTopup(topupVal, isSuccess, msg = "Sukses") {
    const successClass = ["fa-circle-check", "text-green-500"];
    const errorClass = ["fa-circle-xmark", "text-red-500"];

    document.getElementById("bgHasilTopup").classList.remove("hidden")

    document.getElementById("nilaiTransaksiFinal").innerHTML = numberDotSeparator(topupVal);

    if (isSuccess) {
      document.getElementById("hasilTopupSpan").classList.add(...successClass)
      document.getElementById("hasilMessage").classList.add("text-green-600")
      document.getElementById("hasilMessage").innerHTML = msg
    } else {
      document.getElementById("hasilTopupSpan").classList.add(...errorClass)
      document.getElementById("hasilMessage").classList.add("text-red-600")
      document.getElementById("hasilMessage").innerHTML = msg
    }
  }

  function batalkanTransaksi() {
    document.getElementById("bgTopup").classList.add("hidden");
  }
</script>

<?= $this->include('includes/header') ?>

<div class="w-5/6 flex flex-col justify-self-center">
  <?= $this->include('includes/general') ?>

  <h2 class="font-semibold text-2xl mb-4">Pembayaran</h2>

  <input type="hidden" id="servisId" value="<?= $id_servis; ?>">

  <div class="flex gap-2 items-center">
    <img id="imgJenisPembayaran" src="" alt="terpilih">
    <p class="font-semibold text-2xl m-1 py-2" id="jenisPembayaran"></p>
  </div>

  <div id="containerTransaksi" class="gap-2 flex flex-col">
    <div class="<?= $inputClass ?>">
      <span class="text-gray-400 fa-regular fa-credit-card pr-2 pl-1"></span>
      <input
        type="number"
        name="transaksi_pengguna"
        id="transaksi_pengguna"
        class="<?= $inputOutlineClass ?>"
        disabled />
    </div>
    <button id="transaksiBtn" class="w-full py-2 bg-red-300 hover:bg-red-400 rounded-sm cursor-pointer disabled:cursor-not-allowed disabled:bg-gray-300" type="button" onclick="tampilkanTopup()">Transaksi</button>
  </div>

  <div id="bgTopup" class="fixed h-full top-0 right-0 left-0 bg-gray-500/75 hidden">
    <div class="flex flex-col min-h-screen justify-center items-center">
      <div id="konfirmasiTopup" class="flex flex-col justify-center items-center size-72 bg-white rounded-lg space-y-2">
        <img class="size-12 object-contain" src="https://raw.githubusercontent.com/marsyadi14/HIS-PPOB-MArsyadI/refs/heads/main/assets/img/logo_sims.png" />
        <p>Beli <span id="transaksiTipe"></span> Senilai</p>
        <p class="text-xl font-bold">Rp. <span id="nilaiTransaksi">0</span> ?</p>
        <button class="text-red-600 font-semibold hover:cursor-pointer" onclick="postTransaksi()">Ya, lanjutkan bayar</button>
        <button class="text-gray-500 font-semibold hover:cursor-pointer" onclick="batalkanTransaksi()">Batalkan</button>
      </div>
    </div>
  </div>

  <div id="bgHasilTopup" class="fixed h-full top-0 right-0 left-0 bg-gray-500/75 hidden">
    <div class="flex flex-col min-h-screen justify-center items-center">
      <div id="hasilTopup" class="flex flex-col justify-center items-center size-72 bg-white rounded-lg space-y-2">
        <span id="hasilTopupSpan" class="text-6xl fa-solid"></span>
        <span class="text-6xl fa-solid"></span>
        <p>Pembayaran sebesar</p>
        <p class="text-xl font-bold">Rp. <span id="nilaiTransaksiFinal">0</span></p>
        <p class="font-semibold text-md" id="hasilMessage"></p>
        <a href="/home" class="text-red-600">Kembali ke beranda</a>
      </div>
    </div>
  </div>

  <?= $this->endSection() ?>