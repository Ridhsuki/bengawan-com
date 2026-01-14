<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    private function setSeo($title = null, $description = null, $image = null, $type = 'website')
    {
        view()->share([
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'type' => $type,
        ]);
    }

    public function index()
    {
        $this->setSeo(
            title: 'Home | Bengawan Computer',
            description: 'Pusat Laptop Murah & Service Terpercaya di Solo Raya. Menyediakan laptop baru, bekas, dan sparepart berkualitas.'
        );
        $products = Product::active()
            ->where(function ($query) {
                $query->whereNull('discount_price')
                    ->orWhere('discount_price', 0);
            })
            ->latest()
            ->take(6)
            ->get();

        return view('pages.home', compact('products'));
    }

    public function products(Request $request)
    {
        $pageTitle = 'Katalog Produk';
        if ($request->category) {
            $pageTitle = 'Jual Laptop ' . Str::headline($request->category);
        } elseif ($request->search) {
            $pageTitle = 'Hasil Pencarian: ' . $request->search;
        }

        $this->setSeo($pageTitle . ' | Bengawan Computer');

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
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('pages.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images']);

        $images = collect([$product->image])
            ->concat($product->images->pluck('image'))
            ->filter();

        if ($images->isEmpty()) {
            $images->push(null);
        }

        $this->setSeo(
            title: $product->name . ' | Spesifikasi & Harga Murah',
            description: Str::limit(strip_tags($product->description), 160),
            image: $product->image ? asset('storage/' . $product->image) : null,
            type: 'product'
        );

        return view('pages.products.show', compact('product', 'images'));
    }

    public function discount()
    {
        $this->setSeo(
            title: 'Promo & Diskon Laptop Murah Solo',
            description: 'Dapatkan penawaran harga terbaik untuk laptop dan aksesoris komputer di Bengawan Computer.'
        );

        $products = Product::where('is_active', true)
            ->whereNotNull('discount_price')
            ->where('discount_price', '>', 0)
            ->latest()
            ->paginate(12);

        return view('pages.products.discount', compact('products'));
    }

    public function service()
    {
        $this->setSeo(
            title: 'Service Center Laptop Terpercaya Solo Raya',
            description: 'Layanan perbaikan laptop segala merk, ganti LCD, Keyboard, Baterai, Install Ulang, dll.'
        );

        return view('pages.services');
    }

    public function about()
    {
        $this->setSeo(
            title: 'Tentang Kami - Bengawan Computer',
            description: 'Profil perusahaan Bengawan Computer, visi misi, dan lokasi toko kami.'
        );

        return view('pages.about');
    }

    public function searchSuggestions(Request $request)
    {
        $query = trim($request->get('q'));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhereFullText(['name', 'description'], $query);
            })
            ->select('id', 'name', 'slug', 'image', 'price', 'discount_price')
            ->limit(5)
            ->get();

        return response()->json(
            $products->map(fn($product) => [
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $product->image
                    ? asset('storage/' . $product->image)
                    : asset('assets/img/no-image.webp'),
                'price' => $product->formatted_price,
                'discount_price' => $product->has_discount
                    ? $product->formatted_discount_price
                    : null,
                'url' => route('products.show', $product->slug),
            ])
        );
    }
}
