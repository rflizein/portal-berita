<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        try {
            $news = News::latest()->paginate('10');

            if ($news) {
                return ResponseFormatter::success($news, 'Data News Berhasil Diambil');
            } else {
                return ResponseFormatter::error(null, 'Data News Tidak Ada', 404);
            }
        } catch (\Error $error) {
            return ResponseFormatter::error([
                'data' => null,
                'message' => 'Data Gagal Di lihat',
                'error' => $error
            ]);
        }
    }

    public function show($id)
    {
        try {
            $news = News::findOrFail($id);

            if ($news) {
                return ResponseFormatter::success($news, 'Data Berhasil Ditampilkan');
            } else {
                return ResponseFormatter::error(null, 'Data Gagal Ditampilkan', 404);
            }
        } catch (\Error $error) {
            return ResponseFormatter::error([
                'data' => null,
                'message' => 'Data Gagal Di lihat',
                'error' => $error
            ]);
        }
    }
}
