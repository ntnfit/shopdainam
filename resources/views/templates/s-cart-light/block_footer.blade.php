      <!-- Page Footer-->
      <!--================ start footer Area  =================-->
      <footer class="footer-area section_gap">
        <div class="container">
          <div class="row">
            <div class="col-lg-4 col-md-6 single-footer-widget">
              <a href="{{ sc_route('home') }}">
                <img class="logo-footer" src="{{  sc_file(sc_store('logo', ($storeId ?? null))) }}" alt="{{ sc_store('title', ($storeId ?? null)) }}">
            </a>
            <p>{{ sc_store('title', ($storeId ?? null)) }}</p>
            <p> {!! sc_store('time_active', ($storeId ?? null))  !!}</p>
            </div>          
            <div class="col-lg-4 col-md-6 single-footer-widget">
              <h4 class="footer-classic-title"> {{ sc_language_render('front.my_profile') }}</h4>
              <!-- RD Mailform-->
              <ul class="contacts-creative">
                  @if (!empty($sc_layoutsUrl['footer']))
                  @foreach ($sc_layoutsUrl['footer'] as $url)
                  <li>
                      <a {{ ($url->target =='_blank')?'target=_blank':''  }}
                          href="{{ sc_url_render($url->url) }}">{{ sc_language_render($url->name) }}</a>
                  </li>
                  @endforeach
                  @endif
              </ul>
            </div>             
            <div class="col-lg-4 col-md-6 single-footer-widget">           
              <form  method="post" action="{{ sc_route('subscribe') }}" class="form-inline">
                @csrf
                  <div class="form-wrap">
                    <input class="form-control" id="subscribe-form-2-email" type="email" name="subscribe_email" 
                    placeholder="{{ sc_language_render('subscribe.email') }}" onfocus="this.placeholder = ''"
                    onblur="this.placeholder = '{{ sc_language_render('subscribe.email') }}'"
                    required/>       
                  
                    <button  class="click-btn btn btn-default" type="submit" title="{{ sc_language_render('subscribe.title') }}">
                      <span class="fl-bigmug-line-paper122"></span>
                    </button>
                  </div>
                </form>
    
            </div>
        </div>    
      </div>
          <div class="footer-bottom row align-items-center">
            <p class="footer-text m-0 col-lg-8 col-md-12" style="text-align: center;" ><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
    Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://www.facebook.com/ntnfit.nguyen/" target="_blank">NTN</a>
    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
            <div class="col-lg-4 col-md-12 footer-social">
              <a class="icon mdi mdi-facebook" href="{{ sc_config('facebook_url') }}"></a>
              <a class="icon mdi mdi-twitter" href="{{ sc_config('twitter_url') }}"></a>
              <a class="icon mdi mdi-instagram" href="{{ sc_config('instagram_url') }}"></a>
            </div>
          </div>
        </div>
<!-- Messenger Chat Plugin Code -->
    <div id="fb-root"></div>

    <!-- Your Chat Plugin code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>

    <script>
      var chatbox = document.getElementById('fb-customer-chat');
      chatbox.setAttribute("page_id", "101775399116432");
      chatbox.setAttribute("attribution", "biz_inbox");
    </script>

    <!-- Your SDK code -->
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          xfbml            : true,
          version          : 'v13.0'
        });
      };

      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-HLYKC6LD57"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-HLYKC6LD57');
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-PPBRWTE4LH"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-PPBRWTE4LH');
</script>
</footer>
