<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Post;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $categories = Category::paginate(10);
        return view('dashboard.categories.index')->with('categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("dashboard.categories.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name'=> 'required|string',
            'category_slug'=> 'required|string'
        ]);
        $category = [
            'category_name'=> $request->category_name,
            'category_slug'=> $request->category_slug
        ];
        $insert = Category::firstOrCreate($category);
        if($insert){
            return redirect()->back()->with(['msg'=>'Category Succesfully Added']);
        }
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        return view('dashboard.categories.edit')->with('category',$category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name'=> 'required|string',
            'category_slug'=> 'required|string'
        ]);

        $category = [
            'category_name'=> $request->category_name,
            'category_slug'=> $request->category_slug
        ];

        $insert = Category::find($id)->update($category);
        
        if($insert){
            return redirect()->back()->with(['msg'=>'Category Succesfully edited']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $posts = Post::where('category_id',$id)->count();
        // dd($Posts);
        if($posts > 0) {
            return redirect()->back()->with([
                'hasPosts'=>'This Category has '.$posts.' Posts',
                'category_id'=> $id
            ]);
        }else {
            $category = Category::find($id)->delete();
            if($category) {
                return redirect()->back()->with([
                    'msg'=>'Category deleted succesfully!'
                ]);
            }
        }
    }
}
