# Unhinged Support Ticket System

Thank you for the opportunity to review my skills and development practices. I have created this system for you to review and give feedback on. You can see the project designs by going to 
<b>https://www.figma.com/design/fRk6iODOKuEmK7iVz1FF0D/Unhinged-Database?node-id=0-1&t=EYfFQVSjUuH8npQs-1</b>

## Requirements

- Docker Desktop
- Composer
- Node.js
- A sense of Humor (optional but not required)

## Setup Instructions

1. Clone the repository:
```bash
git clone https://github.com/Inskioo/The-Unhinged-Ticket-System
cd unhinged
```

2. Make the build script executable:
```bash
chmod +x setup.sh
```

3. Run the build script:
```bash
./setup.sh
```

The build script will:
- Verify Docker is running
- Install Laravel dependencies
- Configure Laravel Sail
- Set up the database
- Install npm packages
- Build frontend assets

The application will be available at http://localhost:8080

Or visit https://unhinged.inski.io for the live version.
