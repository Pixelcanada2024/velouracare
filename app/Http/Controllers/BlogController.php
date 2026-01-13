<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class BlogController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_blogs'])->only('index');
        $this->middleware(['permission:add_blog'])->only('create');
        $this->middleware(['permission:edit_blog'])->only('edit');
        $this->middleware(['permission:delete_blog'])->only('destroy');
        $this->middleware(['permission:publish_blog'])->only('change_status');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $blogs = Blog::orderBy('created_at', 'desc');

        if ($request->search != null) {
            $blogs = $blogs->where('title', 'like', '%' . $request->search . '%')->orWhere('title_trans', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }

        $blogs = $blogs->paginate(15);

        return view('backend.blog_system.blog.index', compact('blogs', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $blog_categories = BlogCategory::all();
        return view('backend.blog_system.blog.create', compact('blog_categories'));
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
            'category_id' => 'required',
            'title_trans.en' => 'required|string|max:255',
            'short_description_trans.en' => 'required|string',
            'description_trans.en' => 'required|string',
        ]);

        $blog = new Blog;

        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        $blog->title_trans = [
            'en' => $request->title_trans['en']
        ];
        $blog->description_trans = [
            'en' => $request->description_trans['en']
        ];
        $blog->short_description_trans = [
            'en' => $request->short_description_trans['en']
        ];

        $blog->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));

        $blog->meta_title = $request->meta_title;
        $blog->meta_img = $request->meta_img;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;

        $blog->save();

        if ( isset ($request->banner_trans['en']) && $request->banner_trans['en'] != null )
        {
          $blog->setImage('banner', $request->banner_trans['en'] , 'en');
        }

        flash(translate('Blog post has been created successfully'))->success();
        return redirect()->route('blog.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blog = Blog::find($id);
        $blog_categories = BlogCategory::all();

        return view('backend.blog_system.blog.edit', compact('blog', 'blog_categories'));
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
        $lang = $request['lang'] ?? 'en';

        $request->validate([
            'category_id' => 'required',
            'lang' => 'required|string|in:en,ar',
            "title_trans.{$lang}" => 'required|string|max:255',
            "short_description_trans.{$lang}" => 'required|string',
            "description_trans.{$lang}" => 'required|string',
            "banner_trans.{$lang}" => 'nullable',
        ]);

        $blog = Blog::find($id);

        $blog->category_id = $request->category_id;

        $titleTrans = $blog->title_trans ?? [];
        $blog->title_trans = [
            ...$titleTrans,
            $lang => $request->title_trans[$lang]
        ];

        $descriptionTrans = $blog->description_trans ?? [];
        $blog->description_trans = [
            ...$descriptionTrans,
            $lang => $request->description_trans[$lang]
        ];

        $shortDescriptionTrans = $blog->short_description_trans ?? [];
        $blog->short_description_trans = [
            ...$shortDescriptionTrans,
            $lang => $request->short_description_trans[$lang]
        ];

        $blog->slug =( $lang != 'en' ? $request->slug : preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)));

        $blog->meta_title = $request->meta_title;
        $blog->meta_img = $request->meta_img;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;

        $blog->save();

        if ( isset ($request->banner_trans[$lang]) && $request->banner_trans[$lang] != null )
        {
          $blog->setImage('banner', $request->banner_trans[$lang] , $lang);
        } else if ( isset ($request->banner_trans[$lang]) && $request->banner_trans[$lang] == null )
        {
          $blog->deleteIfExist('banner', $lang);
        }

        flash(translate('Blog post has been updated successfully'))->success();
        return redirect()->route('blog.index');
    }

    public function change_status(Request $request)
    {
        $blog = Blog::find($request->id);
        $blog->status = $request->status;

        $blog->save();
        return 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Blog::find($id)->delete();
        return back();
    }


    public function old_all_blog(Request $request)
    {
        $selected_categories = array();
        $search = null;
        $blogs = Blog::query();

        if ($request->has('search')) {
            $search = $request->search;;
            $blogs->where(function ($q) use ($search) {
                foreach (explode(' ', trim($search)) as $word) {
                    $q->where('title', 'like', '%' . $word . '%')
                        ->orWhere('short_description', 'like', '%' . $word . '%');
                }
            });

            $case1 = $search . '%';
            $case2 = '%' . $search . '%';

            $blogs->orderByRaw("CASE
                WHEN title LIKE '$case1' THEN 1
                WHEN title LIKE '$case2' THEN 2
                ELSE 3
                END");
        }

        if ($request->has('selected_categories')) {
            $selected_categories = $request->selected_categories;
            $blog_categories = BlogCategory::whereIn('slug', $selected_categories)->pluck('id')->toArray();

            $blogs->whereIn('category_id', $blog_categories);
        }

        $blogs = $blogs->where('status', 1)->orderBy('created_at', 'desc')->paginate(12);

        $recent_blogs = Blog::where('status', 1)->orderBy('created_at', 'desc')->limit(9)->get();

        return view("frontend.blog.listing", compact('blogs', 'selected_categories', 'search', 'recent_blogs'));
    }

    public function old_blog_details($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        $recent_blogs = Blog::where('status', 1)->orderBy('created_at', 'desc')->limit(9)->get();
        return view("frontend.blog.details", compact('blog', 'recent_blogs'));
    }

    protected function blogResource(Blog $blog , $type = 'single')
    {
        $blogArray = [
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'created_At' => optional($blog->created_at)->format('M d, Y'),
            'banner' => !!$blog->banner ? uploaded_asset($blog->banner) : null,
        ];

        if ($type == 'all') {
          $blogArray['short_description'] = $blog->short_description;
          $blogArray['description'] = $blog->description;
        }

        return $blogArray;
    }

    public function all_blog(Request $request)
    {
    $blogs = Blog::where('status', 1)
                  ->with('translatedImages.upload')
                  ->latest()
                  ->paginate(9)
                  ->through( function ($blog) {
                      return $this->blogResource($blog, 'all');
                  });

    return inertia('Blog/Blog', [
        'blogs' => $blogs,
    ]);

    }

    public function blog_details(Blog $blog)
    {
        $blog->load('bannerUpload');


        $blogTransformed = $this->blogResource($blog, 'single');

        return inertia('Blog/BlogShow', [
            'blog' => $blogTransformed,
        ]);
    }




    public function generateSlug(Request $request)
    {
        $translator = new GoogleTranslate('en'); // Target language
        $translated = $translator->translate($request->title); // auto detects source

        // Slugify the translated string
        $slug = Str::slug($translated);

        return response()->json(['slug' => $slug]);
    }
}
