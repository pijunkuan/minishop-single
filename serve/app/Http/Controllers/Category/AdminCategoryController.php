<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = new Category();
        if ($request->has('visibility') && !is_null($request->get('visibility'))) {
            $categories = $categories->where('visibility', $request->get('visibility'));
        }
        if ($request->get('title')) {
            $categories = $categories->where('category_title', 'like', "%{$request->get('title')}%");
        }
        return $this->jsonSuccessResponse($categories->get());
    }

    public function store(CategoryStoreRequest $request)
    {
        if (Category::count() > 20) return $this->jsonErrorResponse(404, "超出最大分类数量（20）");
        $category = new Category([
            "category_title" => $request->get('category_title'),
            "visibility" => $request->get('visibility')
        ]);
        $category->save();
        return $this->jsonSuccessResponse($category);
    }

    public function update(Category $category, Request $request)
    {
        if ($request->get('category_title')) $category['category_title'] = $request->get('category_title');
        if ($request->has('visibility')) $category['visibility'] = $request->get('visibility');
        $category->save();
        return $this->jsonSuccessResponse($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return $this->jsonSuccessResponse();
    }
}
