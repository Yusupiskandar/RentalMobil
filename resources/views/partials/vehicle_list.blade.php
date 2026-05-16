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
                    <img src="{{ asset('storage/' . str_replace('public/', '', $kendaraan->thumbnail)) }}" 
                         class="card-img-top object-fit-cover" 
                         alt="{{ $kendaraan->nama_kendaraan }}" 
                         style="height: 200px; cursor: pointer; {{ $kendaraan->status === 'tidak_tersedia' ? 'filter: grayscale(100%); opacity: 0.7;' : '' }}"
                         data-bs-toggle="modal" 
                         data-bs-target="#modalKendaraan{{ $kendaraan->id }}">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title fw-bold mb-0" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalKendaraan{{ $kendaraan->id }}">
                                {{ $kendaraan->nama_kendaraan }}
                            </h5>
                            @if($kendaraan->status === 'tersedia')
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-danger">Tidak Tersedia</span>
                            @endif
                        </div>
                        <p class="card-text text-muted mb-3">{{ Str::limit(strip_tags($kendaraan->description), 80) }}</p>
                        <div class="mt-auto">
                            <p class="fs-5 fw-semibold text-primary mb-3">Rp {{ number_format($kendaraan->harga_kendaraan, 0, ',', '.') }} <span class="text-muted fs-6 fw-normal">/ {{ strtolower($kendaraan->lama_sewa) }}</span></p>
                            <a href="/admin/bookings" class="btn {{ $kendaraan->status === 'tersedia' ? 'btn-outline-primary' : 'btn-secondary disabled' }} w-100">
                                {{ $kendaraan->status === 'tersedia' ? 'Booking Sekarang' : 'Tidak Tersedia' }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail Kendaraan -->
                <div class="modal fade" id="modalKendaraan{{ $kendaraan->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $kendaraan->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold" id="modalLabel{{ $kendaraan->id }}">{{ $kendaraan->nama_kendaraan }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Carousel Galeri -->
                                <div id="carouselKendaraan{{ $kendaraan->id }}" class="carousel slide mb-4" data-bs-ride="carousel">
                                    <div class="carousel-inner rounded shadow-sm bg-dark">
                                        <!-- Thumbnail Utama -->
                                        <div class="carousel-item active">
                                            <img src="{{ asset('storage/' . str_replace('public/', '', $kendaraan->thumbnail)) }}" class="d-block w-100 object-fit-contain" alt="Thumbnail" style="height: 400px;">
                                        </div>
                                        <!-- Galeri Tambahan -->
                                        @php
                                            $galeri = is_string($kendaraan->gambar_kendaraan) ? json_decode($kendaraan->gambar_kendaraan, true) : $kendaraan->gambar_kendaraan;
                                        @endphp
                                        @if(is_array($galeri))
                                            @foreach($galeri as $gambar)
                                                <div class="carousel-item">
                                                    <img src="{{ asset('storage/' . str_replace('public/', '', $gambar)) }}" class="d-block w-100 object-fit-contain" alt="Galeri" style="height: 400px;">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselKendaraan{{ $kendaraan->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselKendaraan{{ $kendaraan->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>

                                <!-- Detail Informasi Kendaraan -->
                                <div class="px-2">
                                    <h4 class="fw-bold text-primary mb-3">Rp {{ number_format($kendaraan->harga_kendaraan, 0, ',', '.') }} <span class="text-muted fs-6 fw-normal">/ {{ strtolower($kendaraan->lama_sewa) }}</span></h4>
                                    
                                    <h5 class="fw-semibold">Deskripsi Kendaraan</h5>
                                    <div class="text-muted">
                                        {!! $kendaraan->description !!}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <a href="/admin/bookings" class="btn {{ $kendaraan->status === 'tersedia' ? 'btn-primary' : 'btn-secondary disabled' }} px-4">
                                    {{ $kendaraan->status === 'tersedia' ? 'Booking Sekarang' : 'Tidak Tersedia' }}
                                </a>
                            </div>
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
