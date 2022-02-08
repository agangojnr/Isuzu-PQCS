<!DOCTYPE html>
<html dir="ltr">

@include('layouts.header.header')

<body>
  @yield('content')
    
    
    @include('layouts.footer.script')
  
   @yield('after-scripts')


</body>

</html>