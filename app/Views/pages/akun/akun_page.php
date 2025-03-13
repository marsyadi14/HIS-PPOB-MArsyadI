<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<script type="text/javascript">
  function showPassword(el, passId, iconId) {
    document.getElementById(iconId).classList.toggle("fa-eye");
    document.getElementById(iconId).classList.toggle("fa-eye-slash");
    const passInp = document.getElementById(passId);
    passInp.type = el.checked ? "password" : "text";
  }

  const setProfileInfo = (email, fname, lname, imgLink) => {
    const namaPenggunaEl = document.getElementById("nama_pengguna");
    const ikonPenggunaEl = document.getElementById("ikon_pengguna");

    document.getElementById("fnama_pengguna").value = fname;
    document.getElementById("lnama_pengguna").value = lname;
    document.getElementById("email_pengguna").value = email;

    namaPenggunaEl.innerHTML = `${fname} ${lname}`;

    if (imgLink !== "https://minio.nutech-integrasi.com/take-home-test/null") {
      ikonPenggunaEl.src = imgLink;
    }
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

  const putProfileFetch = async () => {
    const token = sessionStorage.getItem("token");
    const url =
      "https://take-home-test-api.nutech-integrasi.com/profile/update";

    const fnew = document.getElementById("fnama_pengguna").value
    const lnew = document.getElementById("lnama_pengguna").value

    return await fetch(url, {
        method: "PUT",
        body: JSON.stringify({
          "first_name": fnew,
          "last_name": lnew
        }),
        headers: {
          "Content-type": "application/json",
          Authorization: `Bearer ${token}`
        },
      })
      .then((resp) => resp.json())
  }

  const putProfileImageFetch = async () => {
    const token = sessionStorage.getItem("token");
    const url =
      "https://take-home-test-api.nutech-integrasi.com/profile/image";

    const file = document.getElementById("fotoProfilBaru").files[0]

    const formData = new FormData();
    formData.append("file", file);

    return await fetch(url, {
        method: "PUT",
        body: formData,
        headers: {
          Authorization: `Bearer ${token}`
        },
      })
      .then((resp) => resp.json())
  }

  const getProfileInfo = async () => {
    const profileUrl = "https://take-home-test-api.nutech-integrasi.com/profile";
    const balanceUrl = "https://take-home-test-api.nutech-integrasi.com/balance";

    try {
      const token = sessionStorage.getItem("token");

      getFetch(profileUrl, token)
        .then((response) => {
          const respData = response.data
          setProfileInfo(respData.email, respData.first_name, respData.last_name, respData.profile_image)
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

  const editProfil = () => {
    const editEl = document.getElementById("editBtn")
    const logoutEl = document.getElementById("logoutBtn")
    const simpanEl = document.getElementById("simpanBtn")

    const fotoEdit = document.getElementById("fotoProfilEdit")
    fotoEdit.classList.remove("hidden")

    const fname = document.getElementById("fnama_pengguna")
    const lname = document.getElementById("lnama_pengguna")

    fname.disabled = false;
    lname.disabled = false;

    editEl.classList.add("hidden")
    logoutEl.classList.add("hidden")
    simpanEl.classList.remove("hidden")
  }

  const simpanProfil = () => {
    try {
      if (document.getElementById("fotoProfilBaru").files.length) {
        putProfileImageFetch()
          .then((resp => putProfileFetch()))
          .then(resp => {
            window.location.reload()
          })
      } else {
        putProfileFetch()
          .then(resp => {
            window.location.reload()
          })
      }
    } catch (err) {
      console.error(err.message)
    }

  }

  const photoProfileChanged = () => {
    const file = document.getElementById("fotoProfilBaru").files[0]
    var fr = new FileReader();
    fr.onload = function() {
      document.getElementById("ikon_pengguna").src = fr.result;
    }
    fr.readAsDataURL(file);
  }

  const logout = () => {
    sessionStorage.clear()
    window.location.assign("/");
  }
</script>

<?php
$inputClass = "p-1 flex flex-row items-center w-full border border-gray-400 rounded-sm focus-within:outline-1 focus-within:outline-red-300 mb-2 mt-1";
$inputOutlineClass = "w-full focus:outline-none";

$errorClass = "text-red-600";
$successClass = "text-green-600";
?>

<?= $this->include('includes/header') ?>

<div class="w-1/2 flex flex-col justify-self-center items-center">
  <div class="flex flex-col m-4 relative">
    <img
      id="ikon_pengguna"
      class="p-1 w-32 h-auto object-cover"
      src="https://raw.githubusercontent.com/marsyadi14/HIS-PPOB-MArsyadI/refs/heads/main/assets/img/profile_ikon.png"
      alt="Ikon" />
    <label id="fotoProfilEdit" class="hidden">
      <input type="file" name="fotoProfilBaru" id="fotoProfilBaru" accept="image/png, image/jpeg" hidden onchange="photoProfileChanged(this)">
      <span class="absolute bottom-0 right-0 text-3xl fa-solid fa-pen-to-square text-gray-500 hover:text-gray-700"></span>
    </label>
  </div>

  <h3 id="nama_pengguna" class="font-semibold text-3xl">Nama Pengguna</h3>

  <p class="w-full text-left">Email</p>
  <div class="<?= $inputClass ?>">
    <span class="text-gray-400 fa-regular fa-at pr-2 pl-1"></span>
    <input
      type="email"
      name="email_pengguna"
      id="email_pengguna"
      class="<?= $inputOutlineClass ?>"
      placeholder="Masukkan email anda" disabled />
  </div>

  <p class="w-full text-left">Nama Depan</p>
  <div class="<?= $inputClass ?>">
    <span class="text-gray-400 fa-regular fa-user pr-2 pl-1"></span>
    <input
      type="text"
      name="fnama_pengguna"
      id="fnama_pengguna"
      class="<?= $inputOutlineClass ?>"
      placeholder="Masukkan Nama depan anda" disabled />
  </div>
  <p class="w-full text-left">Nama Belakang</p>
  <div class="<?= $inputClass ?>">
    <span class="text-gray-400 fa-regular fa-user pr-2 pl-1"></span>
    <input
      type="text"
      name="lnama_pengguna"
      id="lnama_pengguna"
      class="<?= $inputOutlineClass ?>"
      placeholder="Masukkan Nama belakang anda" disabled />
  </div>

  <button
    id="simpanBtn"
    type="button"
    class="w-full py-2 bg-red-500 hover:bg-red-400 rounded-sm cursor-pointer mb-3 hidden"
    onclick="simpanProfil()">
    Simpan Profil
  </button>

  <button
    id="editBtn"
    type="button"
    class="w-full py-2 bg-red-500 hover:bg-red-400 rounded-sm cursor-pointer mb-3"
    onclick="editProfil()">
    Edit Profil
  </button>

  <button
    id="logoutBtn"
    type="button"
    class="w-full py-2 bg-red-500 hover:bg-red-400 rounded-sm cursor-pointer"
    onclick="logout()">
    Logout
  </button>
</div>

</div>

<?= $this->endSection() ?>