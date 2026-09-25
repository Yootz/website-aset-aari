<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function index(): View
    {
        $assets = Asset::withCount('details')->orderBy('a_code')->get();

        return view('asset.index', compact('assets'));
    }

    public function show(Asset $asset): View
    {
        $asset->load(['details.peminjaman.employee']);

        return view('asset.show', compact('asset'));
    }

    public function lookup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'a_code' => ['required', 'exists:master_aset,a_code'],
        ]);

        return redirect()->route('asset.show', $validated['a_code']);
    }
}
