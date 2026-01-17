# Student Dashboard Structure

## 📁 **File Organization**

### **Views Directory: `resources/views/student/`**

1. **`layouts/app.blade.php`** - Main layout wrapper with sidebar, header, and content area
2. **`components/sidebar.blade.php`** - Navigation sidebar with all student sections
3. **`components/header.blade.php`** - Top header with notifications and user menu
4. **`components/toast.blade.php`** - Toast notification component
5. **`components/modal.blade.php`** - Modal dialog component
6. **`dashboard.blade.php`** - Main dashboard with personalization and quick stats
7. **`focus-mode.blade.php`** - Focus timer and session management
8. **`learning.blade.php`** - AI-powered learning center with explanations and quizzes
9. **`cognitive-skills.blade.php`** - Cognitive training games and challenges
10. **`life-skills.blade.php`** - Life skills and problem-solving activities
11. **`projects.blade.php`** - Project management and tracking
12. **`progress.blade.php`** - Progress tracking and analytics
13. **`settings.blade.php`** - User settings and preferences
14. **`profile.blade.php`** - User profile management

## 🎯 **Core Features by Section**

### 1. **Dashboard** - Personalization & Overview
- Adaptive curriculum settings
- Quick stats and progress overview
- Personalized learning recommendations

### 2. **Focus Mode** - Productivity & Time Management
- Pomodoro timer with customizable sessions
- Session goals and micro-break tracking
- Pre-session breathing exercises
- Progress analytics

### 3. **Learning Center** - AI-Powered Education
- Subject-specific AI explanations
- Interactive quizzes with dynamic content
- Multiple format options (text, visual, audio, video)
- Learning target tracking

### 4. **Cognitive Skills** - Brain Training
- Memory challenges with progressive difficulty
- Planning puzzles and sequencing tasks
- Flexibility tests for adaptive thinking
- Creative writing prompts

### 5. **Life Skills** - Practical Problem Solving
- Real-world scenario challenges
- Decision-making exercises
- Critical thinking activities
- Life application projects

### 6. **Projects** - Long-term Learning
- Project planning and management
- Milestone tracking
- Collaboration tools
- Portfolio building

### 7. **Progress** - Analytics & Insights
- Performance tracking across all areas
- Achievement system
- Learning analytics
- Goal monitoring

### 8. **Settings & Profile** - User Management
- Personal information
- Learning preferences
- Notification settings
- Account management

## 🔧 **Technical Implementation**

### **JavaScript Files: `resources/js/student/`**
- **`dropdowns.js`** - Sidebar toggle and dropdown functionality
- **`toast.js`** - Toast notification system
- **`modal.js`** - Modal dialog management
- **`focus-mode.js`** - Timer and session logic
- **`learning.js`** - AI integration and quiz handling

### **Controllers: `app/Http/Controllers/`**
- **`StudentDashboardController`** - Dashboard data and preferences
- **`StudentFocusController`** - Focus sessions and goals
- **`StudentBreathingController`** - Breathing exercises
- **`StudentLearningController`** - AI learning content
- **`StudentCognitiveController`** - Cognitive skills games

### **Models: `app/Models/`**
- **`User`** - User authentication and relationships
- **`StudentPreference`** - Learning preferences
- **`FocusSession`** - Focus session tracking
- **`BreathingSession`** - Breathing exercise logs
- **`SessionGoal`** - Session-specific goals
- **`MicroBreakLog`** - Micro-break activities
- **`CognitiveSession`** - Cognitive game sessions
- **`CognitiveScore`** - Skill progression tracking
- **`CreativeStory`** - Creative writing submissions

## 🎨 **UI/UX Features**

### **Responsive Design**
- Mobile-first approach with hamburger menu
- Collapsible sidebar for mobile devices
- Touch-friendly interface elements

### **Component System**
- Reusable toast notifications
- Modal dialogs for focused interactions
- Consistent styling with Tailwind CSS

### **Interactive Elements**
- Real-time progress updates
- Dynamic content loading
- Smooth animations and transitions

## 📱 **Mobile Experience**

### **Sidebar Behavior**
- **Desktop**: Fixed sidebar with `lg:pl-72` content offset
- **Mobile**: Collapsible off-canvas drawer
- **Toggle**: Hamburger menu button in header

### **Touch Interactions**
- Swipe gestures for sidebar
- Touch-friendly buttons and inputs
- Responsive grid layouts

## 🔒 **Security & Authentication**

### **Role-Based Access**
- Middleware protection for student routes
- CSRF token validation
- Session management

### **Data Privacy**
- User-specific data isolation
- Secure API endpoints
- Input validation and sanitization
