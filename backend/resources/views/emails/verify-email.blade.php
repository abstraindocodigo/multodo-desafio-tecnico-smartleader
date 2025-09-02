<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificação de Email - MultiTodo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }

        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }

        .button:hover {
            background: #5a6fd8;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>MultiTodo</h1>
        <p>Verificação de Email</p>
    </div>

    <div class="content">
        <h2>Olá!</h2>

        <p>Obrigado por se registrar no MultiTodo! Estamos felizes em tê-lo conosco.</p>

        <p>Para ativar sua conta e começar a usar nossa plataforma, clique no botão abaixo para verificar seu endereço de email:</p>

        <div style="text-align: center;">
            <a href="{{ $actionUrl }}" class="button">Verificar Email</a>
        </div>

        <p>Se o botão não funcionar, você pode copiar e colar o link abaixo no seu navegador:</p>
        <p style="word-break: break-all; background: #e9ecef; padding: 10px; border-radius: 5px;">
            {{ $actionUrl }}
        </p>

        <p><strong>Importante:</strong> Este link expira em 60 minutos por motivos de segurança.</p>

        <p>Se você não criou uma conta no MultiTodo, pode ignorar este email com segurança.</p>

        <p>Obrigado por escolher o MultiTodo!</p>
    </div>

    <div class="footer">
        <p>Este é um email automático, por favor não responda.</p>
        <p>&copy; {{ date('Y') }} MultiTodo. Todos os direitos reservados.</p>
    </div>
</body>

</html>