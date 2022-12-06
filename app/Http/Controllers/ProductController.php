<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\productimg;
use App\Models\propertiesproduct;
use Illuminate\Http\Request;
use App\Http\Resources\ProductConllection;
use App\Http\Resources\Products;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Container\Container;
use Illuminate\Pagination\Paginator as PaginationPaginator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //
    public function index(Request $request)
    {

        // if ($request->color || $request->size) {
        //     if ($request->color && $request->size) {

        //         $arrFilter = array_merge($request->color, $request->size);
        //     } else {
        //         if ($request->color) {


        //             $arrFilter = $request->color;
        //         } else {

        //             $arrFilter = $request->size;
        //         }
        //     }
        //     $filter = propertiesproduct::all();


        //     // return $arrFilter;
        //     $filter = $filter->whereIn('value', $arrFilter);
        //     $arr = [];
        //     foreach ($filter as $item) {

        //         $arr = $item->products;
        //     }
        //     $product = collect($arr);
        // } else {
        //     $product = product::all();
        // }
        // if ($request->category) {
        //     $product = $product->whereIn('categoryName', $request->category);
        // }
        // if ($request->time) {

        //     if ($request->time == 'ASC') {

        //         $product =  $product->sortBy('created_at');
        //     } else {
        //         $product =  $product->sortByDesc('created_at');
        //     }
        //     return $product;
        // }        
        // $product = $this->paginate($product, $perPage = 20, $page = null, $options = []);
        // return $product;

        if ($request->color || $request->size) {
            if ($request->color && $request->size) {

                $arrFilter = array_merge($request->color, $request->size);
            } else {
                if ($request->color) {


                    $arrFilter = $request->color;
                } else {

                    $arrFilter = $request->size;
                }
            }
            // return $arrFilter;
            $product = product::whereHas('propertyproduct', function ($query) use ($arrFilter) {
                $query->whereIn('value', $arrFilter);
            })->get();
        } else {

            $product = product::all();
        }
        if ($request->category) {
            $product = $product->whereIn('categoryName', $request->category);
        }


        $product = $this->paginate($product, 20);


        return new ProductConllection($product);
    }
    public function fliter(Request $request)
    {

        // if ($request->color || $request->size) {
        //     if ($request->color && $request->size) {

        //         $arrFilter = array_merge($request->color, $request->size);
        //     } else {
        //         if ($request->color) {


        //             $arrFilter = $request->color;
        //         } else {

        //             $arrFilter = $request->size;
        //         }
        //     }
        //     // return $arrFilter;
        //     $product = product::whereHas('propertyproduct', function ($query) use ($arrFilter) {
        //         $query->whereIn('value', $arrFilter);
        //     })->get();
        // } else {

        //     $product = product::all();
        // }
        // if ($request->category) {
        //     $product = $product->whereIn('categoryName', $request->category);
        // }
        // $product = $this->paginate($product, 2);
        // return new ProductConllection($product);
        $product = DB::table('products')->inRandomOrder()->limit(20)->get();
        // return new ProductConllection($product);
        return $product;
    }

    public static function paginate(Collection $results, $pageSize)
    {
        $page = Paginator::resolveCurrentPage('page');

        $total = $results->count();

        return self::paginator($results->forPage($page, $pageSize), $total, $pageSize, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    protected static function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items',
            'total',
            'perPage',
            'currentPage',
            'options'
        ));
    }





    // public function paginate($items, $perPage = 15, $page = null, $options = [])
    // {
    //     $page = $page ?: (PaginationPaginator::resolveCurrentPage() ?: 1);

    //     $items = $items instanceof Collection ? $items : Collection::make($items);

    //     return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    // }
    public function getId($id)
    {
        $size = propertiesproduct::where('productId', $id);

        $size = $size->where('key', '=', 'size')->get();

        $color = propertiesproduct::where('productId', $id);
        $color = $color->where('key', '=', 'color')->get();
        $img = product::find($id)->productimg;
        // dd($size);
        // dd($property);
        $product =  product::find($id);
        return [
            "color" => $color,
            'size' => $size,
            'img' => $img,
            "product" => $product,
        ];
    }
    public function add(Request $request)
    {


        $product = new product();

        $product->name = $request->name;
        $product->description = $request->description;
        $product->avatarOne = $request->avatarOne;
        $product->avatarTwo = $request->avatarTwo;
        $product->price = $request->price;
        $product->categoryName = $request->categoryName;

        $product->save();

        if (!empty($request->color)) {
            $color = $request->color;
            if (is_array($color)) {
                foreach ($request->color as $itemColor) {

                    $product->propertyproduct()->create([
                        'key' => 'color',
                        'value' => $itemColor
                    ]);
                }
            } else {
                $product->propertyproduct()->create([
                    'key' => 'color',
                    'value' => $color
                ]);
            }
        }


        if (!empty($request->size)) {
            $size = $request->size;
            if (is_array($size)) {

                foreach ($request->size as $itemSize) {

                    $product->propertyproduct()->create([
                        'key' => 'size',
                        'value' => $itemSize
                    ]);
                }
            } else {
                $product->propertyproduct()->create([
                    'key' => 'size',
                    'value' => $size
                ]);
            }
        }

        if (!empty($request->img)) {

            foreach ($request->img as $itemimg) {

                $product->productimg()->create([
                    'name' => $itemimg,
                    'path' => $itemimg
                ]);
            }
        }
        return $product;
    }

    public function edit(Request $request, $id)
    {

        $product = product::find($id);

        if ($request->name) {

            $product->name = $request->name;
        }
        if ($request->description) {

            $product->description = $request->description;
        }
        if ($request->price) {

            $product->price = $request->price;
        }
        if ($request->categoryName) {

            $product->categoryName = $request->categoryName;
        }
        if ($request->avatarOne) {

            $product->avatarOne = $request->avatarOne;
        }
        if ($request->avatarTwo) {

            $product->avatarTwo = $request->avatarTwo;
        }
        $product->save();


        if (!empty($request->img)) {

            foreach ($request->img as $itemimg) {

                $product->productimg()->create([
                    'name' => $itemimg,
                    'path' => $itemimg
                ]);
            }
        }

        if ($request->color) {
            $product->propertyproduct()->where('key', 'color')->delete();
            $color = $request->color;
            if (is_array($color)) {

                foreach ($request->color as $itemColor) {

                    $product->propertyproduct()->create([
                        'key' => 'color',
                        'value' => $itemColor
                    ]);
                }
            } else {
                $product->propertyproduct()->create([
                    'key' => 'color',
                    'value' => $color
                ]);
            }
        }
        if (!empty($request->size)) {
            $product->propertyproduct()->where('key', 'size')->delete();
            $size = $request->size;
            if (is_array($size)) {

                foreach ($request->size as $itemSize) {

                    $product->propertyproduct()->create([
                        'key' => 'size',
                        'value' => $itemSize
                    ]);
                }
            } else {
                $product->propertyproduct()->create([
                    'key' => 'size',
                    'value' => $size
                ]);
            }
        }

        return $product;
    }
    public function delete($id)
    {
        $deleteImg = productimg::where('productId', '=', $id)->delete();
        $deleteproperty = propertiesproduct::where('productId', '=', $id)->delete();

        return product::destroy($id);
    }
}
