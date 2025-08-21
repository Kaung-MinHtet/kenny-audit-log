⚙️ Setup
1. Publish and Run Migrations
After installing the package, you need to publish its migrations to your application's database/migrations directory and then run them. This will create the audit_logs table.

2. Prepare Your User Model
The audit_logs table includes a user_id column which is a foreign key to your users table. Ensure your App\Models\User model (or whichever model you use for authentication) is properly configured.

By default, the package assumes your user model is App\Models\User. If your user model is in a different namespace, you might need to adjust the AuditLog model within the package (if you fork it) or override the user() relationship in your AuditLog model if you create a custom one within your application.

🚀 Usage
To start auditing a model, simply use the Auditable trait in your Eloquent model.

Now, whenever a Ticket instance is created, updated, or deleted, an entry will be automatically added to your audit_logs table.

Accessing Audit Logs
You can retrieve audit logs through the AuditLog model provided by the package:

Note: To use $ticket->auditLogs, you'll need to add a morphMany relationship to your Ticket model (and any other auditable model):

🤝 Contributing
Contributions are welcome! Please feel free to open an issue or submit a pull request if you find a bug or have an idea for a new feature.

Fork the repository.

Create your feature branch (git checkout -b feature/AmazingFeature).

Commit your changes (git commit -m 'Add some AmazingFeature').

Push to the branch (git push origin feature/AmazingFeature).

Open a Pull Request.

📄 License
This package is open-sourced software licensed under the .