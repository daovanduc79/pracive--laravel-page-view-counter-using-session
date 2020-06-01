[Thực hành] Đếm só lượt view trang
Mục tiêu
Luyện tập sử dụng Session trong Laravel.

Mô tả ứng dụng
Trong phần này, chúng ta sẽ xây dựng một trang web hiển thị 1 danh sách các sản phẩm. Sẽ sử dụng session để thực hiện việc đếm số lượng lượt xem của từng sản phẩm.

Các bước thực hiện
Bước 1: Tạo môi trường
Tạo project Laravel:

composer create-project --prefer-dist laravel/laravel laravel-page-view-counter-using-session
Tạo cơ sở dữ liệu: Đăng nhập vào phpmyadmin tạo một CSDL với tên page-view-counter

Sửa tập tin .env cấu hình kết nối CSDL:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=page-view-counter
DB_USERNAME=root
DB_PASSWORD=password
Bước 2: Tạo bảng products bằng migration và sử dụng seeder để tạo dữ liệu mẫu
Tạo Product Model: Tạo migration đi kèm với Model ta sử dụng cờ -migration hoặc -m

php artisan make:model Product -migration

Thêm các trường vào trong bản Products:

Mở tệp Product migration từ database->migrations->create_products_table.php

public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->string('description');
        $table->float('price');
        $table->integer('view_count');
        $table->timestamps();
    });
}
Tạo seeder:

php artisan make:seeder ProductsTableSeeder
Thêm dữ liệu mẫu vào trong tệp seeder vừa tạo trong thư mục: database->seeds->ProductsTableSeeder.php

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = new \App\Product();
        $product->name = 'Sản phẩm 001';
        $product->description = 'Sản phẩm có mã số 001.';
        $product->price = 1.5;
        $product->view_count = 0;
        $product->save();

        $product = new \App\Product();
        $product->name = 'Sản phẩm 002';
        $product->description = 'Sản phẩm có mã số 002.';
        $product->price = 2.5;
        $product->view_count = 0;
        $product->save();

        $product = new \App\Product();
        $product->name = 'Sản phẩm 003';
        $product->description = 'Sản phẩm có mã số 003.';
        $product->price = 1.5;
        $product->view_count = 0;
        $product->save();
    }
}
Mặc định Seeder trong Laravel sẽ tạo các dữ liệu mẫu nào được chỉ định trong class DatabaseSeeder. Vì vậy ta cần thêm ProductTableSeeder vào trong hàm run() của class DatabaseSeeder

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(ProductsTableSeeder::class);
    }
}
Chạy migrate và seeder: Sử dụng --seed trong lệnh migrate để tạo kèm dữ liệu mẫu.

php artisan migrate –seed
Kiểm tra trong Database.

Bước 3: Tạo Controller và phương thức index()
Ở bước này chúng ta sẽ tạo ProductController trong đó có phương thức index() và phương thức show()

php artisan make:controller ProductController
class ProductController extends Controller
{
    public function index()
    {
        //
    }
    
    public function show($id)
    {
        //
    }
} 
Trong đó:

Phương thức index() sẽ sử dụng Eloquent all() để lấy toàn bộ các sản phẩm từ database và trả về view index.blade.php

public function index()
{
    $products = Product::all();
    return view('index');
} 
Bước 4: Tạo router
Tạo một router có tên index để chuyển hướng người dùng đến trang hiển thị danh sách của sản phẩm khi người dùng bấn vào Danh sách sản phẩm từ trng chủ.

Route::get('products', 'ProductController@index')->name('index');
Tạo một router có tên show để chuyển hướng người dùng đến trang xem chi tiết của sản phẩm khi người dùng bấm vào Xem từ trang danh sách.

Route::get('products/{id}', 'ProductController@show')->name('show');
Bước 5: Tạo view hiển thị danh sách các sản phẩm
Tạo layout cho các view: Tại thư mục resources->views tạo thư mục layouts trong đó chứa view layout master.blade.php

<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="content">
        @yield('content')
    </div>
</div>
<!-- Booostrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
</body>
</html>
Chỉnh sửa lại view Welcome: trong đó có 1 thẻ a có href trỏ đến route có tên là index

@extends('layouts.master')
@section('content')
    <div class="title m-b-md">
        Sử dụng Session đếm số lượng lượt xem
    </div>

    <div class="links">
        <a href="{{ route('index') }}">Danh sách sản phẩm</a>
    </div>
@endsection


 

Tạo view index để hiển thị danh sách tất cả các sản phẩm: Sử dụng Card của boootstrap để hiển thị sản phẩm.

Trong đó dưới mỗi sản phẩm sẽ có 1 nút Xem, khi người dùng bấm vào sẽ di chuyển sang trang hiển thị chi tiết sản phẩm đó và tăng lượt xem lên 1.

@extends('layouts.master')
@section('content')
    <div class="title m-b-md">
        Danh sách sản phẩm
    </div>

    <div class="row">

        <!-- Kiểm tra biến $products được truyền sang từ ProductController -->
        <!-- Nếu biến $products không tồn tại hoặc có số lượng băng 0 (không có sản phẩm nào) thì hiển thị thông báo -->
        @if(!isset($products) || count($products) === 0)
            <p class="text-danger">Không có sản phẩn nào.</p>
        @else

            <!-- Nếu biến $products tồn tại thì hiển thị sản phẩm -->
            @foreach($products as $key => $product)
                <div class="col-4">
                    <div class="card text-left" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->description }}</p>
                            <p class="card-text text-dark">${{ $product->price }}</p>
                            <p class="card-text text-danger">Số lượt xem: {{ $product->view_count }}</p>

                            <!-- Nút XEM chuyển hướng người dùng sang trang chi tiết -->
                            <a href="{{ route('show', $product->id) }}" class="btn btn-primary">Xem</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
 



Bước 6: Tạo chức năng xem chi tiết và sử dụng Session để tăng lượng xem của sản phẩm
Tại phương thức show() trong ProductController chúng ta sẽ tăng lượng xem của sản phẩm mỗi khi người dùng vào xem chi tiết sản phẩm. Để tránh lại việc người dùng refresh lại trình duyệt để tăng lượng view liên tục chúng ta sẽ sử dụng Session để kiểm tra, đảm bảo lượt view của sản phẩm chỉ tăng một lần trong lần xem đầu tiên trong một phiên cho đến khi người dùng tắt trình duyệt đồng nghĩa với xóa toàn bộ session, kết thúc một phiên.

Trong 1 phiên, mỗi khi người dùng vào xem chi tiết một sản phẩm thì sẽ tạo ra 1 productKey, sau đó sẽ kiểm tra trong Session có productKey có tồn tại hay không.

Nếu không tồn tại đồng nghĩa với việc người dùng lần đầu xem chi tiết sản phẩm đó, tăng lượt view lên 1 đơn vị, và tạo 1 session với key là productKey.

public function show($id)
{
    $productKey = 'product_' . $id;

    // Kiểm tra Session của sản phẩm có tồn tại hay không.
    // Nếu không tồn tại, sẽ tự động tăng trường view_count lên 1 đồng thời tạo session lưu trữ key sản phẩm.
    if (!Session::has($productKey)) {
        Product::where('id', $id)->increment('view_count');
        Session::put($productKey, 1);
    }

    // Sử dụng Eloquent để lấy ra sản phẩm theo id
    $product = Product::find($id);

    // Trả về view
    return view('show', compact(['product']));
}
Lưu ý: Đảm bảo đã cấu hình cho việc xóa session khi tắt trình duyệt. Trong thư mục config/session.php kiểm tra mục ‘expire_on_close’ => true 

Bước 7: Tạo view hiển thị chi tiết sản phẩm
@extends('layouts.master')
@section('content')
    <div class="title m-b-md">
        Chi tiết sản phẩm
    </div>

    <div class="row">

        <!-- Kiểm tra biến $product được truyền sang từ ProductController -->
        <!-- Nếu biến $products không tồn tại thì hiển thị thông báo -->
        @if(!isset($product))
            <p class="text-danger">Không có sản phẩn nào.</p>
        @else

            <!-- Nếu biến $product tồn tại thì hiển thị sản phẩm -->
                <div class="col-12">
                    <div class="card text-left" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->description }}</p>
                            <p class="card-text text-dark">${{ $product->price }}</p>
                            <p class="card-text text-danger">Số lượt xem: {{ $product->view_count }}</p>

                            <!-- Nút XEM chuyển hướng người dùng quay lại trang danh sách sản phẩm -->
                            <a href="{{ route('index') }}" class="btn btn-primary">< Quay lại </a>
                        </div>
                    </div>
                </div>
        @endif
    </div>
@endsection
 



 

Chạy ứng dụng để xem kết quả.
