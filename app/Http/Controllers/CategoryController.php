<?php
namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Categories::latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except('image');

            if ($request->hasFile('image')) {
                $imageName = time().'_'.$request->image->getClientOriginalName();
                $request->image->move(public_path('uploads/categories'), $imageName);
                $data['image'] = $imageName;
            }

            Categories::create($data);
            DB::commit();
            return redirect()->route('categories.index')->with('success','Category created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $category = Categories::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image',
        ]);

        DB::beginTransaction();
        try {
            $category = Categories::findOrFail($id);
            $data = $request->except('image');

            if ($request->hasFile('image')) {
                $imageName = time().'_'.$request->image->getClientOriginalName();
                $request->image->move(public_path('uploads/categories'), $imageName);
                $data['image'] = $imageName;
            }

            $category->update($data);
            DB::commit();
            return redirect()->route('categories.index')->with('success','Category updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $category = Categories::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success','Category deleted successfully.');
    }
}
