<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">{{ \App\Models\Setting::where('key', 'brand_name')->value('value') ?? 'RentalMobil' }}</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#about">Tentang Kami</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#vehicles">Armada Kami</a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-primary text-white ms-lg-3 px-4" href="/admin">Login Admin</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
