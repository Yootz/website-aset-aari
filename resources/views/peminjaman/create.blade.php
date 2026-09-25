@extends('layouts.app')

@section('title', 'Buat Peminjaman | AARI')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Asset operations / New loan</p>
            <h1>Catat peminjaman baru.</h1>
            <p>Pilih karyawan, jadwal peminjaman, dan aset yang akan dibawa keluar dari inventaris.</p>
        </div>
        <a href="{{ route('peminjaman.index') }}" class="secondary-button"><span class="button-symbol"><-</span> Kembali</a>
    </div>

    <div class="form-layout">
        <section class="form-panel">
            <h2>Detail peminjaman</h2>
            <p>Aset yang dipilih akan berubah menjadi unavailable setelah transaksi disimpan.</p>

            @if($errors->any())
                <div class="flash-message" style="border-color: #f2b6ad; color: #9e3b30; background: #fff0ed;">
                    <span style="background: #f2b6ad;">!</span>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            @if($employees->isEmpty() || $assets->isEmpty())
                <div class="dashboard-empty">
                    @if($employees->isEmpty())
                        Tambahkan karyawan terlebih dahulu sebelum membuat peminjaman.
                        <a href="{{ route('employee.create') }}">Tambah karyawan -></a>
                    @elseif($assets->isEmpty())
                        Tidak ada aset available yang dapat dipinjam saat ini.
                        <a href="{{ route('asset.index') }}">Lihat daftar aset -></a>
                    @endif
                </div>
            @else
                <form action="{{ route('peminjaman.store') }}" method="POST">
                    @csrf
                    <div class="field">
                        <label for="e_code">Peminjam <span class="required">*</span></label>
                        <select id="e_code" name="e_code" required>
                            <option value="">Pilih karyawan</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->e_code }}" @selected(old('e_code') === $employee->e_code)>{{ $employee->e_name }} - {{ $employee->e_code }}</option>
                            @endforeach
                        </select>
                        @error('e_code') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-two-column">
                        <div class="field">
                            <label for="tgl_pinjam">Tanggal pinjam <span class="required">*</span></label>
                            <input id="tgl_pinjam" type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam', now()->format('Y-m-d')) }}" required>
                            @error('tgl_pinjam') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="field">
                            <label for="tgl_balik">Rencana kembali <span class="required">*</span></label>
                            <input id="tgl_balik" type="date" name="tgl_balik" value="{{ old('tgl_balik') }}" required>
                            @error('tgl_balik') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="field">
                        <label for="p_desc">Catatan</label>
                        <textarea id="p_desc" name="p_desc" placeholder="Tambahkan kebutuhan atau keterangan peminjaman">{{ old('p_desc') }}</textarea>
                    </div>

                    <div class="field">
                        <label for="asset-select-0">Aset yang dipinjam <span class="required">*</span></label>
                        <div id="asset-fields" class="asset-fields">
                            <div class="asset-field-row">
                                <select id="asset-select-0" name="details[0][a_code]" required>
                                    <option value="">Pilih aset available</option>
                                    @foreach($assets as $asset)
                                        <option value="{{ $asset->a_code }}">{{ $asset->a_code }} - {{ $asset->a_name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="details[0][dt_qty]" value="1">
                                <button type="button" class="action-link action-delete remove-asset" aria-label="Hapus aset" hidden>Hapus</button>
                            </div>
                        </div>
                        <button type="button" id="add-asset" class="secondary-button asset-add-button"><span class="button-symbol">+</span> Tambah aset lain</button>
                        @error('details') <p class="field-error">{{ $message }}</p> @enderror
                        @error('details.0.a_code') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('peminjaman.index') }}" class="secondary-button">Batal</a>
                        <button type="submit" class="primary-button"><span class="button-symbol">+</span> Simpan peminjaman</button>
                    </div>
                </form>
            @endif
        </section>

        <aside class="form-note">
            <div class="form-note-mark">↗</div>
            <h3>Jaga alur aset tetap terbaca.</h3>
            <p>Setiap aset hanya bisa dipinjam satu kali ketika statusnya available.</p>
            <ul>
                <li>Pilih karyawan yang bertanggung jawab.</li>
                <li>Pastikan rencana pengembalian benar.</li>
                <li>Gunakan detail untuk kebutuhan khusus.</li>
            </ul>
        </aside>
    </div>

    @if(!$employees->isEmpty() && !$assets->isEmpty())
        <script>
            const assetOptions = @json($assets->map(fn ($asset) => ['code' => $asset->a_code, 'name' => $asset->a_name])->values());
            const assetFields = document.getElementById('asset-fields');
            const addAssetButton = document.getElementById('add-asset');

            const syncAssetOptions = () => {
                const selects = [...assetFields.querySelectorAll('select[name*="[a_code]"]')];
                const selectedCodes = new Set(selects.map((select) => select.value).filter(Boolean));

                selects.forEach((select) => {
                    [...select.options].forEach((option) => {
                        option.disabled = option.value !== select.value && selectedCodes.has(option.value);
                    });
                });
            };

            const bindAssetSelect = (select) => {
                select.addEventListener('change', syncAssetOptions);
            };

            assetFields.addEventListener('click', (event) => {
                if (!event.target.classList.contains('remove-asset')) {
                    return;
                }

                event.target.closest('.asset-field-row').remove();
                updateRemoveButtons();
                syncAssetOptions();
            });

            addAssetButton.addEventListener('click', () => {
                const index = assetFields.children.length;
                const row = document.createElement('div');
                row.className = 'asset-field-row';
                row.innerHTML = `<select id="asset-select-${index}" name="details[${index}][a_code]" required><option value="">Pilih aset available</option>${assetOptions.map((asset) => `<option value="${asset.code}">${asset.code} - ${asset.name}</option>`).join('')}</select><input type="hidden" name="details[${index}][dt_qty]" value="1"><button type="button" class="action-link action-delete remove-asset">Hapus</button>`;
                assetFields.appendChild(row);
                bindAssetSelect(row.querySelector('select'));
                syncAssetOptions();
                updateRemoveButtons();
            });

            const updateRemoveButtons = () => {
                const buttons = assetFields.querySelectorAll('.remove-asset');
                buttons.forEach((button) => {
                    button.hidden = buttons.length === 1;
                });
            };

            bindAssetSelect(document.getElementById('asset-select-0'));
            updateRemoveButtons();
            syncAssetOptions();
        </script>
    @endif
@endsection
