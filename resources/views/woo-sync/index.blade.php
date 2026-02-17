<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WooCommerce Sync Bridge</title>
    <style>
        body { font-family: ui-sans-serif, system-ui, sans-serif; margin: 24px; color: #1f2937; }
        .row { display: flex; gap: 12px; flex-wrap: wrap; margin: 16px 0; }
        .card { border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px; min-width: 240px; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border-bottom: 1px solid #e5e7eb; text-align: left; padding: 10px; }
        .success { padding: 10px; border: 1px solid #bbf7d0; background: #f0fdf4; color: #166534; border-radius: 8px; }
    </style>
</head>
<body>
    <h1>WooCommerce -> Laravel Sync Bridge</h1>

    @if(session('status'))
        <p class="success">{{ session('status') }}</p>
    @endif

    <div class="row">
        @foreach(['products', 'orders', 'customers'] as $type)
            <div class="card">
                <h3>{{ ucfirst($type) }}</h3>
                <p>Last sync: {{ optional($stats[$type])->created_at ?? 'never' }}</p>
                <form method="post" action="{{ route('woo-sync.run', $type) }}">
                    @csrf
                    <button type="submit">Run sync</button>
                </form>
            </div>
        @endforeach
    </div>

    <h2>Recent sync logs</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Fetched</th>
                <th>Upserted</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at }}</td>
                    <td>{{ $log->type }}</td>
                    <td>{{ $log->fetched_count }}</td>
                    <td>{{ $log->upserted_count }}</td>
                    <td>{{ $log->duration_ms }} ms</td>
                    <td>{{ $log->status }}</td>
                    <td>{{ $log->message }}</td>
                </tr>
            @empty
                <tr><td colspan="7">No sync logs yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
