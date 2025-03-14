<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

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

  const setBannerInfo = (banner) => {
    const bannerEl = document.getElementById("bannerId");

    let daftBanner = []
    for (let i in banner) {
      daftBanner.push(`
      <img
        class="h-auto object-cover"
        src="${banner[i].banner_image}"
        alt="${banner[i].banner_name}"
      />
      `)
    }

    bannerEl.innerHTML = daftBanner.join("")
  }

  const setServiceInfo = (services) => {
    const serviceEl = document.getElementById("serviceId");

    let daftService = []
    for (let i in services) {
      daftService.push(`
      <div class="hover:opacity-75">
        <a href="/transaksi/${services[i].service_code}">
          <img
            class="h-20 object-contain"
            src="${services[i].service_icon}"
            alt="${services[i].service_code}_${services[i].service_tariff}"
          />
          <p class="font-light text-sm text-center text-wrap">${services[i].service_name}</p>
        </a>
      </div>
      `)
    }

    serviceEl.innerHTML = daftService.join("")
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

    const bannerUrl = "https://take-home-test-api.nutech-integrasi.com/banner";
    const servicesUrl = "https://take-home-test-api.nutech-integrasi.com/services";

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

      getFetch(bannerUrl, token)
        .then((response) => {
          setBannerInfo(response.data)
        })

      getFetch(servicesUrl, token)
        .then((response) => {
          setServiceInfo(response.data)
        })

    } catch (error) {
      console.error(error.message);
    }
  };

  window.onload = () => {
    if (!sessionStorage.getItem("token")) {
      window.location.assign("/");
    } else {
      getProfileInfo()
    }
  };
</script>

<?= $this->include('includes/header') ?>

<div class="w-5/6 flex flex-col justify-self-center">
  <?= $this->include('includes/general') ?>

  <div id="serviceId" class="grid grid-cols-12 gap-1">Daftar Servis</div>
  <h2 class="font-semibold text-md">Temukan Promo Menarik</h2>
  <div id="bannerId" class="flex-wrap flex flex-row gap-2">Slide Banner promo</div>
</div>

<?= $this->endSection() ?>