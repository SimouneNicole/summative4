@props(['status'])

<span class="status-pill status-pill--{{ $status->value }}">{{ $status->label() }}</span>
