<!--begin::Footer-->
<footer class="app-footer text-center">
  <!--begin::To the end-->
  <div class="float-end d-none d-sm-inline">
    @lang("me::me.Version") {{ toBanglaPhone(get_setting('app_version', '4.1.3')) }}
  </div>
    @lang('me::me.mycopyright', [
        'year' => banglaYear(date('Y')),
        'developer' => config('me_settings.meta_author'),
        'developer_url' => config('me_settings.meta_author_url')
    ])@if (get_setting('show_footer_authorization', true)) {{ __('me::me.footer_authorization', ['company' => get_setting('app_name', 'Your Company')]) }}@endif
</footer>
<!--end::Footer-->
