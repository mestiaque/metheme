{{-- Readable before/after view of a data change entry (me_change_log). Needs: $activity --}}
@php
    $rows = collect($activity->changes ?? []);
    $main = $rows->whereNull('section');
    $sections = $rows->whereNotNull('section')->groupBy('section');
    $colors = ['added' => '#198754', 'removed' => '#dc3545', 'changed' => '#fd7e14', 'hidden' => '#6c757d'];
    $value = fn ($v) => $v === null || $v === '' ? '—' : $v;
@endphp

<h5 class="mb-3">
    <i class="fas fa-exchange-alt me-1" style="color:#6f42c1"></i> {{ __('me::me.Changes') }}
    <span class="badge text-white" style="background-color:#6f42c1">{{ toBanglaNumber($activity->change_count) }}</span>
</h5>

@if($activity->subjectLabel())
    <p class="small text-muted mb-2">
        {{ __('me::me.Record') }}: <strong>{{ $activity->subjectLabel() }}</strong> ·
        <a href="{{ route('activity.index', ['subject_type' => $activity->subject_type, 'subject_id' => $activity->subject_id]) }}">
            <i class="fas fa-history"></i> {{ __('me::me.record_history') }}
        </a>
    </p>
@endif

@if($main->isNotEmpty())
    <div class="table-responsive mb-3">
        <table class="table table-sm table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 25%">{{ __('me::me.Field') }}</th>
                    <th style="width: 37%">{{ __('me::me.Before') }}</th>
                    <th style="width: 38%">{{ __('me::me.After') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($main as $row)
                    <tr>
                        <td class="fw-semibold">
                            <span class="d-inline-block rounded-circle me-1" style="width:8px;height:8px;background:{{ $colors[$row['type']] ?? '#6c757d' }}"></span>
                            {{ $row['label'] }}
                        </td>
                        @if($row['type'] === 'hidden')
                            <td colspan="2" class="text-muted fst-italic">{{ __('me::me.value_hidden_changed') }}</td>
                        @else
                            <td class="text-break {{ $row['type'] === 'removed' || $row['type'] === 'changed' ? 'text-danger' : 'text-muted' }}">
                                @if($row['type'] === 'removed' || $row['type'] === 'changed')<del>{{ $value($row['old']) }}</del>@else{{ $value($row['old']) }}@endif
                            </td>
                            <td class="text-break {{ $row['type'] === 'added' || $row['type'] === 'changed' ? 'text-success fw-semibold' : 'text-muted' }}">{{ $value($row['new']) }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@foreach($sections as $section => $items)
    <div class="border rounded p-2 mb-3">
        <div class="fw-semibold mb-2">{{ $items->first()['section_label'] ?? \Illuminate\Support\Str::headline($section) }}</div>
        <ul class="list-unstyled mb-0 small">
            @foreach($items as $row)
                <li class="mb-1">
                    @if($row['type'] === 'added' && $row['field'] === null)
                        <span class="badge" style="background:{{ $colors['added'] }}">{{ __('me::me.Added') }}</span>
                        <strong>{{ $row['item'] }}</strong>
                        @if($row['new'])<span class="text-muted">({{ $row['new'] }})</span>@endif
                    @elseif($row['type'] === 'removed' && $row['field'] === null)
                        <span class="badge" style="background:{{ $colors['removed'] }}">{{ __('me::me.Removed') }}</span>
                        <strong><del>{{ $row['item'] }}</del></strong>
                        @if($row['old'])<span class="text-muted">({{ $row['old'] }})</span>@endif
                    @elseif($row['type'] === 'hidden')
                        <span class="badge" style="background:{{ $colors['hidden'] }}">{{ __('me::me.Changed') }}</span>
                        <strong>{{ $row['item'] }}</strong> › {{ $row['label'] }}: <em class="text-muted">{{ __('me::me.value_hidden_changed') }}</em>
                    @else
                        <span class="badge" style="background:{{ $colors[$row['type']] ?? '#6c757d' }}">{{ __('me::me.' . ucfirst($row['type'])) }}</span>
                        <strong>{{ $row['item'] }}</strong> › {{ $row['label'] }}:
                        <del class="text-danger">{{ $value($row['old']) }}</del> → <span class="text-success fw-semibold">{{ $value($row['new']) }}</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endforeach
