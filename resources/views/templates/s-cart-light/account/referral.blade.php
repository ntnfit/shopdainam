@php
/*
$layout_page = shop_profile
** Variables:**
- $statusOrder
- $orders
*/ 
@endphp

@extends($sc_templatePath.'.account.layout')

@section('block_main_profile')
<h6 class="title-store">{{ $title }}</h6>
    <code>
      Referral link: <a> {{ URL::to('customer/register.html') }}?refs={{$refs}}</a>
      <br></br>
      Referral Code: <a>{{$customer->email}}</a>
    </code>
      @if (count($referral) ==0)
      <div class="text-danger">
        {{ sc_language_render('front.data_notfound') }}
      </div>
      @else
      <p>
        <code> Số user sử dụng referral link:{{$referral->count()}}</code> 
    </p>
      @endif
   
@endsection
