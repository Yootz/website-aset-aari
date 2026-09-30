<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QRCodeController extends Controller
{
    public function index(Request $request)
    {
        $assets = DB::table('master_aset')
            ->select('a_code', 'a_name')
            ->orderBy('a_code')
            ->get();

        $selectedCode = $request->query('a_code', $assets->first()?->a_code);
        $selectedSize = (int) $request->query('size', 180);

        $allowedSizes = [120, 160, 180, 220, 300];
        if (! in_array($selectedSize, $allowedSizes, true)) {
            $selectedSize = 180;
        }

        $selectedAsset = $assets->firstWhere('a_code', $selectedCode) ?? $assets->first();

        $qrUrl = $selectedAsset
            ? route('asset.lookup', ['a_code' => $selectedAsset->a_code])
            : route('asset.index');

        return view('createqr.index', [
            'assets' => $assets,
            'selectedAsset' => $selectedAsset,
            'selectedSize' => $selectedSize,
            'allowedSizes' => $allowedSizes,
            'qrUrl' => $qrUrl,
        ]);
    }
}
