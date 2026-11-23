<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Completed</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #17212B; background-color: #EFF4F7; padding: 24px;">
    <div style="max-width: 560px; margin: 0 auto; background: #F8FBFC; border: 1px solid #D4E0E4; border-radius: 16px; padding: 24px;">
        <p style="margin: 0 0 8px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #2E5E57;">
            Tasker &bull; Completion Notification
        </p>
        <h1 style="margin: 0 0 16px; font-size: 20px; font-weight: 600; color: #17212B;">
            {{ $task->title }}
        </h1>
        <p style="margin: 0 0 16px; font-size: 14px; line-height: 1.6; color: #4A5763;">
            A task has been marked as <strong>{{ $task->status->label() }}</strong> in Tasks for Today.
        </p>
        <table style="width: 100%; border-collapse: collapse; font-size: 14px; margin-bottom: 16px;">
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #D4E0E4; color: #4A5763;">Task ID</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #D4E0E4; font-weight: 600; text-align: right;">#{{ $task->id }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #D4E0E4; color: #4A5763;">Scheduled Date</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #D4E0E4; font-weight: 600; text-align: right;">{{ $task->task_date->format('F j, Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #D4E0E4; color: #4A5763;">Status</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #D4E0E4; font-weight: 600; text-align: right; color: #14532D;">{{ $task->status->label() }}</td>
            </tr>
            @if ($completedBy)
                <tr>
                    <td style="padding: 8px 0; color: #4A5763;">Updated By</td>
                    <td style="padding: 8px 0; font-weight: 600; text-align: right;">{{ $completedBy }}</td>
                </tr>
            @endif
        </table>
        <p style="margin: 0; font-size: 12px; color: #4A5763;">
            Sent automatically by the IT0049 Tasks for Today Management System.
        </p>
    </div>
</body>
</html>
