<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #0f172a;
            color: #e2e8f0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
        }

        .container {
            max-width: 640px;
            margin: 0 auto;
            padding: 24px;
        }

        .card {
            background: #0b1220;
            border: 1px solid #1f2937;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .35);
        }

        .brand {
            text-align: center;
            margin-bottom: 24px;
        }

        .brand-title {
            font-size: 24px;
            font-weight: 800;
            color: #c7d2fe;
        }

        .h1 {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 16px;
            color: #f8fafc;
        }

        .text {
            font-size: 14px;
            line-height: 1.6;
            color: #cbd5e1;
        }

        .meta {
            margin: 16px 0;
            padding: 16px;
            background: #0a1222;
            border: 1px solid #1e293b;
            border-radius: 10px;
        }

        .meta-row {
            display: flex;
            gap: 12px;
            padding: 6px 0;
            border-bottom: 1px dashed #243244;
        }

        .meta-row:last-child {
            border-bottom: none;
        }

        .meta-key {
            width: 120px;
            color: #93c5fd;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .meta-val {
            color: #e5e7eb;
            font-size: 14px;
        }

        .changes {
            margin-top: 12px;
            color: #94a3b8;
            font-size: 13px;
        }

        .section-title {
            margin-top: 18px;
            color: #93c5fd;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .flow {
            margin-top: 8px;
            color: #e2e8f0;
            background: #0a1222;
            border: 1px solid #1e293b;
            border-radius: 10px;
            padding: 12px;
            white-space: pre-wrap;
        }

        .btn {
            display: inline-block;
            padding: 12px 18px;
            border-radius: 10px;
            text-decoration: none;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(99, 102, 241, .35);
        }

        .btn:hover {
            background: linear-gradient(135deg, #4f46e5, #9333ea);
            color: #fff;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(99, 102, 241, .45);
        }

        .footer {
            margin-top: 28px;
            color: #64748b;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="brand">
            <div class="brand-title">{{ $appName }}</div>
        </div>
        <div class="card">
            <div class="h1">Hi {{ $notifiable->name ?? 'there' }}</div>
            <p class="text">{{ $messageText }}</p>
            <p class="text">Actor: {{ $actorName }}</p>

            <div class="meta">
                @foreach ($meta as $k => $v)
                    @if (!is_null($v) && $v !== '')
                        <div class="meta-row">
                            <div class="meta-key">{{ $k }}</div>
                            <div class="meta-val">{{ $v }}</div>
                        </div>
                    @endif
                @endforeach
            </div>

            @if (!empty($changes))
                <div class="changes">
                    <strong>Updated {{ count($changes) }} field{{ count($changes) === 1 ? '' : 's' }}</strong>
                </div>
            @endif

            @if (!empty($ticket->description))
                <div class="section-title">Description</div>
                <div class="flow">{{ e(Str::limit($ticket->description, 200)) }}</div>
            @endif

            <p style="margin-top:20px;">
                <a class="btn" href="{{ $url }}" target="_blank" rel="noopener">View Ticket</a>
            </p>
        </div>
        <div class="footer">© {{ date('Y') }} {{ $appName }}</div>
        <div class="footer">If the button doesn't work, open: <a href="{{ $url }}"
                style="color:#93c5fd;">{{ $url }}</a></div>
        <div style="height:24px"></div>

    </div>
</body>

</html>
