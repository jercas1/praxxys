<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\RequiredIf;

use App\Models\Product;
use App\Models\ProductFile;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::when($request->input('search'), function ($query) use ($request) {
            return $query->where('name', 'LIKE', "%{$request->input('search')}%")
                ->orWhere('description', 'LIKE', "%{$request->input('search')}%");
        })
            ->when(strlen($request->input('category')), function ($query) use ($request) {
                return $query->where('category', $request->input('category'));
            })
            ->orderBy('updated_at', 'DESC')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    public function validateStepForm($step, Request $request)
    {
        if ($step == 1) {
            $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|in:Convenience goods,Shopping goods,Specialty goods',
                'description' => 'required|string|max:4294967295',
            ]);
        } else if ($step == 2) {
            $request->validate([
                'images.*' => 'required|mimetypes:image/png,image/jpeg,image/jpg|max:102400',
                'images' => 'required',
            ]);
        } else if ($step == 2.2) {
            $request->validate([
                'images.*' => 'required|mimetypes:image/png,image/jpeg,image/jpg|max:102400',
                'images' =>
                [
                    new RequiredIf(!$request->has('old_files')),
                ],
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        $response = DB::transaction(function () use ($request, $validated) {
            $product = new Product;
            $product->name = $validated['name'];
            $product->category = $validated['category'];
            $product->description = $validated['description'];
            $product->datetime = $validated['datetime'];
            $product->save();

            $id = 1;
            $user_id = strval($request->user()->id);

            $store_location = $user_id . '/' . $product->id;

            $product_file_data['product_id'] = $product->id;

            foreach ($request->images as $image) {
                $filename_prepend = Str::orderedUuid();
                $filename = $filename_prepend . $id . '.' . $image->getClientOriginalExtension();

                Storage::disk('product')->putFileAs($store_location, $image, $filename);

                $id++;

                $product_file_data['file_url'] = "/storage/product/" . $store_location . '/' . $filename;
                ProductFile::create($product_file_data);
            }

            return response()->json([
                'success' => true,
                'title' => 'Create Success',
                'message' => 'The creation of product is successful!',
            ]);
        });

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'product' => $product->load('productFiles'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Product $product)
    {
        $validated = $request->validated();

        $response = DB::transaction(function () use ($request, $validated, $product) {
            $product->name = $validated['name'];
            $product->category = $validated['category'];
            $product->description = $validated['description'];
            $product->datetime = $validated['datetime'];
            $product->save();

            ProductFile::where('product_id', $product->id)
                ->when($request->has('old_files'), function ($query) use ($request) {
                    return $query->whereNotIn('id', $request->input('old_files'));
                })
                ->delete();

            $id = 1;
            $user_id = strval($request->user()->id);

            $store_location = $user_id . '/' . $product->id;

            $product_file_data['product_id'] = $product->id;

            if ($request->has('images')) {
                foreach ($request->images as $image) {
                    $filename_prepend = Str::orderedUuid();
                    $filename = $filename_prepend . $id . '.' . $image->getClientOriginalExtension();

                    Storage::disk('product')->putFileAs($store_location, $image, $filename);

                    $id++;

                    $product_file_data['file_url'] = "/storage/product/" . $store_location . '/' . $filename;
                    ProductFile::create($product_file_data);
                }
            }

            return response()->json([
                'success' => true,
                'title' => 'Update Success',
                'message' => 'The update of product is successful!',
            ]);
        });

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'title' => 'Delete Success',
            'message' => 'The product is successfully deleted!',
        ]);
    }
}
