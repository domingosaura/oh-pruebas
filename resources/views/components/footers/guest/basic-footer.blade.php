@props(['textColor'])
<footer class="footer position-fixed bottom-footer py-2 w-100 z-index-1">
  <div class="container">
    <div class="row align-items-center justify-content-lg-between">
      <div class="col-12 col-md-6 my-auto">
        <div class="{{ $textColor}} copyright text-center text-sm text-lg-start">
          © <script>
            document.write(new Date().getFullYear())
          </script>&nbsp;
          OhMyPhoto Powered by Superqubit
        </div>
      </div>
      <div class="col-12 col-md-6">
        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
          <li class="nav-item">
            <a href="mailto:{{env('MAIL_FROM_ADDRESS', 'info@ohmyphoto.es')}}">contacto</a>
          </li>
          <li class="nav-item">
            &nbsp;&nbsp;<a href="{{ route('cookies')}}">política de cookies</a>
          </li>
          <!--
          <li class="nav-item">
            &nbsp;&nbsp;<a href="{ { route('condiciones')} }">términos y condicionesa</a>
          </li>
        -->
    </ul>
          
          
          <!--
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
          <li class="nav-item">
            <a href="https://www.creative-tim.com" class="nav-link { { $textColor} }" target="_blank">Creative Tim</a>
          </li>
          <li class="nav-item">
            <a href="https://www.updivision.com" class="nav-link { { $textColor} }" target="_blank">UPDIVISION</a>
          </li>
          <li class="nav-item">
            <a href="https://www.creative-tim.com/presentation" class="nav-link { { $textColor} }" target="_blank">About
              Us</a>
          </li>
          <li class="nav-item">
            <a href="https://www.creative-tim.com/blog" class="nav-link { { $textColor} }" target="_blank">Blog</a>
          </li>
          <li class="nav-item">
            <a href="https://www.creative-tim.com/license" class="nav-link pe-0 { { $textColor} }"
              target="_blank">License</a>
          </li>
        </ul>
        -->


      </div>
    </div>
  </div>
</footer>