<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::active()->latest()->take(6)->get();

        return view('pages.home', compact('products'));
    }

    public function products(Request $request)
    {
        $categories = Category::withCount('products')->get();

        $products = Product::active()
            ->when($request->category, function (Builder $query, $slug) {
                $query->whereHas('category', function (Builder $q) use ($slug) {
                    $q->where('slug', $slug);
                });
            })
            ->when($request->price, function (Builder $query, $price) {
                switch ($price) {
                    case 'lt_2m':
                        $query->where('price', '<', 2000000);
                        break;
                    case 'lt_5m':
                        $query->where('price', '<', 5000000);
                        break;
                    case 'lt_10m':
                        $query->where('price', '<', 10000000);
                        break;
                    case 'lt_20m':
                        $query->where('price', '<', 20000000);
                        break;
                    case 'gt_20m':
                        $query->where('price', '>=', 20000000);
                        break;
                }
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('pages.products.index', compact('products', 'categories'));
    }

    public function productDetail(Product $product)
    {
        $product->load(['category', 'images']);

        $images = collect([$product->image])
            ->concat($product->images->pluck('image'))
            ->filter();

        if ($images->isEmpty()) {
            $images->push(null);
        }

        return view('pages.products.show', compact('product', 'images'));
    }


    public function discount()
    {
        $products = Product::where('is_active', true)
            ->whereNotNull('discount_price')
            ->where('discount_price', '>', 0)
            ->latest()
            ->paginate(12);

        return view('pages.products.discount');
        // return view('discount', compact('products'));
    }

    public function show(Product $product)
    {
        return view('pages.products.show', compact('product'));
    }

    public function service()
    {
        return view('pages.services');
    }
    public function about()
    {
        return view('pages.about');
    }
}
