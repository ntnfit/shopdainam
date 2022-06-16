
 <div class="single-product">
            <div class="product-img">
               <a href="{{ $product->getUrl() }}">
              <img class="img-fluid w-100" src="{{ sc_file($product->getThumb()) }}" alt="{{ $product->name }}"/>
              </a>
              <div class="p_icon">
                <a href="{{ $product->getUrl() }}">
                  <i class="ti-eye"></i>
                </a>
                <a onClick="addToCartAjax('{{ $product->id }}','wishlist','{{ $product->store_id }}')">
                  <i class="ti-heart"></i>
                </a>
                <a onClick="addToCartAjax('{{ $product->id }}','default','{{ $product->store_id }}')">
                  <i class="ti-shopping-cart"></i>
                </a>
              </div>
            </div>
            <div class="product-btm">
              <a href="{{ $product->getUrl() }}" class="d-block">
                <h4>{{ $product->name }}</h4>
              </a>
              <div class="mt-3">
                <span class="mr-4">  {!! $product->showPrice() !!}</span>
                 @if ($product->price != $product->getFinalPrice() && $product->kind !=SC_PRODUCT_GROUP)
                 <span><img class="product-badge new" src="{{ sc_file($sc_templateFile.'/images/home/sale.png') }}" class="new" alt="" /></span>
                 @elseif($product->kind == SC_PRODUCT_BUILD)   
                  @elseif($product->kind == SC_PRODUCT_GROUP)   
                  @endif
              </div>
            </div>
	</div>
