@props(['status'])

<span class="status-pill {{ $status->cssClass() }}">{{ $status->label() }}</span>
