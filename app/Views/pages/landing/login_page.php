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

  const postLoginInfo = async (emailVal, passVal) => {
    resetError()

    const loginUrl = "https://take-home-test-api.nutech-integrasi.com/login";
    try {
      const response = await fetch(loginUrl, {
        method: "POST",
        body: JSON.stringify({
          email: emailVal,
          password: passVal,
        }),
        headers: {
          "Content-type": "application/json",
        },
      }).then(resp => resp.json());

      if (response.status !== 0) {
        throw new Error(response.message);
      }

      sessionStorage.setItem("token", response.data.token);

      setSuccess(response.message);
      setTimeout(() => {
        window.location.assign("/home");
      }, 500);

    } catch (error) {
      setError(error.message)
    }
  };

  function validateLogin() {
    const emailVal = document.querySelector("[name=email_pengguna]").value;
    const passVal = document.querySelector("[name=password_pengguna]").value;

    postLoginInfo(emailVal, passVal)
  }

  window.onload = () => {
    if (sessionStorage.getItem("token")) {
      window.location.assign("/home");
    }
  }
</script>

<div class="min-h-screen flex flex-row">
  <div class="flex flex-col grow justify-center items-center">
    <div class="flex flex-row space-x-2 mb-4">
      <img
        class="p-1 h-auto object-cover ..."
        src="/assets/img/logo_sims.png" />
      <h1 class="font-bold text-4xl">SIMS PPOB</h1>
    </div>
    <div class="w-1/3 flex flex-col items-center space-y-4">
      <h2 class="text-2xl text-center font-semibold text-wrap">
        Masuk atau buat akun untuk memulai
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

      <p id="error" class="hidden <?= $errorClass ?>"></p>
      <p id="success" class="hidden <?= $successClass ?>"></p>

      <button
        type="button"
        class="w-full py-2 bg-red-300 hover:bg-red-400 rounded-sm cursor-pointer"
        onclick="validateLogin()">
        Masuk
      </button>
      <p>
        Belum punya akun? Register
        <a href="/register" class="text-red-400">di sini</a>
      </p>
    </div>
  </div>
  <div>
    <img class="h-screen object-cover" src="/assets/img/ilus_login.png" />
  </div>
</div>

<?= $this->endSection() ?>