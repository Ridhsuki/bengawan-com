<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->latest()->take(8)->get();
        return view('pages.home', compact('products'));
    }

    public function products(Request $request)
    {
        $query = Product::where('is_active', true);

        if ($request->has('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->has('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('pages.products.index', compact('products', 'categories'));
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

    public function show()
    // public function show(Product $product)
    {
        return view('pages.products.show');
        // return view('pages.products.show', compact('product'));
    }

    public function service()
    {
        return view('pages.services'); // Static page, text bisa ambil dari $company->about
    }
    public function about()
    {
        return view('pages.about'); // Static page, text bisa ambil dari $company->about
    }
}
