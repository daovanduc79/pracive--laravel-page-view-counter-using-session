<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;

class ProductController extends Controller
{
    protected $products;

    public function __construct(Product $products)
    {
        $this->products = $products;
    }

    public function index()
    {
        $products = $this->products->all();
        return view('index', compact('products'));
    }

    public function show($id)
    {

        if (!session('productKey'.$id)) {
            $product = $this->products->findOrFail($id);
            $product->view_count++;
            $product->save();
            session()->push('productKey'.$id,true);
        }
        $product = $this->products->findOrFail($id);
        return view('view', compact('product'));
    }
}
