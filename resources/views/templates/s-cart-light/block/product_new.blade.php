@php
$productsNew = $modelProduct->start()->getProductLatest()->setlimit(sc_config('product_top'))->getData();
$catagory = $modelCategory->getData();
@endphp

@if ($productsNew)
      <!-- New Products-->
  <section class="inspired_product_area section_gap_bottom_custom">
            <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-12">
            <div class="main_title">
              <h2><span>{{ sc_language_render('front.products_new') }}</span></h2>
            </div>
          </div>
        </div>
          <div class="owl-carousel owl-style-7" data-items="2" data-sm-items="2" data-xl-items="3" data-xxl-items="6" data-nav="false" data-dots="true" data-margin="5"  data-autoplay="true">
        @foreach ($productsNew as $key => $productNew)
            {{-- Render product single --}}
            @include($sc_templatePath.'.common.product_single', ['product' => $productNew])
            {{-- //Render product single --}}
        @endforeach
      </div>
  
        {{-- Render product for catagory --}}
        @foreach($catagory as $key => $catagorys)
        <div class="block">
          <div class="col-sm-12">
              <span><b><a style="color:black; font-size:20px" href="{{ $catagorys->getUrl() }}"> {{ $catagorys->title }}</a></b></span>
            </div>
        </div>
          @php
            $products=$modelProduct
            ->getProductToCategory([$catagorys->id])
            ->setLimit(6)
            ->getData();
          @endphp
          @if(count( $products)>=3)
          <div class="owl-carousel owl-style-7" data-items="2" data-sm-items="2" data-xl-items="3" data-xxl-items="6" data-nav="false" data-dots="true" data-margin="5"  data-autoplay="true">
            @foreach($products as $key => $product)
              {{-- Render product single --}}
              @include($sc_templatePath.'.common.product_silder', ['product' => $product])
              {{-- //Render product single --}}
            @endforeach
          </div>
          @else
          <div class="owl-carousel owl-style-7" data-items="1" data-sm-items="1" data-xl-items="1" data-xxl-items="1" data-nav="false" data-dots="true" data-margin="5"  data-autoplay="true">
            @foreach($products as $key => $product)
            <div class="col-lg-3 col-md-3 col-sm-4 col-6">
              {{-- Render product single --}}
              @include($sc_templatePath.'.common.product_silder', ['product' => $product])
              {{-- //Render product single --}}
            </div>
            @endforeach
          </div>
        
          @endif
      @endforeach
    </div>
    </section>
<style>
  .owl-carousel .owl-item img {
    display: block;
    width: 30%;
}
.owl-item cloned { width : 190px !important; }
</style>

@endif

