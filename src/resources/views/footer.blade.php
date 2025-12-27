<!--begin::Footer-->
<footer class="app-footer">
  <!--begin::To the end-->
  <div class="float-end d-none d-sm-inline">
    @lang("Version") {{ toBanglaPhone(get_setting('app_version', '2.0.0')) }}
  </div>
    @lang('me::metheme.mycopyright', [
        'year' => banglaYear(date('Y')),
        'company' => get_setting('shop_name', 'Your Company')
    ])
</footer>
<!--end::Footer-->
