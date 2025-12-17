@extends("layouts.ewatch")
@section('title', $title ?? 'Watchshop')
@section("content")


    <section class="about-section item">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5 col-md-12 col-sm-12">
                    <div class="about-text" data-aos="fade-up">
                        <h5 class="section-title">ABOUT US</h5>
                        <h2 class="section-heading title2">GET VERIETY FROM US</h2>
                        <p class="title-description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting
                            industry. Lorem Ipsum has been the industry's standard dummy text ever
                            since the 1500s, when an unknown printer took a galley of type and
                            scrambled it to make a type specimen book.
                        </p>
                        <a href="#" class="btn sectionA-btn ">See More</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12" data-aos="fade-up">
                    <div class="about-boxs">
                        <div class="box-item">
                            <img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/icon/icon1.svg' ) }}" alt="icon">
                            <h3 class="box-title">ROUND DIAL</h3>
                            <p class="default-content">Lorem Ipsum is simply dummy text of the
                                printing and typesetting industry. Lorem Ipsum
                                has been the industry'
                            </p>
                        </div>
                    </div>
                    <div class="about-boxs" data-aos="fade-up">
                        <div class="box-item box-item2">
                            <img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/icon/icon3.svg' ) }}" alt="icon">
                            <h3 class="box-title">ALRM DIALALRM DIAL</h3>
                            <p class="default-content">Lorem Ipsum is simply dummy text of the
                                printing and typesetting industry. Lorem Ipsum
                                has been the industry'
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12" data-aos="fade-up">
                    <div class="about-boxs">
                        <div class="box-item">
                            <img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/icon/icon2.svg' ) }}" alt="icon">
                            <h3 class="box-title">FORMAL DIAL</h3>
                            <p class="default-content">Lorem Ipsum is simply dummy text of the
                                printing and typesetting industry. Lorem Ipsum
                                has been the industry'
                            </p>
                        </div>
                    </div>
                    <div class="about-boxs" data-aos="fade-up">
                        <div class="box-item box-item2">
                            <img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/icon/icon4.svg' ) }}" alt="icon">
                            <h3 class="box-title">CAUSAL DIAL</h3>
                            <p class="default-content">Lorem Ipsum is simply dummy text of the
                                printing and typesetting industry. Lorem Ipsum
                                has been the industry'
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="product-section">
        <div class="container">
            <div class="row section-heading text-center justify-content-center" data-aos="fade-down-right">
                <div class="col-lg-7">
                    <h2 class="title2">POPULAR PRODUCTS</h2>
                    <p class="title-description section-title-description text-center m-auto">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s
                    </p>
                </div>
            </div>
            <div class="gs-items">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-right">
                            <div class="product-img m-auto">
                                <a href="#"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img1.png' ) }}" alt="img"></a>
                                <a href="#" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding rebonNew">
                                        New
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="#">Laxer rex flet Rolex SA</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="#">Rolex</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-right">
                            <div class="product-img m-auto">
                                <a href="#"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img2.png' ) }}" alt="img"></a>
                                <a href="#" class="buy-now btn animate_flipInX">Buy Now</a>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="#">Laxer rex flet Hublot</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="#">Hublot</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-right">
                            <div class="product-img m-auto">
                                <a href="#"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img3.png' ) }}" alt="img"></a>
                                <a href="#" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding">
                                        Sale
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="#">Laxer rex flet Chopard</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="#">Chopard</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-right">
                            <div class="product-img m-auto">
                                <a href="#"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img4.png' ) }}" alt="img"></a>
                                <a href="#" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding rebon-offer">
                                        30% off
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="#">Laxer rex flet Blancpain </a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="#">Blancpain</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-left">
                            <div class="product-img m-auto">
                                <a href="#"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img5.png' ) }}" alt="img"></a>
                                <a href="#" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding">
                                        Sale
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="#">Laxer rex flet Cartier </a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="#">Cartier</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-left">
                            <div class="product-img m-auto">
                                <a href="#"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img3.png' ) }}" alt="img"></a>
                                <a href="#" class="buy-now btn animate_flipInX">Buy Now</a>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="#">Laxer rex flet Ulysse Nardin</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="#">Ulysse Nardin</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-left">
                            <div class="product-img m-auto">
                                <a href="#"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img2.png' ) }}" alt="img"></a>
                                <a href="#" class="buy-now btn animate_flipInX">Buy Now</a>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="#">Laxer rex flet Piaget SA</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="#">Piaget</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="gs-product-item product-default" data-aos="flip-left">
                            <div class="product-img m-auto">
                                <a href="#"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/product-img1.png' ) }}" alt="img"></a>
                                <a href="#" class="buy-now btn animate_flipInX">Buy Now</a>
                                <div class="product-rebon">
                                    <div class="rebonding rebonNew">
                                        new
                                    </div>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-description">
                                    <a href="#">Laxer rex flet Rolex</a>
                                </h3>
                                <div class="d-flex justify-content-between">
                                    <div class="price d-flex">
                                        <div class="sale-price">$100</div>
                                        <div class="discount-price align-middle"><del>$150</del></div>
                                    </div>
                                    <h5 class="brand-name"><a href="#">Rolex</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="offer-section">
        <div class="discount-deal">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-sm-12">
                        <div class="offer-cart text-center" data-aos="zoom-in" data-aos-duration="600">
                            <span class="first-now">now </span> <br>
                            <h2 class="off-discound">30% OFF</h2> <br>
                            <span class="amazing-deal">AMAZING DEAL HERE</span>
                        </div>
                        <div class="offer-countdown">
                            <div class="d-flex justify-content-center " id="timer">
                                <div id="days" data-aos="flip-right"></div>
                                <div id="hours" data-aos="flip-right"></div>
                                <div id="minutes" data-aos="flip-right"></div>
                                <div id="seconds" data-aos="flip-right"></div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-count-down text-center m-auto">
                        <a href="#" class="btn btn-warning">See More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="product-slider-area">
        <div class="container">
            <div class="product-titles text-center">
                <h6>A COMPANION FOR YOU</h6>
                <h2>SPECIAL EDITION</h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-carousel owl-carousel owl-theme owl-dots owl-dot button span carousel-item">
                        <div class="slideProduct firstItem">
                            <div class="row">
                                <div class="col-lg-5 col-md-12 col-sm-12">
                                    <div class="product-img">
                                        <img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/section-img1.png' ) }}" alt="img">
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-12 col-sm-12">
                                    <div class="slider-cart">
                                        <h3>SILVER METAL NUMERAL</h3>
                                        <span>$100.00</span>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                            industry. Lorem Ipsum has been the industry's standard dummy text ever
                                            since the 1500s,
                                        </p>
                                        <div class="whishList d-flex">
                                            <button type="button" class="btn btn-secondary mt-3">ADD TO CART</button>
                                            <button type="button" class="btn btn-secondary mt-3 ml-3">ADD TO WISHLIST</button>
                                            <button type="button" class="btn btn-secondary mt-3 ml-3">COMPARE</button>
                                        </div>
                                        <div class="category">
                                            <strong>Categories: </strong><span>T- shits, Watches</span>
                                        </div>
                                        <div class="tags">
                                            <strong>Tags: </strong><span>Covering, Teez</span>
                                        </div>
                                        <div class="social-media">
                                            <span>Share:</span>
                                            <a href="#"><i class="bx bxl-facebook "></i></a>
                                            <a href="#"><i class="bx bxl-google-plus "></i></a>
                                            <a href="#"><i class="bx bxl-linkedin "></i></a>
                                            <a href="#"><i class="bx bxl-twitter"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="slideProduct firstItem">
                            <div class="row">
                                <div class="col-lg-5 col-md-12 col-sm-12">
                                    <div class="product-img">
                                        <img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/section-img1.png' ) }}" alt="img">
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-12 col-sm-12">
                                    <div class="slider-cart">
                                        <h3>SILVER METAL NUMERAL</h3>
                                        <span>$100.00</span>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                            industry. Lorem Ipsum has been the industry's standard dummy text ever
                                            since the 1500s,
                                        </p>
                                        <div class="whishList d-flex">
                                            <button type="button" class="btn btn-secondary mt-3">ADD TO CART</button>
                                            <button type="button" class="btn btn-secondary mt-3 ml-3">ADD TO WISHLIST</button>
                                            <button type="button" class="btn btn-secondary mt-3 ml-3">COMPARE</button>
                                        </div>
                                        <div class="category">
                                            <span>Categories: </span><span> T- shits, Watches</span>
                                        </div>
                                        <div class="tags">
                                            <span>Tags: </span><span> Covering, Teez</span>
                                        </div>
                                        <div class="social-media">
                                            <span>Share: </span>
                                            <a href="#"><i class="bx bxl-facebook bx-fade-up-hover"></i></a>
                                            <a href="#"><i class="bx bxl-google-plus bx-fade-up-hover"></i></a>
                                            <a href="#"><i class="bx bxl-linkedin bx-fade-up-hover"></i></a>
                                            <a href="#"><i class="bx bxl-twitter bx-burst"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="shipping-area">
        <div class="container">
            <div class="row section-head text-center justify-content-center">
                <div class="col-lg-7">
                    <h2 class="title2">CARE FOR CUSTOMER</h2>
                    <p class="title-description">Fusce ac fermentum est, eu convallis lacus. Vestibulum bibendum ex ac hendrerit</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="shipping-box shipping-leftSide" data-aos="fade-up" data-aos-duration="300">
                        <div class="shipping-box-icon">
                            <a href="#" class="icon"><img src="{{  asset('assets/templates/ewatch/assets/images/icon/customer-icon1.svg' ) }}" alt="icon"></a>
                        </div>
                        <div class="shipping-text">
                            <h4>Free Shipping </h4>
                            <p class="default-content text-light">Lorem Ipsum is simply dummy text of the printing and typesetting
                                industry. Lorem Ipsum has been the industry's standard dummy text ever
                                since the 1500s
                            </p>
                        </div>
                        <div class="text-center">
                            <a href="#" class="btn-link">Vew More
                                <i class="bx bx-chevron-right bx-fade-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shipping-box shipping-rightSide" data-aos="fade-up" data-aos-duration="500">
                        <div class="shipping-box-icon">
                            <a href="#" class="icon"><img src="{{  asset('assets/templates/ewatch/assets/images/icon/customer-icon2.svg' ) }}" alt="icon"></a>
                        </div>
                        <div class="shipping-text">
                            <h4>Free Shipping </h4>
                            <p class="default-content text-light">Lorem Ipsum is simply dummy text of the printing and typesetting
                                industry. Lorem Ipsum has been the industry's standard dummy text ever
                                since the 1500s
                            </p>
                        </div>
                        <div class="text-center">
                            <a href="#" class="btn-link">Vew More
                                <i class="bx bx-chevron-right bx-fade-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="customer-testimonials">
        <div class="customerSliderArea">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="testimonial-slider owl-carousel">
                            <div class="item text-center">
                                <div class="review-icon">
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                </div>
                                <p class="default-content testimonial-slider-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's
                                    standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make
                                    a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining
                                    essentially unchanged
                                </p>
                                <div class="customer-name">
                                    <h4>John de smith</h4>
                                    <span class="name-title">Designation, XYZ Company</span>
                                </div>
                            </div>
                            <div class="item text-center active">
                                <div class="review-icon">
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                </div>
                                <p class="default-content testimonial-slider-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's
                                    standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make
                                    a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining
                                    essentially unchanged
                                </p>
                                <div class="customer-name">
                                    <h4>John de smith</h4>
                                    <span class="name-title">Designation, XYZ Company</span>
                                </div>
                            </div>
                            <div class="item text-center">
                                <div class="review-icon">
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                </div>
                                <p class="default-content testimonial-slider-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's
                                    standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make
                                    a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining
                                    essentially unchanged
                                </p>
                                <div class="customer-name">
                                    <h4>John de smith</h4>
                                    <span class="name-title">Designation, XYZ Company</span>
                                </div>
                            </div>
                            <div class="item text-center">
                                <div class="review-icon">
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                    <i class="flaticon-pointed-star"></i>
                                </div>
                                <p class="default-content testimonial-slider-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's
                                    standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make
                                    a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining
                                    essentially unchanged
                                </p>
                                <div class="customer-name">
                                    <h4>John de smith</h4>
                                    <span class="name-title">Designation, XYZ Company</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="blogs-area">
        <div class="container">
            <div class="row section-head text-center justify-content-center">
                <div class="col-lg-7">
                    <h2 class="title2">BLOGS</h2>
                    <p class="title-description section-title-description m-auto">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="blog-box" data-aos="flip-right">
                        <div class="blog-img">
                            <a href="blog-details.html"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/blog-img1.png' ) }}" alt="img"></a>
                        </div>
                        <div class="dateAndComment d-flex justify-content-between">
                            <span class="date"><i class="bx bx-calendar"></i> July 21,2020</span>
                            <span class="comment"><i class="bx bx-comment-detail"></i> 5 Comments</span>
                        </div>
                        <div class="blog-text">
                            <h3><a href="blog-details.html">Vestibulum Ac Bibendum Magna,
                                    Vitae Dictum Est.</a>
                            </h3>
                            <p class="default-content">Lorem Ipsum is simply dummy text of the printing and
                                typesetting industry. Lorem Ipsum has been the
                                industry's.
                            </p>
                        </div>
                        <div class="blogs-readMore-btn">
                            <a href="blog-details.html" class="btn-link blog-btn read-more">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="blog-box" data-aos="flip-right">
                        <div class="blog-img">
                            <a href="blog-details.html"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/blog-img2.png' ) }}" alt="img"></a>
                        </div>
                        <div class="dateAndComment d-flex justify-content-between">
                            <span class="date"><i class="bx bx-calendar"></i> November 21,2020</span>
                            <span class="comment"><i class="bx bx-comment-detail"></i> 3 Comments</span>
                        </div>
                        <div class="blog-text">
                            <h3>
                                <a href="blog-details.html">Suspendisse Dui Nulla, Ultricies vel
                                    Nisi a, Aliquet Ffficitur Arcu.</a>
                            </h3>
                            <p class="default-content">Lorem Ipsum is simply dummy text of the printing and
                                typesetting industry. Lorem Ipsum has been the
                                industry's.
                            </p>
                        </div>
                        <div class="blogs-links-btn">
                            <a href="blog-details.html" class="btn-link read-more blog-btn">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-box" data-aos="flip-right">
                        <div class="blog-img">
                            <a href="blog-details.html"><img class="img-fluid" src="{{  asset('assets/templates/ewatch/assets/images/blog-img3.png' ) }}" alt="img"></a>
                        </div>
                        <div class="dateAndComment d-flex justify-content-between">
                            <span class="date"><i class="bx bx-calendar"></i> December 21,2020</span>
                            <span class="comment"><i class="bx bx-comment-detail"></i> 8 Comments</span>
                        </div>
                        <div class="blog-text">
                            <h3>
                                <a href="blog-details.html">Nulla Metus Erat, Lobortis Vitae Eu,
                                    Sagittis Eleifend Eu Mauris.</a>
                            </h3>
                            <p class="default-content">Lorem Ipsum is simply dummy text of the printing and
                                typesetting industry. Lorem Ipsum has been the
                                industry's.
                            </p>
                        </div>
                        <div class="blogs-links-btn">
                            <a href="blog-details.html" class="btn-link read-more blog-btn">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="contactMail-area">
        <div class="container">
            <div class="text-and-mail">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="tuch-area-text">
                            <h3 data-aos="fade-down">Let's Stay in Touch</h3>
                            <p class="text-light title-description">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="tuch-area-mail text-center d-flex">
                            <i class="bx bx-envelope"></i>
                            <input type="email" name="email" class="form-control" placeholder="Email Address" autocomplete="on" required>
                            <div class="paper-plan">
                                <a href="#"><i class="bx bx-paper-plane bx-circle"></i></a>
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


