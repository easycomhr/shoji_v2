@extends("layouts.ewatch")
@section('title', $title ?? 'Shop')
@section("content")


    <div class="section-breadCrumbs contact-breadCrumbs">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadCrumbs-content">
                        <div class="page-name">
                            <h2>Product Details</h2>
                        </div>
                        <div class="breadcrumbs-link">
                            <ul class="d-flex justify-content-center">
                                <li><a href="index.html">Home </a><i class="bx bx-chevrons-right"></i></li>
                                <li><a href="shop-page.html">Shop </a><i class="bx bx-chevrons-right"></i></li>
                                <li><a class="active" aria-current="page">Product Details</a> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="product-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="single-product-image text-center">
                        <div id="custCarousel" class="carousel slide" data-ride="carousel">

                            <div class="carousel-inner">
                                <div class="carousel-item"> <img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-02.png' ) }}" alt="Slider 01"> </div>
                                <div class="carousel-item"> <img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-03.png' ) }}" alt="Slider 02"> </div>
                                <div class="carousel-item"> <img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-04.png' ) }}" alt="Slider 03"> </div>
                                <div class="carousel-item active"> <img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-05.png' ) }}" alt="Slider 04"> </div>
                            </div>
                            <a class="carousel-control-prev" href="#custCarousel" data-slide="prev"> <span class="carousel-control-prev-icon"></span> </a> <a class="carousel-control-next" href="#custCarousel" data-slide="next"> <span class="carousel-control-next-icon"></span> </a>
                            <ol class="carousel-indicators list-inline">
                                <li class="list-inline-item"> <a id="carousel-selector-0" class="selected" data-slide-to="0" data-target="#custCarousel"> <img src="{{  asset('assets/templates/ewatch/assets/images/product-02.png' ) }}" alt="Slider 01" class="img-fluid"> </a> </li>
                                <li class="list-inline-item"> <a id="carousel-selector-1" data-slide-to="1" data-target="#custCarousel"> <img src="{{  asset('assets/templates/ewatch/assets/images/product-03.png' ) }}" alt="Slider 02" class="img-fluid"> </a> </li>
                                <li class="list-inline-item"> <a id="carousel-selector-2" data-slide-to="2" data-target="#custCarousel"> <img src="{{  asset('assets/templates/ewatch/assets/images/product-04.png' ) }}" alt="Slider 03" class="img-fluid"> </a> </li>
                                <li class="list-inline-item active"> <a id="carousel-selector-3" data-slide-to="3" data-target="#custCarousel"> <img src="{{  asset('assets/templates/ewatch/assets/images/product-05.png' ) }}" alt="Slider 04" class="img-fluid"> </a> </li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="single-product-details">
                        <div class="brand">SKU: BE45VGRT</div>
                        <div class="star-rating">
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <p><a href="{{ route('home.product', 'BE45VGRT') }}">3 reviews</a></p>
                        </div>
                        <div class="product-name"><h2>Laxer rex flet Ulysse Nardin</h2></div>
                        <div class="price">
                            <div class="d-flex">
                                <div class="base-price">$32.00</div>
                                <div class="sale-price">$25.00</div>
                            </div>
                            <div class="off-percent">30% off</div>
                        </div>
                        <div class="product-description-title"><h3>Product Description</h3></div>
                        <p class="product-short-description">Maecenas ipsum sapien, imperdiet sodales molestie finibus, scelerisque eu dolor. In facilisis ac leo eget feugiat. Sed eu diam nisl. Nunc nisi est, lacinia quis scelerisque et, commodo tincidunt nunc. Quisque egestas arcu enim, id consectetur sem dapibus a. Quisque euismod pellentesque eros sit amet cursus. Quisque interdum venenatis placerat.</p>
                        <div class="color-select">
                            <h5 class="widget-title">Color</h5>
                            <div class>
                                <input type="radio" name="color" id="red" value="red">
                                <label for="red"><span class="red"></span></label>
                                <input type="radio" name="color" id="yellow">
                                <label for="yellow"><span class="yellow"></span></label>
                                <input type="radio" name="color" id="olive">
                                <label for="olive"><span class="olive"></span></label>
                                <input type="radio" name="color" id="orange">
                                <label for="orange"><span class="orange"></span></label>
                                <input type="radio" name="color" id="purple">
                                <label for="purple"><span class="purple"></span></label>
                                <input type="radio" name="color" id="pink">
                                <label for="pink"><span class="pink"></span></label>
                            </div>
                        </div>
                        <div class="size-select">
                            <h5 class="widget-title">Size</h5>
                            <div class>
                                <input type="radio" name="size" id="small" value="small">
                                <label for="small"><span>10</span></label>
                                <input type="radio" name="size" id="medium" value="medium">
                                <label for="medium"><span>12</span></label>
                                <input type="radio" name="size" id="large" value="large">
                                <label for="large"><span>14</span></label>
                                <input type="radio" name="size" id="biglarge" value="biglarge">
                                <label for="biglarge"><span>16</span></label>
                                <input type="radio" name="size" id="extralarge" value="extralarge">
                                <label for="extralarge"><span>18</span></label>
                            </div>
                        </div>
                        <div class="quantity">
                            <h5 class="quantity-title">Quantity</h5>
                            <form id="quantity-form" method="POST" action="#">
                                <div class="number">
                                    <span class="minus"><i class="bx bx-minus"></i></span>
                                    <input type="text" value="1">
                                    <span class="plus"><i class="bx bx-plus"></i></span>
                                </div>
                                <button class="btn btn-primary"><i class="bx bx-cart"></i> Add To Cart</button>
                                <a href="#" class="add-to-favourites"><i class="bx bx-heart"></i> Add To Love</a>
                            </form>
                        </div>
                        <div class="payment-method">
                            <h5 class="payment-method-title">Secure and Safe Checkout</h5>
                            <img src="{{  asset('assets/templates/ewatch/assets/images/payment_method.png' ) }}" alt="Payment Method">
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-details-tab">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-description-tab" data-toggle="tab" href="#nav-description" role="tab" aria-controls="nav-description" aria-selected="true">Description</a>
                        <a class="nav-item nav-link" id="nav-specification-tab" data-toggle="tab" href="#nav-specification" role="tab" aria-controls="nav-specification" aria-selected="false">Specification</a>
                        <a class="nav-item nav-link" id="nav-materials-tab" data-toggle="tab" href="#nav-materials" role="tab" aria-controls="nav-materials" aria-selected="false">Materials</a>
                        <a class="nav-item nav-link" id="nav-reviews-tab" data-toggle="tab" href="#nav-reviews" role="tab" aria-controls="nav-reviews" aria-selected="false">Reviews</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-description" role="tabpanel" aria-labelledby="nav-description-tab">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</p>
                        <ul>
                            <li>Duis aute irure dolor in reprehenderit in sed.</li>
                            <li>Ut enim ad minim veniam, quis nostrud exercitation.</li>
                            <li>Tempor incididunt ut labore et dolore.</li>
                            <li>Magnam aliquam quaerat voluptatem.</li>
                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.</li>
                            <li>Incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="nav-specification" role="tabpanel" aria-labelledby="nav-specification-tab">
                        <div class="table-responsive">
                            <table>
                                <tbody>
                                <tr>
                                    <td class="title width1"><strong>Name</strong></td>
                                    <td>Laxer rex flet Ulysse Nardin</td>
                                </tr>
                                <tr>
                                    <td class="title width1"><strong>SKU</strong></td>
                                    <td>BE45VGRT</td>
                                </tr>
                                <tr>
                                    <td class="title width1"><strong>Models</strong></td>
                                    <td>FX 829 v1</td>
                                </tr>
                                <tr>
                                    <td class="title width1"><strong>Categories</strong></td>
                                    <td>Jute Busked</td>
                                </tr>
                                <tr>
                                    <td class="title width1"><strong>Size</strong></td>
                                    <td>60’’ x 40’’</td>
                                </tr>
                                <tr>
                                    <td class="title width1"><strong>Brand</strong></td>
                                    <td>Individual Collections</td>
                                </tr>
                                <tr>
                                    <td class="title width1"><strong>Color</strong></td>
                                    <td>Red</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-materials" role="tabpanel" aria-labelledby="nav-materials-tab">
                        <div class="table-responsive">
                            <table>
                                <tbody>
                                <tr>
                                    <td class="title width1"><strong>Name</strong></td>
                                    <td>Laxer rex flet Ulysse Nardin</td>
                                </tr>
                                <tr>
                                    <td class="title width1"><strong>SKU</strong></td>
                                    <td>BE45VGRT</td>
                                </tr>
                                <tr>
                                    <td class="title width1"><strong>Models</strong></td>
                                    <td>FX 829 v1</td>
                                </tr>
                                <tr>
                                    <td class="title width1"><strong>Categories</strong></td>
                                    <td>Jute Busked</td>
                                </tr>
                                <tr>
                                    <td class="title width1"><strong>Size</strong></td>
                                    <td>60’’ x 40’’</td>
                                </tr>
                                <tr>
                                    <td class="title width1"><strong>Brand</strong></td>
                                    <td>Individual Collections</td>
                                </tr>
                                <tr>
                                    <td class="title width1"><strong>Color</strong></td>
                                    <td>Red</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-reviews" role="tabpanel" aria-labelledby="nav-reviews-tab">
                        <div class="review-wrapper">
                            @include('elements.zalo_widget_comment')
                        </div>
                    </div>
                </div>
            </div>
            <div class="related-product">
                <h3>You Might Also Like</h3>
                <div class="releted-product-carousel owl-carousel owl-theme">
                    <div class="gs-product-item product-default">
                        <div class="product-img m-auto">
                            <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img1.png' ) }}" alt="img"></a>
                            <a href="#" class="buy-now btn animate_flipInX">Buy Now</a>
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
                    <div class="gs-product-item product-default">
                        <div class="product-img m-auto">
                            <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img2.png' ) }}" alt="img"></a>
                            <a href="#" class="buy-now btn animate_flipInX">Buy Now</a>
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
                    <div class="gs-product-item product-default">
                        <div class="product-img m-auto">
                            <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img3.png' ) }}" alt="img"></a>
                            <a href="#" class="buy-now btn animate_flipInX">Buy Now</a>
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
                    <div class="gs-product-item product-default">
                        <div class="product-img m-auto">
                            <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img4.png' ) }}" alt="img"></a>
                            <a href="#" class="buy-now btn animate_flipInX">Buy Now</a>
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
                    <div class="gs-product-item product-default">
                        <div class="product-img m-auto">
                            <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img5.png' ) }}" alt="img"></a>
                            <a href="#" class="buy-now btn animate_flipInX">Buy Now</a>
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
                    <div class="gs-product-item product-default">
                        <div class="product-img m-auto">
                            <a href="{{ route('home.product', 'BE45VGRT') }}"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img3.png' ) }}" alt="img"></a>
                            <a href="#" class="buy-now btn animate_flipInX">Buy Now</a>
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


