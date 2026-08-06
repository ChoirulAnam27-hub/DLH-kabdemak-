@extends('layouts.public')

@section('title', 'Laporan Berhasil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden mt-4">
                <div class="bg-dlh-primary text-white py-5">
                    <i class="bi bi-check-circle-fill display-1 mb-3"></i>
                    <h2 class="fw-bold mb-0">Laporan Berhasil Terkirim!</h2>
                </div>
                <div class="card-body p-5">
                    <p class="text-muted mb-4">Terima kasih atas partisipasi Anda dalam menjaga kebersihan Kabupaten Demak. Laporan Anda telah masuk ke dalam sistem kami dan akan segera ditindaklanjuti.</p>
                    
                    <div class="bg-light p-4 rounded-4 mb-4 border border-2 border-dashed">
                        <span class="text-muted small fw-bold text-uppercase tracking-wide">Kode Tiket Anda</span>
                        <h2 class="display-5 fw-bold text-dark mt-2 mb-0 user-select-all" id="ticketCode">{{ $report->ticket_code }}</h2>
                    </div>
                    
                    <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-dark mb-4 text-start">
                        <i class="bi bi-exclamation-circle-fill text-warning me-2"></i>
                        <strong>PENTING:</strong> Simpan kode tiket di atas untuk melacak status penanganan laporan Anda di kemudian hari.
                    </div>

                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                        <button onclick="copyTicket()" class="btn btn-outline-primary btn-lg px-4 rounded-pill">
                            <i class="bi bi-clipboard me-2"></i> Salin Kode
                        </button>
                        <a href="{{ route('public.track.show', $report->ticket_code) }}" class="btn btn-dlh-primary btn-lg px-4 rounded-pill">
                            <i class="bi bi-search me-2"></i> Lacak Sekarang
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 pb-4">
                    <a href="{{ route('public.landing') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyTicket() {
        const ticketText = document.getElementById('ticketCode').innerText;
        navigator.clipboard.writeText(ticketText).then(function() {
            alert('Kode Tiket disalin: ' + ticketText);
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>
<style>
    .border-dashed { border-style: dashed !important; }
</style>
@endpush
