<script type="text/javascript">
  function showSaldo(el, iconId) {
    document.getElementById(iconId).classList.toggle("fa-eye");
    document.getElementById(iconId).classList.toggle("fa-eye-slash");
    const saldoId = document.getElementById("saldoId");
    const saldoIdHidden = document.getElementById("saldoIdHidden");
    saldoId.classList.toggle("hidden");
    saldoIdHidden.classList.toggle("hidden");
  }
</script>

<div class="grid grid-cols-2 my-4">
  <div class="flex flex-col m-4">
    <img
      id="general_ikon_pengguna"
      class="p-1 w-20 h-auto object-cover"
      src="/assets/img/profile_ikon.png"
      alt="Ikon"
    />
    <h2 class="text-xl">Selamat Datang,</h2>
    <h3 id="general_nama_pengguna" class="font-semibold text-3xl">Nama Pengguna</h3>
  </div>
  <div
    class="flex flex-col justify-between bg-red-600 text-white rounded-lg p-6"
  >
    <p class="text-lg">Saldo Anda</p>
    <p class="font-bold text-4xl">
      Rp.
      <span id="saldoId" class="hidden">10.000</span>
      <span id="saldoIdHidden"
        >&#128952;&#128952;&#128952;&#128952;&#128952;</span
      >
    </p>
    <div>
      <label class="select-none cursor-pointer">
        <input
          type="checkbox"
          id="show_saldo_pengguna"
          class="hidden"
          onchange="showSaldo(this, 'show_saldo_pengguna', 'showSaldo')"
          checked
        />
        Lihat Saldo <span id="showSaldo" class="ml-1 fa-regular fa-eye"></span>
      </label>
    </div>
  </div>
</div>
