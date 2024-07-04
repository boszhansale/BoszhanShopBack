<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Imports\ProductPriceImport;
use App\Models\Category;
use App\Models\Counteragent;
use App\Models\PriceType;
use App\Models\Product;
use App\Models\ProductBarcode;
use App\Models\ProductCounteragentPrice;
use App\Models\ProductImage;
use App\Models\ProductPriceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();

        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('brand_id')->where('enabled', 1)->with('brand')->get();
        $priceTypes = PriceType::where('id',3)->get();
        $counteragents = Counteragent::orderBy('name')->get();

        return view('admin.product.create', compact('categories', 'priceTypes', 'counteragents'));
    }

    public function show(Product $product)
    {
        return view('admin.product.show', compact('product'));
    }

    public function store(ProductStoreRequest $request)
    {
        $product = Product::create($request->validated());

//        foreach ($request->get('counteragent_prices') as $item) {
//            if (!isset($item['price'])) {
//                continue;
//            }
//            if ($item['price'] == 0) {
//                continue;
//            }
//            ProductCounteragentPrice::updateOrCreate(
//                [
//                    'product_id' => $product->id,
//                    'counteragent_id' => $item['counteragent_id'],
//                ],
//                [
//                    'product_id' => $product->id,
//                    'counteragent_id' => $item['counteragent_id'],
//                    'price' => $item['price'],
//                ]
//            );
//        }

        foreach ($request->get('price_types') as $item) {
            ProductPriceType::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'price_type_id' => $item['price_type_id'],
                ],
                [
                    'product_id' => $product->id,
                    'price_type_id' => $item['price_type_id'],
                    'price' => $item['price'],
                ]
            );
        }

        return redirect()->route('admin.product.index');
    }

    public function edit(Product $product)
    {
        $priceTypes = PriceType::where('id',3)->get();
        $categories = Category::orderBy('brand_id')
            ->where('enabled', 1)
            ->with('brand')
            ->get();
        $counteragents = Counteragent::orderBy('name')->get();

        return view('admin.product.edit', compact('product', 'priceTypes', 'categories', 'counteragents'));
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->category_id = $request->get('category_id');
        $product->name = $request->get('name');
        $product->article = $request->get('article');
        $product->id_1c = $request->get('id_1c');
        $product->measure = $request->get('measure');
        $product->barcode = $request->get('barcode');
        $product->remainder = $request->get('remainder');
        $product->discount = $request->get('discount') ?? 0;
        $product->hit = $request->has('hit');
        $product->new = $request->has('new');
        $product->action = $request->has('action');
        $product->purchase = $request->has('purchase');
        $product->return = $request->has('return');
        $product->discount_5 = $request->has('discount_5');
        $product->discount_10 = $request->has('discount_10');
        $product->discount_15 = $request->has('discount_15');
        $product->discount_20 = $request->has('discount_20');

        $product->save();

        foreach ($request->get('price_types') as $item) {
            ProductPriceType::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'price_type_id' => $item['price_type_id'],
                ],
                [
                    'product_id' => $product->id,
                    'price_type_id' => $item['price_type_id'],
                    'price' => $item['price'],
                ]
            );
        }

//        foreach ($request->get('price_types') as $item) {
//            ProductPriceType::updateOrCreate(
//                [
//                    'product_id' => $product->id,
//                    'price_type_id' => $item['price_type_id'],
//                ],
//                [
//                    'product_id' => $product->id,
//                    'price_type_id' => $item['price_type_id'],
//                    'price' => $item['price'],
//                ]
//            );
//        }
//        $product->counteragentPrices()->where('price', 0)->delete();
//        if ($request->has('counteragent_prices')) {
//            foreach ($request->get('counteragent_prices') as $item) {
//                if (!isset($item['price'])) {
//                    continue;
//                }
//                if ($item['price'] == 0) {
//                    continue;
//                }
//                ProductCounteragentPrice::updateOrCreate(
//                    [
//                        'product_id' => $product->id,
//                        'counteragent_id' => $item['counteragent_id'],
//                    ],
//                    [
//                        'product_id' => $product->id,
//                        'counteragent_id' => $item['counteragent_id'],
//                        'price' => $item['price'],
//                    ]
//                );
//            }
//        }
//        if ($request->file('images')) {
//            foreach ($request->file('images') as $image) {
//                $productImage = new ProductImage();
//                $productImage->product_id = $product->id;
//                $productImage->name = $image->getClientOriginalName();
//                $productImage->path = $image;
//                $productImage->save();
//
//            }
//        }

        return redirect()->route('admin.product.index');
    }

    public function delete(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $product->delete();

        return redirect()->back();
    }

    public function deleteImage(ProductImage $productImage)
    {
        Storage::disk('public')->delete($productImage->getRawOriginal('path'));

        $productImage->delete();

        return redirect()->back();
    }

    public function counteragentPriceStore(Request $request, Product $product)
    {
        $product->counteragentPrices()->updateOrCreate([
            'counteragent_id' => $request->get('counteragent_id'),
        ], [
            'counteragent_id' => $request->get('counteragent_id'),
            'price' => $request->get('price'),
        ]);

        return back();
    }

    public function counteragentPriceDelete(ProductCounteragentPrice $counteragentPrice)
    {
        $counteragentPrice->delete();

        return back();
    }

    public function barcodeCreate(Request $request, Product $product)
    {
        $product->barcodes()->create(
            ['barcode' => $request->get('barcode')]
        );

        return redirect()->back();
    }

    public function barcodeDelete(ProductBarcode $productBarcode)
    {
        $productBarcode->delete();
        return redirect()->back();
    }

    public function priceParse(Request $request)
    {
        if ($request->method() == 'GET') {
            return view('admin.product.priceParse');
        }
        $type = PriceType::findOrFail($request->get('price_type_id'));

        Excel::import(new ProductPriceImport($type), $request->file('price'));

        return redirect()->back()->with('успешно');
    }

    public function info(Request $request)
    {
        return view('admin.product.info');
    }
}
