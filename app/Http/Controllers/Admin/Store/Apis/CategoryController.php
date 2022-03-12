<?php

namespace App\Http\Controllers\Admin\Store\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Store\Category\CreateRequest;
use App\Http\Requests\Admin\Store\Category\UpdateRequest;
use App\Models\Category;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use RespondsWithHttpStatus;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate(['status'=> 'required|boolean']);
        $categories = Category::query()->where('is_active', $request->status)->with('media')->paginate(10);
        return $this->success('your categories', [$categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $request['slug'] = Str::slug($request->name).now();
        $category = Category::query()->create($request->except('image'));
        $category->addMedia($request->image)->toMediaCollection('categories');
        return $this->success('successfully', [$category->get()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateRequest $request, $id)
    {
        $category = Category::query()->findOrFail($id);
        $category->name = $request->name;
        $category->is_active = $request->is_active;
        $category->save();
        if ($request->has('image'))
        {
            $category->clearMediaCollection('categories');
            $category->addMedia($request->image)->toMediaCollection('categories');
        }
        return $this->success('category updated successfully',[$category->get()] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function status(Request $request)
    {
        $request->validate(['status'=> 'required|boolean', 'category_id' => 'required']);
        $category = Category::query()->findOrFail($request->category_id);
        $category->is_active = $request->status;
        $category->save();
        return $this->success('status has ben changed', [ $category->get() ]);
    }

    public function withoutProducts()
    {
        $categories = Category::doesntHave('products')->get();
        return $this->success('categories has not products', [$categories]);
    }

    public function filter($count)
    {
        if (\Route::is('admin.category.filter.bigger'))
        {
            $categories = Category::query()->whereHas('products' , function($query) use ($count)
            {
            $query->where('price', '>', $count);
            })->paginate(100);
            return $this->success('category has products price bigger count', [$categories]);
        }
        $categories = Category::query()->whereHas('products' , function($query) use ($count)
        {
            $query->where('price', '<', $count);
        })->paginate(100);
        return $this->success('category has products price bigger count', [$categories]);
    }

    public function statusFilter(Request $request)
    {
        $request->validate(['status' => 'required|boolean']);
        $categories = Category::query()->where('is_active', $request->status)->with('products')->paginate(10);
        return $this->success('category is active with his products', [$categories]);
    }

    public function categoryWithProducts()
    {
        $categories = Category::query()->with('products')->paginate(10);
        return $this->success('categories with products',[$categories]);
    }
}
