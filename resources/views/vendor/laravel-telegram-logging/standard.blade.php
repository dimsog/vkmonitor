<b>{{ $appName }}</b> ({{ $level_name }})
Env: {{ $appEnv }}
[{{ $datetime->format('Y-m-d H:i:s') }}] {{ $appEnv }}.{{ $level_name }} {{ str_replace('\n', "\n", $formatted) }}
