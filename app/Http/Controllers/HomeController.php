<?php

namespace App\Http\Controllers;

use App\Models\Land;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * トップ画面を表示
     */
    public function index()
    {
        // おすすめの土地（最新6件）
        $recommendedLands = Land::where('STATUS', 1)
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('recommendedLands'));
    }

    /**
     * 検索結果画面へリダイレクト
     */
    public function search(Request $request)
    {
        return redirect()->route('lands.search', $request->all());
    }
}
