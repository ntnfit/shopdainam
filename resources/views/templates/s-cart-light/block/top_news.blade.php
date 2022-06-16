@php
$news = $modelNews->start()->setlimit(sc_config('item_top'))->getData();
@endphp

@if ($news)
<div class="row justify-content-center">
  <div class="col-lg-12">
    <div class="main_title">
      <h2><span>{{ sc_language_render('front.blog') }}</span></h2>
    </div>
  </div>
</div>
 <div class="owl-carousel owl-style-7" data-items="1" data-sm-items="2" data-xl-items="3" data-xxl-items="4" data-nav="true" data-dots="true" data-margin="30" data-autoplay="true">
      @foreach ($news as $blog)
        {{-- Render product single --}}
        @include($sc_templatePath.'.common.blog_single', ['blog' => $blog])
        {{-- //Render product single --}}
      @endforeach
    </div>
@endif
