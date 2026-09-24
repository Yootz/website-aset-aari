<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat QR Code Aset</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f4f7ff 0%, #edf7ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .qr-card {
            width: min(100%, 760px);
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 18px 50px rgba(27, 57, 106, 0.12);
            overflow: hidden;
        }

        .qr-header {
            background: linear-gradient(135deg, #0d6efd, #0ea5e9);
            color: white;
            padding: 20px 28px;
        }

        .qr-body {
            padding: 28px;
        }

        .qr-box {
            background: #f8fbff;
            border: 1px solid #dfeeff;
            border-radius: 18px;
            padding: 24px;
            min-height: 420px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .code-label {
            margin-top: 16px;
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 0.04em;
        }

        .size-select {
            max-width: 180px;
        }

        .qr-preview {
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 16px;
            padding: 18px;
            box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.04);
        }

        .code-url {
            margin-top: 18px;
            font-size: 0.82rem;
            color: #475569;
            word-break: break-word;
            text-align: center;
        }

        .no-print {
            display: block;
        }

        @media print {
            body {
                background: #fff;
                display: block;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            .qr-card {
                width: 100%;
                box-shadow: none;
                border: none;
                border-radius: 0;
            }

            .qr-header {
                display: none;
            }

            .qr-body {
                padding: 0;
            }

            .qr-box {
                min-height: auto;
                border: none;
                background: #fff;
                padding: 0;
            }

            .code-url {
                font-size: 10pt;
                margin-top: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="qr-card">
        <div class="qr-header no-print">
            <h3 class="mb-0">Generate QR Code Aset</h3>
        </div>

        <div class="qr-body">
            <div class="d-flex justify-content-end mb-3 no-print">
                <button type="button" class="btn btn-dark" onclick="window.print()">Print QR</button>
            </div>

            <form method="GET" action="{{ route('createqr.index') }}" id="qrForm" class="mb-4 no-print">
                <div class="row g-3 align-items-end">
                    <div class="col-md-7">
                        <label for="a_code" class="form-label fw-semibold">Pilih Aset</label>
                        <select name="a_code" id="a_code" class="form-select form-select-lg">
                            @forelse ($assets as $asset)
                                <option value="{{ $asset->a_code }}" {{ $selectedAsset && $selectedAsset->a_code === $asset->a_code ? 'selected' : '' }}>
                                    {{ $asset->a_code }}
                                </option>
                            @empty
                                <option value="">Tidak ada data aset</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="size" class="form-label fw-semibold">Ukuran</label>
                        <select name="size" id="size" class="form-select form-select-lg size-select">
                            @foreach ($allowedSizes as $sizeOption)
                                <option value="{{ $sizeOption }}" {{ $selectedSize == $sizeOption ? 'selected' : '' }}>
                                    {{ $sizeOption }} px
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Generate</button>
                    </div>
                </div>
            </form>

            @if ($selectedAsset)
                <div class="qr-box print-area">
                    <div class="qr-preview">
                        <canvas id="qrCanvas"
                            width="{{ $selectedSize }}"
                            height="{{ $selectedSize }}"
                            data-qr-url="{{ $qrUrl }}"
                            data-size="{{ $selectedSize }}">
                        </canvas>
                    </div>
                    <div class="code-label" id="assetCode">{{ $selectedAsset->a_code }}</div>
                    <div class="code-url" id="qrUrlText">{{ $qrUrl }}</div>
                </div>
            @else
                <div class="alert alert-warning mb-0">Tidak ada data aset yang tersedia.</div>
            @endif
        </div>
    </div>

    @vite('resources/js/app.js')
</body>
</html>
