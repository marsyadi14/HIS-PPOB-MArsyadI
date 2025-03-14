<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<?php
$showmoreBtnClass = "py-2 px-4 text-red-400 border border-gray-400 rounded-md hover:bg-gray-200";
?>

<script type="text/javascript">
  const setProfileInfo = (fname, lname, imgLink) => {
    const namaPenggunaEl = document.getElementById("general_nama_pengguna");
    const ikonPenggunaEl = document.getElementById("general_ikon_pengguna");

    namaPenggunaEl.innerHTML = `${fname} ${lname}`;

    if (imgLink !== "https://minio.nutech-integrasi.com/take-home-test/null") {
      ikonPenggunaEl.src = imgLink;
    }
  }

  const numberDotSeparator = (x) => x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

  const setBalanceInfo = (balance) => {
    const balanceEl = document.getElementById("saldoId");

    balanceEl.innerHTML = numberDotSeparator(balance)
  }

  const setHistoryInfo = (records) => {
    const historyEl = document.getElementById("historyId");

    const daftarBulan = ["Januari",
      "Februari",
      "Maret",
      "April",
      "Mei",
      "Juni",
      "Juli",
      "Agustus",
      "September",
      "Oktober",
      "November",
      "Desember",
    ]

    let daftHistory = []
    for (let i in records) {
      let textClass = (records[i].transaction_type === "PAYMENT") ? "text-red-400" : "text-green-400";
      let spanClass = (records[i].transaction_type === "PAYMENT") ? "fa-minus" : "fa-plus";

      let currDate = new Date(records[i].created_on)
      let currDateText = `${currDate.getDate()} ${daftarBulan[currDate.getMonth()]} ${currDate.getFullYear()} ${currDate.getHours()}:${currDate.getMinutes()} WIB`

      daftHistory.push(`
      <div class="flex grow justify-between border border-gray-200 rounded-lg p-3">
        <div class="flex flex-col space-y-2">
          <p class="text-2xl ${textClass} items-center space-x-2">
            <span class="fa-solid ${spanClass}"></span>
            Rp. ${numberDotSeparator(records[i].total_amount)}
          </p>
          <p class="font-light text-sm">${currDateText}</p>
        </div>
        <div class="flex flex-col space-y-2 text-right">
          <p class="text-md font-semibold">${records[i].description}</p>
          <p>Invoice ${records[i].invoice_number}</p>
        </div>
      </div>
      `)
    }

    historyEl.innerHTML = daftHistory.join("")
  }

  const getFetch = async (url, token) => {
    return await fetch(url, {
        method: "GET",
        headers: {
          "Content-type": "application/json",
          "Authorization": `Bearer ${token}`
        },
      })
      .then((resp) => resp.json())
  }

  const getProfileInfo = async () => {
    const profileUrl = "https://take-home-test-api.nutech-integrasi.com/profile";
    const balanceUrl = "https://take-home-test-api.nutech-integrasi.com/balance";

    const offset = Number(document.querySelector("[name=offsetTransaksi").value)
    const historyUrl = `https://take-home-test-api.nutech-integrasi.com/transaction/history?offset=${offset}&limit=5`;

    try {
      const token = sessionStorage.getItem("token");

      getFetch(profileUrl, token)
        .then((response) => {
          const respData = response.data
          setProfileInfo(respData.first_name, respData.last_name, respData.profile_image)
        })

      getFetch(balanceUrl, token)
        .then((response) => {
          const balance = response.data.balance
          setBalanceInfo(balance)
        })

      getFetch(historyUrl, token)
        .then((response) => {
          const history = response.data
          setHistoryInfo(history.records)
        })

    } catch (error) {
      console.error(error.message);
    }
  };

  window.onload = () => {
    if (!sessionStorage.getItem("token")) {
      window.location.assign("/");
    } else {
      document.querySelector("[name=offsetTransaksi").value = 0

      getProfileInfo()
    }
  };

  const showmoreButton = () => {
    if (Number(document.querySelector("[name=offsetTransaksi").value) === -1) return

    const offset = Number(document.querySelector("[name=offsetTransaksi").value) + 5
    const historyUrl = `https://take-home-test-api.nutech-integrasi.com/transaction/history?offset=${offset}&limit=5`;

    const token = sessionStorage.getItem("token");

    try {
      document.getElementById("showmoreId").disabled = true;
      getFetch(historyUrl, token)
        .then((response) => {
          const history = response.data

          if (history.records.length === 5) {
            document.querySelector("[name=offsetTransaksi").value = Number(document.querySelector("[name=offsetTransaksi").value) + 5;
            setHistoryInfo(history.records)
          } else if (history.records.length > 0) {
            setHistoryInfo(history.records)
            document.querySelector("[name=offsetTransaksi").value = -1
            document.getElementById("showmoreId").classList.add("hidden")
          } else {
            document.querySelector("[name=offsetTransaksi").value = -1
            document.getElementById("showmoreId").classList.add("hidden")
          }
        })
    } catch (error) {
      console.error(error.message);
    } finally {
      document.getElementById("showmoreId").disabled = false;
    }
  }
</script>

<?= $this->include('includes/header') ?>

<div class="w-5/6 flex flex-col justify-self-center my-2">
  <?= $this->include('includes/general') ?>

  <input type="hidden" name="offsetTransaksi">
  <h2 class="font-semibold text-lg">Semua Transaksi</h2>

  <div id="historyId" class="flex flex-col gap-2 mt-2 mb-4">
  </div>

  <button id="showmoreId" class="<?= $showmoreBtnClass ?>" onclick="showmoreButton()">Show More</button>
</div>

<?= $this->endSection() ?>