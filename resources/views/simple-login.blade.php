<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenClaw Portal - Simple Login</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-size: 24px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }
        
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            display: none;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .info {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            font-size: 14px;
            color: #666;
        }
        
        .info h3 {
            margin-top: 0;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>OpenClaw Portal</h1>
        
        <form id="loginForm">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="admin@openclaw.test" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" value="password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <div id="message" class="message"></div>
        
        <div class="info">
            <h3>Demo Credentials</h3>
            <p><strong>Email:</strong> admin@openclaw.test</p>
            <p><strong>Password:</strong> password</p>
            <p><small>This is a simplified login for testing. The full Laravel authentication system is installed but currently has CSRF issues with the proxy configuration.</small></p>
        </div>
    </div>
    
    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const message = document.getElementById('message');
            
            message.style.display = 'none';
            message.className = 'message';
            
            try {
                const response = await fetch('/test-login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email, password })
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    message.textContent = 'Login successful! Redirecting to dashboard...';
                    message.className = 'message success';
                    message.style.display = 'block';
                    
                    // Store user info
                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    // Redirect after delay
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1500);
                } else {
                    message.textContent = data.message || 'Login failed';
                    message.className = 'message error';
                    message.style.display = 'block';
                }
            } catch (error) {
                message.textContent = 'Network error. Please try again.';
                message.className = 'message error';
                message.style.display = 'block';
                console.error('Login error:', error);
            }
        });
        
        // Check if already logged in
        window.addEventListener('load', function() {
            const user = localStorage.getItem('user');
            if (user) {
                document.getElementById('message').textContent = 'Already logged in. Redirecting...';
                document.getElementById('message').className = 'message success';
                document.getElementById('message').style.display = 'block';
                
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 1000);
            }
        });
    </script>
</body>
</html>