<div class="footer text-muted text-center{{ !empty($class) ? " {$class}" : ''  }}">
    &copy; {{ date('Y') }} <a href="{{ route('forms.index') }}">{{ config('app.name') }}</a>
</div>
