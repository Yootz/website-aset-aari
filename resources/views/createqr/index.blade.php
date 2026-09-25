@extends('layouts.app')

@section('title', 'QR Aset | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Asset operations / QR</p>
            <h1>Berikan setiap aset pintu masuknya.</h1>
            <p>Generate QR code yang mengarah langsung ke detail aset dan riwayat peminjamannya.</p>
        </div>
        <button type="button" class="primary-button no-print" onclick="window.print()"><span class="button-symbol">+</span> Print QR</button>
    </div>

    <section class="qr-layout">
        <div class="form-panel no-print">
            <h2>Atur QR code</h2>
            <p>Pilih aset dan ukuran QR yang ingin dicetak.</p>
            <form method="GET" action="{{ route('createqr.index') }}" id="qrForm">
                <div class="field">
                    <label for="a_code">Pilih aset <span class="required">*</span></label>
                    <select name="a_code" id="a_code">
                        @forelse ($assets as $asset)
                            <option value="{{ $asset->a_code }}" {{ $selectedAsset && $selectedAsset->a_code === $asset->a_code ? 'selected' : '' }}>{{ $asset->a_code }} - {{ $asset->a_name ?? 'Aset' }}</option>
                        @empty
                            <option value="">Tidak ada data aset</option>
                        @endforelse
                    </select>
                </div>
                <div class="field">
                    <label for="size">Ukuran QR</label>
                    <select name="size" id="size">
                        @foreach ($allowedSizes as $sizeOption)
                            <option value="{{ $sizeOption }}" {{ $selectedSize == $sizeOption ? 'selected' : '' }}>{{ $sizeOption }} px</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="primary-button">Generate <span class="button-symbol">-></span></button>
                </div>
            </form>
        </div>

        <div class="qr-preview-panel">
            <div class="qr-preview-head">
                <div>
                    <p class="eyebrow">Preview</p>
                    <h2>{{ $selectedAsset?->a_name ?? 'Belum ada aset' }}</h2>
                </div>
                @if ($selectedAsset)
                    <span class="code-chip">{{ $selectedAsset->a_code }}</span>
                @endif
            </div>
            @if ($selectedAsset)
                <div class="qr-box print-area">
                    <div class="qr-preview">
                        <canvas id="qrCanvas" width="{{ $selectedSize }}" height="{{ $selectedSize }}" data-qr-url="{{ $qrUrl }}" data-size="{{ $selectedSize }}"></canvas>
                    </div>
                    <div class="code-label" id="assetCode">{{ $selectedAsset->a_code }}</div>
                    <div class="code-url" id="qrUrlText">{{ $qrUrl }}</div>
                </div>
            @else
                <div class="empty-state"><div class="empty-symbol">+</div><h3>Belum ada aset</h3><p>Tambahkan data aset terlebih dahulu untuk membuat QR code.</p></div>
            @endif
        </div>
    </section>
@endsection
