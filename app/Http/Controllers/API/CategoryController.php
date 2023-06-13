<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $category = Category::latest()->paginate('10');

            if ($category) {
                return ResponseFormatter::success($category, 'Data Category Berhasil Diambil');
            } else {
                return ResponseFormatter::error(null, 'Data Category Tidak Ada', 404);
            }
        } catch (\Error $error) {
            return ResponseFormatter::error([
                'message' => 'Something Went Wrong',
                'error' => $error
            ],  'Authentication Failed', 500);
        }
    }

    public function create(Request $request)
    {
        try {
            //validate request
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'image' => 'required|mimes:png,jpg'
            ]);

            $image = $request->file('image');
            $image->storeAs('public/categories', $image->hashName());

            $category = Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
                'image' => $image->hashName()
            ]);
            if ($category) {
                return ResponseFormatter::success($category, 'Data Category Berhasil Diambil');
            } else {
                return ResponseFormatter::error(null, 'Data Category Tidak Ada', 404);
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

            $category = Category::findOrFail($id);
            Storage::disk('local')->delete('public/categories/' . basename($category->image));
            $category->delete();

            if ($category) {
                return ResponseFormatter::success($category, 'Data Category Berhasil Dihapus');
            } else {
                return ResponseFormatter::error(null, 'Data Category Tidak Ada', 404);
            }
        } catch (\Error $error) {
            return ResponseFormatter::error([
                'data' => null,
                'message' => 'Data Gagal Dihapus',
                'error' => $error
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // request validate
            $this->validate($request, [
                'name' => 'required|unique:categories,name' . $id,
                'image' => 'mimes:png,jpg|max:2000'
            ]);

            //get daat category by id
            $category = Category::findOrFail($id);

            //check jika image kosong
            if ($request->file('image') == '') {

                //update data tanpa image
                $category = Category::findOrFail($category->id);
                $category->update([
                    'name' => $request->name,
                    'slug' => Str::slug($request->name, '-')
                ]);

                if ($category) {
                    return ResponseFormatter::success($category, 'Data Category Berhasil Diupdate');
                } else {
                    return ResponseFormatter::error(null, 'Data Category Tidak Ada', 404);
                }
            } else {

                //delet image lama
                Storage::disk('local')->delete('public/categories/' . basename($category->image));

                //upload image baru
                $image = $request->file('image');
                $image->storeAs('public/categories', $image->hashName());

                //update data dengan image baru
                $category = Category::findOrFail($category->id);
                $category->update([
                    'image' => $image->hashName(),
                    'name' => $request->name,
                    'slug' => Str::slug($request->name, '-')
                ]);

                if ($category) {
                    return ResponseFormatter::success($category, 'Data Category Berhasil Diupdate');
                } else {
                    return ResponseFormatter::error(null, 'Data Category Tidak Ada', 404);
                }
            }
        } catch (\Error $error) {
            return ResponseFormatter::error([
                'data' => null,
                'message' => 'Data Gagal Diupdate',
                'error' => $error
            ]);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);

            if ($category) {
                return ResponseFormatter::success($category, 'Data Berhasil Ditampilkan');
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
