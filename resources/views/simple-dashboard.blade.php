<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenClaw Portal - Dashboard</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            margin: 0;
            background: #f5f5f5;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-weight: bold;
        }
        
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .card h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        
        .stat {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            display: block;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: transform 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-logout {
            background: #dc3545;
            margin-left: 10px;
        }
        
        .system-status {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }
        
        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #28a745;
        }
        
        .status-dot.warning {
            background: #ffc107;
        }
        
        .status-dot.error {
            background: #dc3545;
        }
        
        .quick-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>OpenClaw Portal</h1>
        <div class="user-info">
            <div class="user-avatar">
                {{ substr($user->email, 0, 1) }}
            </div>
            <div>
                <strong>{{ $user->name }}</strong><br>
                <small>{{ $user->email }}</small>
            </div>
            <a href="#" onclick="logout()" class="btn btn-logout">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <div class="dashboard-grid">
            <div class="card">
                <h3>System Overview</h3>
                <div class="system-status">
                    <div class="status-dot"></div>
                    <span>All systems operational</span>
                </div>
                <p>OpenClaw Portal is running successfully with proxy configuration.</p>
                
                <div class="stats">
                    <div class="stat">
                        <span class="stat-number">4</span>
                        <span class="stat-label">Agents</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">60</span>
                        <span class="stat-label">Tasks</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Uptime</span>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <h3>Quick Actions</h3>
                <div class="quick-actions">
                    <a href="http://openclaw.deploymatrix.com:8082/" class="btn" target="_blank">Access Portal</a>
                    <a href="/test" class="btn" target="_blank">Test API</a>
                    <a href="/simple-login" class="btn">Switch User</a>
                </div>
                
                <h3 style="margin-top: 25px;">System Information</h3>
                <ul>
                    <li><strong>URL:</strong> http://openclaw.deploymatrix.com/</li>
                    <li><strong>Backend:</strong> http://localhost:8082/</li>
                    <li><strong>Proxy:</strong> Apache with ProxyPass</li>
                    <li><strong>Framework:</strong> Laravel 12</li>
                    <li><strong>Database:</strong> SQLite</li>
                </ul>
            </div>
            
            <div class="card">
                <h3>Recent Activity</h3>
                <ul>
                    <li>✅ Apache proxy configured</li>
                    <li>✅ Domain routing established</li>
                    <li>✅ Laravel application running</li>
                    <li>✅ 4 agents assigned tasks</li>
                    <li>✅ Documentation imported</li>
                    <li>⚠️ CSRF issues with proxy (workaround active)</li>
                </ul>
                
                <h3 style="margin-top: 25px;">Next Steps</h3>
                <ol>
                    <li>Fix CSRF token issues with proxy</li>
                    <li>Implement full authentication</li>
                    <li>Configure SSL/TLS</li>
                    <li>Deploy Phase 2 tasks</li>
                </ol>
            </div>
        </div>
        
        <div class="card" style="margin-top: 30px;">
            <h3>Technical Details</h3>
            <p><strong>Issue:</strong> The Laravel CSRF protection is conflicting with the Apache proxy configuration. This is a common issue when running Laravel behind a reverse proxy.</p>
            <p><strong>Current Solution:</strong> Using a simplified login system that bypasses CSRF for testing purposes.</p>
            <p><strong>Permanent Fix Needed:</strong> Proper configuration of Laravel's TrustProxies middleware and session handling for the proxy environment.</p>
            
            <div class="quick-actions" style="margin-top: 20px;">
                <a href="https://laravel.com/docs/10.x/requests#configuring-trusted-proxies" class="btn" target="_blank">Laravel Proxy Docs</a>
                <a href="https://httpd.apache.org/docs/2.4/mod/mod_proxy.html" class="btn" target="_blank">Apache Proxy Docs</a>
            </div>
        </div>
    </div>
    
    <script>
        function logout() {
            localStorage.removeItem('user');
            window.location.href = '/simple-login';
        }
        
        // Load user from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const user = JSON.parse(localStorage.getItem('user') || '{}');
            if (!user.email) {
                window.location.href = '/simple-login';
            }
        });
    </script>
</body>
</html>