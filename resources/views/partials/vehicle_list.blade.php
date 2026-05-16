<section id="vehicles" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Armada Kami</h2>
            <p class="text-muted">Pilih kendaraan yang paling sesuai dengan kebutuhan Anda.</p>
        </div>
        <div class="row g-4">
            @forelse($kendaraans as $kendaraan)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('storage/' . str_replace('public/', '', $kendaraan->thumbnail)) }}" class="card-img-top object-fit-cover" alt="{{ $kendaraan->nama_kendaraan }}" style="height: 200px; {{ $kendaraan->status === 'tidak_tersedia' ? 'filter: grayscale(100%); opacity: 0.7;' : '' }}">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title fw-bold mb-0">{{ $kendaraan->nama_kendaraan }}</h5>
                            @if($kendaraan->status === 'tersedia')
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-danger">Tidak Tersedia</span>
                            @endif
                        </div>
                        <p class="card-text text-muted mb-3">{{ Str::limit($kendaraan->deskripsi, 80) }}</p>
                        <div class="mt-auto">
                            <p class="fs-5 fw-semibold text-primary mb-3">Rp {{ number_format($kendaraan->harga_kendaraan, 0, ',', '.') }} <span class="text-muted fs-6 fw-normal">/ {{ strtolower($kendaraan->lama_sewa) }}</span></p>
                            <a href="/admin/bookings" class="btn {{ $kendaraan->status === 'tersedia' ? 'btn-outline-primary' : 'btn-secondary disabled' }} w-100">
                                {{ $kendaraan->status === 'tersedia' ? 'Booking Sekarang' : 'Tidak Tersedia' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted mb-0">Belum ada armada kendaraan yang ditambahkan.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
