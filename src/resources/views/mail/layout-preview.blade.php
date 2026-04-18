@extends('me::master')

@section('title', 'Mail Layout Preview')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold"><i class="bi bi-envelope-paper me-2 text-primary"></i>Mail Layout Preview</h4>
            <p class="text-muted mb-0 small">Preview all available email templates with demo data.</p>
        </div>
        <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">3 Layouts</span>
    </div>

    {{-- Tab Navigation --}}
    <ul class="nav nav-tabs border-0 mb-0" id="mailPreviewTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold" id="tab-auth-otp" data-bs-toggle="tab" data-bs-target="#pane-auth-otp" type="button" role="tab">
                <i class="bi bi-shield-lock me-1"></i> Auth &mdash; With OTP
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="tab-auth-no-otp" data-bs-toggle="tab" data-bs-target="#pane-auth-no-otp" type="button" role="tab">
                <i class="bi bi-key me-1"></i> Auth &mdash; No OTP
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="tab-notice" data-bs-toggle="tab" data-bs-target="#pane-notice" type="button" role="tab">
                <i class="bi bi-megaphone me-1"></i> Notice
            </button>
        </li>
    </ul>

    {{-- Tab Content --}}
    <div class="tab-content border border-top-0 rounded-bottom bg-light" id="mailPreviewTabsContent">

        {{-- Auth Layout with OTP --}}
        <div class="tab-pane fade show active p-3" id="pane-auth-otp" role="tabpanel">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <span class="fw-semibold">Auth Layout</span>
                    <span class="badge bg-success ms-2">OTP Enabled</span>
                </div>
                <small class="text-muted">Used for: Email verification, Login OTP</small>
            </div>
            <div class="rounded overflow-hidden border bg-white" style="height: 680px;">
                <iframe id="frame-auth-otp" style="width:100%;height:100%;border:none;" title="Auth Layout with OTP"></iframe>
            </div>
        </div>

        {{-- Auth Layout without OTP --}}
        <div class="tab-pane fade p-3" id="pane-auth-no-otp" role="tabpanel">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <span class="fw-semibold">Auth Layout</span>
                    <span class="badge bg-secondary ms-2">No OTP</span>
                </div>
                <small class="text-muted">Used for: Password reset, Notifications</small>
            </div>
            <div class="rounded overflow-hidden border bg-white" style="height: 680px;">
                <iframe id="frame-auth-no-otp" style="width:100%;height:100%;border:none;" title="Auth Layout without OTP"></iframe>
            </div>
        </div>

        {{-- Notice Layout --}}
        <div class="tab-pane fade p-3" id="pane-notice" role="tabpanel">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <span class="fw-semibold">Notice Layout</span>
                    <span class="badge bg-primary ms-2">With Greeting</span>
                </div>
                <small class="text-muted">Used for: Announcements, Bulk notices</small>
            </div>
            <div class="rounded overflow-hidden border bg-white" style="height: 780px;">
                <iframe id="frame-notice" style="width:100%;height:100%;border:none;" title="Notice Layout"></iframe>
            </div>
        </div>

    </div>
</div>

<script>
    (function () {
        const frames = [
            { id: 'frame-auth-otp',    html: @json($authWithOtp) },
            { id: 'frame-auth-no-otp', html: @json($authNoOtp) },
            { id: 'frame-notice',      html: @json($noticeHtml) },
        ];

        frames.forEach(function (item) {
            const iframe = document.getElementById(item.id);
            if (iframe) {
                iframe.srcdoc = item.html;
            }
        });
    })();
</script>
@endsection
