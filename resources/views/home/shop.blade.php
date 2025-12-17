@extends("layouts.ewatch")
@section('title', $title ?? 'Shop')
@section("content")

    <div class="section-breadCrumbs shop-breadCrumbs">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadCrumbs-content">
                        <div class="page-name">
                            <h2>Shop</h2>
                        </div>
                        <div class="breadcrumbs-link">
                            <ul class="d-flex justify-content-center">
                                <li><a href="index.html">Home </a><i class="bx bx-chevrons-right"></i></li>
                                <li><a class="active" aria-current="page">Shop</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="product-listing-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="area-title">
                        <h3 class="title-2"> Vew Our All Products</h3>
                    </div>
                </div>
                <div class="col-lg-3">
                    <form class="product-shorting" action="https://demo.egenslab.com/html/ewatch/shop-page.html" method="post">
                        <div class="form-group gs_form_control">
                            <select class="form-control nice_selection">
                                <option>Sort by latest</option>
                                <option>Default</option>
                                <option>Popularity</option>
                                <option>Price: low to high</option>
                                <option>Price: high to low</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="product-items">

                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-right">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img1.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding rebonNew">
                                        New
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Rolex SA</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Rolex</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-right">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img2.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Hublot</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Hublot</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-right">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img3.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding">
                                        Sale
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Chopard</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Chopard</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-right">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img4.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding rebon-offer">
                                        30% off
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Blancpain </a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Blancpain</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-left">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img5.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding">
                                        Sale
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Cartier </a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Cartier</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-left">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img3.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Ulysse Nardin</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Ulysse Nardin</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-left">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img2.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Piaget SA</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Piaget</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-left">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img1.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding rebonNew">
                                        new
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Rolex</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Rolex</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-right">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img1.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding rebonNew">
                                        New
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Rolex SA</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Rolex</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-right">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img2.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Hublot</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Hublot</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-right">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img3.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding">
                                        Sale
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Chopard</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Chopard</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-right">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img4.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding rebon-offer">
                                        30% off
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Blancpain </a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Blancpain</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-left">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img5.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding">
                                        Sale
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Cartier </a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Cartier</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-left">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img3.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Ulysse Nardin</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Ulysse Nardin</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-left">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img2.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Piaget SA</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Piaget</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-left">
                            <div class="product-img m-auto">
                                <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img1.png' ) }}" alt="img"></a>
                                <a href="{{ route('home.product', 'BE45VGRT') }}" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding rebonNew">
                                        new
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="{{ route('home.product', 'BE45VGRT') }}">Laxer rex flet Rolex</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="{{ route('home.product', 'BE45VGRT') }}">Rolex</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row justify-content-center">
                    <div>
                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="#"><i class="bx bx-chevrons-left"></i></a></li>
                            <li class="page-item"><a class="page-link active" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#"><i class="bx bx-chevrons-right"></i></a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection

<script>
    {{--const DELETE_URL = "{{ route('admin.user.destroy') }}";--}}
</script>

@section("pagescript")
{{--    <script src="{{ asset('js/admin/user/index.js?t='.config('constants.app_version') )}}"></script>--}}
@endsection


