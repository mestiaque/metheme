<!--begin::Footer-->
<footer class="app-footer text-center">
  <!--begin::To the end-->
  <div class="float-end d-none d-sm-inline">
    @lang("me::me.Version") {{ toBanglaPhone(get_setting('app_version', '4.1.3')) }}
  </div>
    @lang('me::me.mycopyright', [
        'year' => banglaYear(date('Y')),
        'company' => get_setting('app_name', 'Your Company')
    ])
</footer>
<!--end::Footer-->
