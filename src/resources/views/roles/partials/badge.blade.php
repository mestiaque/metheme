{{-- Role badge in the role's own color. Needs: $role --}}
<span class="badge" style="{{ $role->badgeStyle() }}" title="{{ $role->hierarchyPath() }}">{{ $role->name }}</span>
