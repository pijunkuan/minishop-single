@extends('front.layouts.layouts')
@section('body')
    @if($vue['type']== "single")
        @include($path)
    @else
        @include($path ?? 'front.404')
    @endif
@endsection


