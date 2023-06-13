<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        try {
            $slider = Slider::latest()->paginate('10');

            if ($slider) {
                return ResponseFormatter::success($slider, 'Data Slider Berhasil Diambil');
            } else {
                return ResponseFormatter::error(null, 'Data Slider Tidak Ada', 404);
            }
        } catch (\Error $error) {
            return ResponseFormatter::error([
                'message' => 'Something Went Wrong',
                'error' => $error
            ],  'Authentication Failed', 500);
        }
    }

    public function createSlider(Request $request)
    {
        try {
            $this->validate($request, [
                'url' => 'required|string|max:255',
                'image' => 'required|mimes:png,jpg'
            ]);

            $image = $request->file('image');
            $image->storeAs('public/categories', $image->hashName());

            $slider = Slider::create([
                'url' => $request->url,
                'image' => $image->hashName()
            ]);
            if ($slider) {
                return ResponseFormatter::success($slider, 'Data Slider Berhasil Diambil');
            } else {
                return ResponseFormatter::error(null, 'Data Slider Tidak Ada', 404);
            }
        } catch (\Error $error) {
            return ResponseFormatter::error([
                'data' => null,
                'message' => 'Data gagal ditambahkan',
                'error' => $error
            ]);
        }
    }

    public function destroy($id)
    {
        try {

            $slider = Slider::findOrFail($id);
            Storage::disk('local')->delete('public/categories/' . basename($slider->image));
            $slider->delete();

            if ($slider) {
                return ResponseFormatter::success($slider, 'Data Slider Berhasil Dihapus');
            } else {
                return ResponseFormatter::error(null, 'Data Slider Tidak Ada', 404);
            }
        } catch (\Error $error) {
            return ResponseFormatter::error([
                'data' => null,
                'message' => 'Data Gagal Dihapus',
                'error' => $error
            ]);
        }
    }

    public function show($id)
    {
        try {
            $slider = Slider::findOrFail($id);

            if ($slider) {
                return ResponseFormatter::success($slider, 'Data Berhasil Ditampilkan');
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
