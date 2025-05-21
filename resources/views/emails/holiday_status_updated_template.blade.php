<!DOCTYPE html>
<html>
<head>
    <title>Solicitud de Vacaciones</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: #007bff;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            padding: 20px;
            background: white;
            border-radius: 0 0 8px 8px;
        }
        .greeting {
            font-size: 24px;
            color: #007bff;
            margin-bottom: 20px;
        }
        .status-approved {
            color: #28a745;
            font-weight: bold;
        }
        .status-declined {
            color: #dc3545;
            font-weight: bold;
        }
        .details {
            margin: 20px 0;
            padding: 15px;
            background: #f1f1f1;
            border-radius: 8px;
        }
        .details p {
            margin: 8px 0;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Solicitud de Vacaciones</h1>
    </div>
    <div class="content">
        <div class="greeting">¡Hola! 👋</div>

        @if($holiday->type === 'approved')
            <p>Tu solicitud de vacaciones ha sido <span class="status-approved">aprobada</span>.</p>
        @else
            <p>Lamentamos informarte que tu solicitud de vacaciones ha sido <span class="status-declined">rechazada</span>.</p>
        @endif

        <p>A continuación los detalles:</p>

        <div class="details">
            <p><span class="label">Calendario de Referencia:</span> <strong>{{ $holiday->calendar->name }}</strong></p>
            <p><span class="label">Fecha de Inicio:</span> <strong>{{ $holiday->day }}</strong></p>
        </div>

        <p>@if($holiday->type === 'approved') ¡Esperamos que disfrutes tus vacaciones! @else Si necesitas más información, no dudes en contactarnos. @endif</p>
    </div>
    <div class="footer">
        ¡Gracias! 🌴<br>
        Atentamente,<br>
        {{ $holiday->user->name }}<br>
    </div>
</div>
</body>
</html>
