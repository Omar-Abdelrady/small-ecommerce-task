<?php

namespace App\Http\Controllers\Admin\Store\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Store\Product\FilterRequest;
use App\Http\Requests\Admin\Store\Product\StoreRequest;
use App\Models\Product;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use RespondsWithHttpStatus;
    public function index()
    {

    }

    public function store(StoreRequest $request)
    {
        $request->request->add(['slug' => Str::slug($request->name.now())]);
        $product = Product::query()->create($request->except('image'));
        $product->addMedia($request->image)->toMediaCollection('products');
        return $this->success('product created successfully', [$product->get()]);
    }

    public function update(StoreRequest $request, $id)
    {
        $product = Product::query()->findOrFail($id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->is_active = $request->is_active;
        $product->save();
        if ($request->has('image'))
        {
            $product->clearMediaCollection('products');
            $product->addMedia($request->image)->toMediaCollection('products');
        }
        return $this->success('product updated successfully.', [$product->get()]);
    }

    public function status(Request $request, $id)
    {
        $request->validate(['is_active' => 'required|boolean']);
        $product = Product::query()->findOrFail($id);
        $product->is_active = $request->is_active;
        $product->save();
        return $this->success('status of product updated successfully', [$product->get()]);
    }

    public function filter($count)
    {
        if (\Route::is('admin.product.filter.bigger'))
        {
            $products = Product::query()->where('price', '>', $count)->paginate(10);
            return $this->success('products bigger', [$products]);
        }
        $products = Product::query()->where('price', '<', $count)->paginate(10);
        return $this->success('products smaller', [$products]);
    }

    public function getStatusProducts(Request $request)
    {
        $request->validate(['status' => 'required|boolean']);
        $products = Product::query()->where('is_active', $request->status)->paginate(10);
        return $this->success('product related status', [$products]);
    }

    public function delete($id)
    {
        $product = Product::query()->findOrFail($id);
        $product->delete();
        return $this->success('product deleted successfully', ['(:']);
    }
}
