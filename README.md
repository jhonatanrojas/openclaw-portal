# OpenClaw Portal

A centralized task management and file sharing system for the OpenClaw ecosystem.

## 🚀 Features

### 📋 Task Management System
- **Intelligent Auto-Assignment**: Tasks are automatically assigned to agents based on skills, preferences, and workload
- **Priority Scoring**: Tasks have complexity, urgency, and importance scores that calculate overall priority
- **Dependency Management**: Tasks can depend on other tasks with automatic blocking
- **Time Tracking**: Estimated vs actual hours with progress calculation
- **Status Workflow**: Pending → In Progress → Completed with automatic timestamps

### 📁 File Share System
- **Category-based Organization**: Screenshots, docs, logs, backups, temp files
- **Upload/Download API**: RESTful API for programmatic file management
- **Security Headers**: Protected with XSS, clickjacking, and MIME sniffing protection
- **Automatic Cleanup**: Temporary files automatically cleaned after 7 days

### 📚 Documentation Management
- **Markdown Editor**: Built-in editor for creating and editing documentation
- **Category Organization**: Organize docs by project, feature, or team
- **Search Functionality**: Full-text search across all documentation
- **Version History**: Track changes to documentation over time

### 🤖 Agent Management
- **Skill-based Assignment**: Agents have specific skills (backend, frontend, devops, documentation)
- **Performance Tracking**: Completion rates and workload balancing
- **Preference Matching**: Agents can specify task type preferences

### 🔧 Technical Features
- **RESTful APIs**: Complete API for all services
- **Modern UI**: Responsive design with Tailwind CSS
- **Authentication**: User authentication with role-based access
- **Database**: SQLite for development, MySQL/PostgreSQL ready
- **Real-time Stats**: Dashboard with real-time statistics

## 🏗️ Architecture

```
OpenClaw Portal
├── app/
│   ├── Http/Controllers/
│   │   ├── TaskController.php          # Complete task management
│   │   ├── TaskAssignmentController.php # Intelligent assignment logic
│   │   ├── FileShareController.php     # File upload/download
│   │   └── DocumentationController.php # Docs management
│   ├── Models/
│   │   ├── Task.php                    # Task model with relationships
│   │   ├── TaskAssignment.php          # Assignment tracking
│   │   └── User.php                    # User/Agent model
│   └── Services/
│       ├── FileShareService.php        # File operations
│       └── OpenClawFileService.php     # Core file editing
├── database/
│   └── migrations/                     # Complete database schema
├── resources/views/
│   ├── tasks/                          # Task management UI
│   ├── file-share/                     # File share interface
│   └── layouts/                        # Application layouts
└── routes/
    ├── web.php                         # Web routes
    └── api.php                         # API routes
```

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- SQLite/MySQL/PostgreSQL

### Installation
```bash
# Clone the repository
git clone https://github.com/jhonatanrojas/openclaw-portal.git
cd openclaw-portal

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed

# Build assets
npm run build

# Start development server
php artisan serve
```

### Default Credentials
- **Admin**: `admin@openclaw.test` / `password`
- **Backend Agent**: `backend@openclaw.test` / `password`
- **Frontend Agent**: `frontend@openclaw.test` / `password`
- **DevOps Agent**: `devops@openclaw.test` / `password`
- **Documentation Agent**: `docs@openclaw.test` / `password`

## 📊 Task Management API

### List Tasks
```bash
GET /api/tasks
```

### Create Task
```bash
POST /api/tasks
{
  "title": "Implement user authentication",
  "description": "Create REST API for auth",
  "category": "backend",
  "type": "feature",
  "priority": "high",
  "estimated_hours": 8,
  "complexity_score": 0.7,
  "urgency_score": 0.8,
  "importance_score": 0.9
}
```

### Auto-Assign Task
```bash
GET /api/tasks/{id}/auto-assign
```

### Get Statistics
```bash
GET /api/tasks/stats
```

## 📁 File Share API

### List Categories
```bash
GET /api/file-share/categories
```

### Upload File
```bash
POST /api/file-share/{category}/upload
Content-Type: multipart/form-data
file: @screenshot.png
```

### List Files in Category
```bash
GET /api/file-share/{category}/files
```

## 🔗 Integration with OpenClaw

The portal integrates with OpenClaw in several ways:

1. **File Sharing**: OpenClaw agents can upload screenshots and logs to the file share
2. **Task Assignment**: Tasks can be created and assigned to OpenClaw agents
3. **Documentation**: OpenClaw documentation can be managed through the portal
4. **API Access**: All services are available via RESTful APIs

## 🛠️ Development

### Running Tests
```bash
php artisan test
```

### Database Seeding
```bash
php artisan db:seed
```

### Creating Migrations
```bash
php artisan make:migration create_table_name
```

### Creating Controllers
```bash
php artisan make:controller TaskController
```

## 📈 Roadmap

### Phase 1: Core Features (✅ Complete)
- [x] Task management system
- [x] File share system
- [x] Documentation management
- [x] Agent management
- [x] RESTful APIs

### Phase 2: Advanced Features (🔄 In Progress)
- [ ] Real-time notifications
- [ ] WebSocket integration
- [ ] Advanced reporting
- [ ] Email notifications
- [ ] Calendar integration

### Phase 3: Enterprise Features (📅 Planned)
- [ ] Multi-tenant support
- [ ] Advanced permissions
- [ ] Audit logging
- [ ] Backup/restore
- [ ] API rate limiting

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP framework
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework
- [OpenClaw](https://openclaw.ai) - The parent project
- All contributors who help improve this portal

## 📞 Support

- **GitHub Issues**: [Report bugs or request features](https://github.com/jhonatanrojas/openclaw-portal/issues)
- **Documentation**: [View full documentation](https://openclaw.ai/docs)
- **Community**: [Join our Discord](https://discord.gg/clawd)

---

**OpenClaw Portal** - Your centralized hub for task management and file sharing in the OpenClaw ecosystem. 🐾