@include('front.layouts.header')

<body>
@if($vue['type']== "single")
    @include($path)
@else
    @include($path ?? 'front.404')
@endif

@include('front.layouts.footer')
</body>

</html>
