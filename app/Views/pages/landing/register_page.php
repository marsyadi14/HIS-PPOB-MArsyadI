<?= $this->extend('layouts/default') ?>

<?php
$inputClass = "p-1 flex flex-row items-center w-full border border-gray-400 rounded-sm focus-within:outline-1 focus-within:outline-red-300";
$inputOutlineClass = "w-full focus:outline-none";

$errorClass = "text-red-600";
$successClass = "text-green-600";
?>

<?= $this->section('content') ?>

<script type="text/javascript">
  function showPassword(el, passId, iconId) {
    document.getElementById(iconId).classList.toggle("fa-eye");
    document.getElementById(iconId).classList.toggle("fa-eye-slash");
    const passInp = document.getElementById(passId);
    passInp.type = el.checked ? "password" : "text";
  }

  function resetError() {
    document.getElementById("error").classList.add("hidden");
  }

  function setError(msg) {
    document.getElementById("error").classList.remove("hidden");
    document.getElementById("error").innerHTML = msg;
  }

  function setSuccess(msg) {
    document.getElementById("success").classList.remove("hidden");
    document.getElementById("success").innerHTML = msg;
  }

  const postRegisterInfo = async (emailVal, fName, lName, passVal) => {
    const registerUrl =
      "https://take-home-test-api.nutech-integrasi.com/registration";
    try {
      const response = await fetch(registerUrl, {
        method: "POST",
        body: JSON.stringify({
          email: emailVal,
          first_name: fName,
          last_name: lName,
          password: passVal,
        }),
        headers: {
          "Content-type": "application/json",
        },
      }).then((resp) => resp.json());

      if (response.status !== 0) {
        throw new Error(response.message);
      }

      setSuccess(response.message);
      setTimeout(() => {
        window.location.assign("/");
      }, 500);

    } catch (error) {
      setError(error.message);
    }
  };

  function validateRegister() {
    resetError();

    const emailVal = document.querySelector("[name=email_pengguna]").value;

    const fNameVal = document.querySelector("[name=fnama_pengguna]").value;
    const lNameVal = document.querySelector("[name=lnama_pengguna]").value;

    const passVal = document.querySelector("[name=password_pengguna]").value;
    const konfPassVal = document.querySelector(
      "[name=konfirmasi_password]"
    ).value;

    if (passVal !== konfPassVal) {
      setError("Mohon tulis ulang password dengan benar");
    } else {
      postRegisterInfo(emailVal, fNameVal, lNameVal, passVal);
    }
  }

  window.onload = () => {
    if (sessionStorage.getItem("token")) {
      window.location.assign("/home");
    }
  };
</script>

<div class="min-h-screen flex flex-row">
  <div class="flex flex-col grow justify-center items-center">
    <div class="flex flex-row space-x-2 mb-4">
      <img class="p-1 h-auto object-cover" src="https://raw.githubusercontent.com/marsyadi14/HIS-PPOB-MArsyadI/refs/heads/main/assets/img/logo_sims.png" />
      <h1 class="font-bold text-4xl">SIMS PPOB</h1>
    </div>
    <div class="w-1/3 flex flex-col items-center space-y-4">
      <h2 class="text-2xl text-center font-semibold text-wrap">
        Lengkapi data untuk membuat akun
      </h2>
      <div class="<?= $inputClass ?>">
        <span class="text-gray-400 fa-regular fa-at pr-2 pl-1"></span>
        <input
          type="email"
          name="email_pengguna"
          id="email_pengguna"
          class="<?= $inputOutlineClass ?>"
          placeholder="Masukkan email anda" />
      </div>

      <div class="<?= $inputClass ?>">
        <span class="text-gray-400 fa-regular fa-user pr-2 pl-1"></span>
        <input
          type="text"
          name="fnama_pengguna"
          id="fnama_pengguna"
          class="<?= $inputOutlineClass ?>"
          placeholder="Masukkan Nama depan anda" />
      </div>
      <div class="<?= $inputClass ?>">
        <span class="text-gray-400 fa-regular fa-user pr-2 pl-1"></span>
        <input
          type="text"
          name="lnama_pengguna"
          id="lnama_pengguna"
          class="<?= $inputOutlineClass ?>"
          placeholder="Masukkan Nama belakang anda" />
      </div>

      <div class="<?= $inputClass ?>">
        <span class="text-gray-400 fa-solid fa-lock pr-2 pl-1"></span>
        <input
          type="password"
          name="password_pengguna"
          id="password_pengguna"
          class="<?= $inputOutlineClass ?>"
          placeholder="Masukkan password anda" />
        <label>
          <input
            type="checkbox"
            id="show_password_pengguna"
            class="hidden"
            onchange="showPassword(this, 'password_pengguna', 'showPass')"
            checked />
          <span id="showPass" class="fa-regular fa-eye"></span>
        </label>
      </div>

      <div class="<?= $inputClass ?>">
        <span class="text-gray-400 fa-solid fa-lock pr-2 pl-1"></span>
        <input
          type="password"
          name="konfirmasi_password"
          id="konfirmasi_password"
          class="<?= $inputOutlineClass ?>"
          placeholder="Konfirmasi password anda" />
        <label>
          <input
            type="checkbox"
            id="show_konfirmasi_password"
            class="hidden"
            onchange="showPassword(this, 'konfirmasi_password', 'showKonf')"
            checked />
          <span id="showKonf" class="fa-regular fa-eye"></span>
        </label>
      </div>

      <p id="error" class="hidden <?= $errorClass ?>"></p>
      <p id="success" class="hidden <?= $successClass ?>"></p>
      <button
        type="button"
        class="w-full py-2 bg-red-300 hover:bg-red-400 rounded-sm cursor-pointer"
        onclick="validateRegister()">
        Registrasi
      </button>
      <p>
        Sudah punya akun? Login <a href="/" class="text-red-400">di sini</a>
      </p>
    </div>
  </div>
  <div>
    <img class="h-screen object-cover" src="https://raw.githubusercontent.com/marsyadi14/HIS-PPOB-MArsyadI/refs/heads/main/assets/img/ilus_login.png" />
  </div>
</div>

<?= $this->endSection() ?>