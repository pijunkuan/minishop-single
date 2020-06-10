@include('front.layouts.header')

    <body>
        @if($vue['type']== "single")
            <div id="app">app</div>
        @else
            @include($path ?? 'front.404')
        @endif

        @include('front.layouts.footer')
    </body>

</html>
