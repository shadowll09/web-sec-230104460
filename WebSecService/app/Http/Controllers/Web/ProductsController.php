<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use DB;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller {

	use ValidatesRequests;

	public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }

	public function list(Request $request) {

		$query = Product::select("products.*");

		$query->when($request->keywords,
		fn($q)=> $q->where("name", "like", "%$request->keywords%"));

		$query->when($request->min_price,
		fn($q)=> $q->where("price", ">=", $request->min_price));

		$query->when($request->max_price, fn($q)=>
		$q->where("price", "<=", $request->max_price));

		$query->when($request->order_by,
		fn($q)=> $q->orderBy($request->order_by, $request->order_direction??"ASC"));

		// For customers, only show products with stock > 0
		if (auth()->check() && auth()->user()->hasRole('Customer')) {
			$query->where('stock_quantity', '>', 0);
		}

		$products = $query->get();

		return view('products.list', compact('products'));
	}

	public function edit(Request $request, Product $product = null) {
		// Check if user has permission to edit products
		if(!auth()->user()->hasPermissionTo('edit_products')) {
			abort(403, 'You do not have permission to edit products.');
		}

		$product = $product??new Product();

		return view('products.edit', compact('product'));
	}

	public function save(Request $request, Product $product = null) {
        // Check if user has permission to add or edit products
        if(!auth()->user()->hasAnyPermission(['add_products', 'edit_products'])) {
			abort(403, 'You do not have permission to add or edit products.');
		}

        $this->validate($request, [
            'code' => ['required', 'string', 'max:32'],
            'name' => ['required', 'string', 'max:128'],
            'model' => ['required', 'string', 'max:256'],
            'description' => ['required', 'string', 'max:1024', 'no_script_tags', 'safe_html'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:100000'],
            'photo' => ['nullable', 'string', 'max:255'],
            'main_photo' => ['nullable', 'image', 'max:2048'], // 2MB max size
            'additional_photos.*' => ['nullable', 'image', 'max:2048'], // Each additional photo 2MB max
        ]);

        $product = $product ?? new Product();
        
        // Fix mass assignment by explicitly setting fields
        $product->code = $request->input('code');
        $product->name = $request->input('name');
        $product->model = $request->input('model');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->stock_quantity = $request->input('stock_quantity');
        
        // For backward compatibility
        if ($request->has('photo')) {
            $product->photo = $request->input('photo');
        }
        
        // Handle main photo upload
        if ($request->hasFile('main_photo') && $request->file('main_photo')->isValid()) {
            $mainPhotoName = time() . '_' . $request->file('main_photo')->getClientOriginalName();
            $request->file('main_photo')->storeAs('public/products', $mainPhotoName);
            $product->main_photo = $mainPhotoName;
        }
        
        // Handle additional photos
        if ($request->hasFile('additional_photos')) {
            $additionalPhotos = [];
            
            foreach ($request->file('additional_photos') as $photo) {
                if ($photo->isValid()) {
                    $photoName = time() . '_' . uniqid() . '_' . $photo->getClientOriginalName();
                    $photo->storeAs('public/products', $photoName);
                    $additionalPhotos[] = $photoName;
                }
            }
            
            // If we have existing photos, merge with new ones
            if ($product->additional_photos && is_array($product->additional_photos)) {
                $additionalPhotos = array_merge($product->additional_photos, $additionalPhotos);
            }
            
            $product->additional_photos = $additionalPhotos;
        }
        
        $product->save();

        return redirect()->route('products_list')->with('success', 'Product saved successfully');
    }

	public function delete(Request $request, Product $product) {
		// Check if user has permission to delete products
		if(!auth()->user()->hasPermissionTo('delete_products')) abort(403, 'You do not have permission to delete products.');

		$product->delete();

		return redirect()->route('products_list');
	}

	/**
	 * Show a single product's details
	 */
	public function show(Product $product) {
		return view('products.show', compact('product'));
	}
}
