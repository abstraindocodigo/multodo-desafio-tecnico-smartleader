<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tarefa Concluída</title>
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
            background-color: #2196F3;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .task-info {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #2196F3;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Tarefa Concluída!</h1>
    </div>
    
    <div class="content">
        <p>Parabéns <strong>{{ $user->name }}</strong>!</p>
        
        <div class="success">
            <strong>Você concluiu com sucesso a tarefa:</strong> {{ $task->title }}
        </div>
        
        <div class="task-info">
            <h3>{{ $task->title }}</h3>
            
            @if($task->description)
                <p><span class="label">Descrição:</span><br>{{ $task->description }}</p>
            @endif
            
            <p><span class="label">Status:</span> <strong style="color: #28a745;">{{ ucfirst($task->status) }}</strong></p>
            <p><span class="label">Prioridade:</span> {{ ucfirst($task->priority) }}</p>
            
            @if($task->due_date)
                <p><span class="label">Data Limite:</span> {{ $task->due_date->format('d/m/Y H:i') }}</p>
            @endif
            
            <p><span class="label">Concluída em:</span> {{ $task->updated_at->format('d/m/Y H:i') }}</p>
        </div>
        
        <p>Continue assim! Acesse o sistema para visualizar suas próximas tarefas.</p>
    </div>
    
    <div class="footer">
        <p>Este é um email automático do Sistema de Tarefas Multiempresa.</p>
    </div>
</body>
</html>
