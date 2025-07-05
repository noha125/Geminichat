<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Gemini</title>
     <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f0f4;
            color: #6b4a4a;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: #fff;
            border-right: 1px solid #f0d6dd;
            padding: 1.5rem;
            box-shadow: 2px 0 10px rgba(217, 125, 139, 0.1);
            overflow-y: auto;
        }
        
        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .sidebar-title {
            color: #d97d8b;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .new-chat-btn {
            background: #f8e8eb;
            color: #d97d8b;
            border: none;
            border-radius: 6px;
            padding: 0.4rem 0.8rem;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .new-chat-btn:hover {
            background: #f0d6dd;
        }
        
        .conversation-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .conversation-item {
            padding: 0.7rem 1rem;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            cursor: pointer;
            font-size: 0.95rem;
            color: #8a5a5a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: all 0.2s ease;
        }
        
        .conversation-item:hover {
            background: #f8e8eb;
        }
        
        .conversation-item.active {
            background: #f0d6dd;
            color: #b76473;
            font-weight: 500;
        }
        
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            overflow-y: auto;
        }
        
        .container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 10px 25px rgba(107, 74, 74, 0.1);
        }
        
        h1 {
            text-align: center;
            color: #d97d8b;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        form {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        input[type="text"] {
            flex-grow: 1;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 2px solid #f0d6dd;
            font-size: 1rem;
            background: #fcf8f9;
            transition: border 0.3s ease;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: #d97d8b;
        }
        
        input[type="submit"] {
            background: #d97d8b;
            color: white;
            border: none;
            padding: 0 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        input[type="submit"]:hover {
            background: #b76473;
            transform: translateY(-1px);
        }
        
        .chat-message {
            margin-bottom: 1.5rem;
        }
        
        .user-message {
            background: #f0f4f8;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            border-left: 4px solid #d97d8b;
        }
        
        .bot-message {
            background: #faf2f4;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #a0aec0;
        }
        
        .message-time {
            font-size: 0.75rem;
            color: #718096;
            text-align: right;
            margin-top: 0.5rem;
        }
        
        .error {
            color: #c0392b;
            font-weight: bold;
            margin-bottom: 1rem;
            text-align: center;
            padding: 0.8rem;
            background: #fce8e8;
            border-radius: 8px;
            border-left: 4px solid #c0392b;
        }
        
        .user-info {
            background: #d97d8b;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            display: inline-block;
        }
        
        .archive-bar {
            background: linear-gradient(135deg, #f8e8eb, #f5dfe4);
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            margin-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(217, 125, 139, 0.1);
        }
        
        .archive-title {
            color: #b76473;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .archive-btn {
            background: #d97d8b;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 0.4rem 0.8rem;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        
        .archive-btn:hover {
            background: #b76473;
            transform: translateY(-1px);
        }
        .conversation-item {
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .conversation-item:hover {
            background: #f8e8eb;
        }
        .conversation-prompt {
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .conversation-time {
            font-size: 0.75rem;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-title">Historique</div>
            <a href="{{ route('home') }}" class="new-chat-btn">+ Nouveau</a>
        </div>
        
        <ul class="conversation-list">
            @foreach($conversations as $conversation)
                <li class="conversation-item">
                    <div class="conversation-prompt">
                        {{ Str::limit($conversation->prompt, 40) }}
                    </div>
                    <div class="conversation-time">
                        {{ $conversation->created_at->format('H:i') }}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    
    <div class="main-content">
        <div class="container">
            @auth
                <div class="user-info">Connecté en tant que {{ Auth::user()->name }}</div>
            @endauth

            <h1>Chat Gemini</h1>

            @if ($errors->any())
                <div class="error">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('ask') }}">
                @csrf
                <input type="text" name="prompt" 
                       placeholder="Pose ta question ici..." 
                       value="{{ old('prompt', session('prompt')) }}" 
                       required />
                <input type="submit" value="Envoyer" />
            </form>

            @if(session('answer'))
                <div class="chat-message">
                    <div class="user-message">
                        {{ session('prompt') }}
                        <div class="message-time">{{ now()->format('H:i') }}</div>
                    </div>
                    <div class="bot-message">
                        {{ session('answer') }}
                        <div class="message-time">{{ now()->format('H:i') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Faire défiler vers le bas automatiquement
        window.onload = function() {
            const chatMessages = document.querySelector('.main-content');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        };

        function fetchArchive() {
            fetch('/archive')
                .then(response => response.json())
                .then(data => {
                    console.log('Archive:', data);
                    alert(`${data.length} conversations chargées`);
                });
        }
    </script>
</body>
</html>