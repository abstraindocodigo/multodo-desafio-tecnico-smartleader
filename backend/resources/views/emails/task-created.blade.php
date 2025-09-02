<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nova Tarefa Criada</title>
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
            background-color: #4CAF50;
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
            border-left: 4px solid #4CAF50;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Nova Tarefa Criada</h1>
    </div>
    
    <div class="content">
        <p>Olá <strong>{{ $user->name }}</strong>,</p>
        
        <p>Uma nova tarefa foi criada para você na empresa <strong>{{ $company->name }}</strong>.</p>
        
        <div class="task-info">
            <h3>{{ $task->title }}</h3>
            
            @if($task->description)
                <p><span class="label">Descrição:</span><br>{{ $task->description }}</p>
            @endif
            
            <p><span class="label">Status:</span> {{ ucfirst($task->status) }}</p>
            <p><span class="label">Prioridade:</span> {{ ucfirst($task->priority) }}</p>
            
            @if($task->due_date)
                <p><span class="label">Data Limite:</span> {{ $task->due_date->format('d/m/Y H:i') }}</p>
            @endif
            
            <p><span class="label">Criada em:</span> {{ $task->created_at->format('d/m/Y H:i') }}</p>
        </div>
        
        <p>Acesse o sistema para visualizar e gerenciar suas tarefas.</p>
    </div>
    
    <div class="footer">
        <p>Este é um email automático do Sistema de Tarefas Multiempresa.</p>
    </div>
</body>
</html>
