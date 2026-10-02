@extends('layouts.app')

@section('title', 'Edit Peminjaman | AARI')

@section('content')
	<div class="page-heading">
		<div>
			<p class="eyebrow">Transaksi / Peminjaman</p>
			<h1>Perbarui detail peminjaman.</h1>
			<p>Pastikan informasi peminjam, aset, dan status pengembalian tetap sesuai.</p>
		</div>
		<a href="{{ route('peminjaman.index') }}" class="secondary-button">Kembali ke daftar</a>
	</div>

	<section class="form-panel">
		<h2>Edit detail peminjaman</h2>
		<p>Kode peminjaman adalah identitas tetap dan tidak dapat diubah.</p>

		@if($errors->any())
			<div class="flash-message" data-swal-type="error" style="border-color: #f2b6ad; color: #9e3b30; background: #fff0ed;">
				<span style="background: #f2b6ad;">!</span>
				<div>{{ $errors->first() }}</div>
			</div>
		@endif

		<form action="{{ route('peminjaman.update', $peminjaman->p_code) }}" method="POST">
			@csrf
			@method('PUT')

			<div class="field">
				<label for="p_code">Kode peminjaman</label>
				<input id="p_code" type="text" value="{{ $peminjaman->p_code }}" readonly class="field-readonly">
				<p class="field-hint">Kode digunakan sebagai identitas unik peminjaman.</p>
			</div>

			<div class="field">
				<label for="e_code">Peminjam <span class="required">*</span></label>
				<select id="e_code" name="e_code" required>
					@foreach($employees as $employee)
						<option value="{{ $employee->e_code }}" @selected(old('e_code', $peminjaman->e_code) === $employee->e_code)>{{ $employee->e_name }} ({{ $employee->e_code }})</option>
					@endforeach
				</select>
				@error('e_code') <p class="field-error">{{ $message }}</p> @enderror
			</div>

			<div class="field">
				<label for="asset-edit-select-{{ array_key_first($detailRows) }}">Aset yang dipinjam <span class="required">*</span></label>
				<div id="asset-fields" class="asset-fields">
					@foreach($detailRows as $index => $detailRow)
						<div class="asset-field-row" data-index="{{ $index }}">
							<select id="asset-edit-select-{{ $index }}" name="details[{{ $index }}][a_code]" required>
								<option value="">Pilih aset available</option>
								@foreach($assets as $asset)
									<option value="{{ $asset->a_code }}" @selected(($detailRow['a_code'] ?? '') === $asset->a_code)>{{ $asset->a_code }} - {{ $asset->a_name }}</option>
								@endforeach
							</select>
							<input type="hidden" name="details[{{ $index }}][dt_qty]" value="{{ $detailRow['dt_qty'] ?? 1 }}">
							<button type="button" class="action-link action-delete remove-asset" aria-label="Hapus aset" @if($loop->count === 1) hidden @endif>Hapus</button>
						</div>
						@error('details.'.$index.'.a_code') <p class="field-error">{{ $message }}</p> @enderror
					@endforeach
				</div>
				<button type="button" id="add-asset" class="secondary-button asset-add-button"><span class="button-symbol">+</span> Tambah aset lain</button>
				@error('details') <p class="field-error">{{ $message }}</p> @enderror
			</div>

			<div class="field">
				<label for="tgl_pinjam">Tanggal peminjaman <span class="required">*</span></label>
				<input id="tgl_pinjam" type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam', $peminjaman->tgl_pinjam?->format('Y-m-d')) }}" required>
				@error('tgl_pinjam') <p class="field-error">{{ $message }}</p> @enderror
			</div>

			<div class="field">
				<label for="tgl_balik">Tanggal pengembalian</label>
				<input id="tgl_balik" type="date" name="tgl_balik" value="{{ old('tgl_balik', $peminjaman->tgl_balik?->format('Y-m-d')) }}">
				@error('tgl_balik') <p class="field-error">{{ $message }}</p> @enderror
			</div>

			<div class="form-actions">
				<a href="{{ route('peminjaman.index') }}" class="secondary-button">Batal</a>
				<button type="submit" class="primary-button"><span class="button-symbol">✓</span> Simpan perubahan</button>
			</div>
		</form>
	</section>

	<script>
		const assetOptions = @json($assets->map(fn ($asset) => ['code' => $asset->a_code, 'name' => $asset->a_name])->values());
		const assetFields = document.getElementById('asset-fields');
		const addAssetButton = document.getElementById('add-asset');
		const assetIndexes = [...assetFields.querySelectorAll('.asset-field-row')]
			.map((row) => Number(row.dataset.index));
		let nextAssetIndex = Math.max(-1, ...assetIndexes) + 1;

		const syncAssetOptions = () => {
			const selects = [...assetFields.querySelectorAll('select[name*="[a_code]"]')];
			const selectedCodes = new Set(selects.map((select) => select.value).filter(Boolean));

			selects.forEach((select) => {
				[...select.options].forEach((option) => {
					option.disabled = option.value !== select.value && selectedCodes.has(option.value);
				});
			});
		};

		const updateRemoveButtons = () => {
			const buttons = assetFields.querySelectorAll('.remove-asset');
			buttons.forEach((button) => {
				button.hidden = buttons.length === 1;
			});
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
			const index = nextAssetIndex++;
			const row = document.createElement('div');
			row.className = 'asset-field-row';
			row.dataset.index = index;
			row.innerHTML = `<select id="asset-edit-select-${index}" name="details[${index}][a_code]" required><option value="">Pilih aset available</option>${assetOptions.map((asset) => `<option value="${asset.code}">${asset.code} - ${asset.name}</option>`).join('')}</select><input type="hidden" name="details[${index}][dt_qty]" value="1"><button type="button" class="action-link action-delete remove-asset" aria-label="Hapus aset">Hapus</button>`;
			assetFields.appendChild(row);
			updateRemoveButtons();
			syncAssetOptions();
		});

		assetFields.querySelectorAll('select').forEach((select) => {
			select.addEventListener('change', syncAssetOptions);
		});
		updateRemoveButtons();
		syncAssetOptions();
	</script>
@endsection
