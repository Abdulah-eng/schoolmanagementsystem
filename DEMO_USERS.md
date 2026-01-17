# Demo Users - EduFocus

## Demo Student User
- **Email**: `alex.johnson@student.com`
- **Password**: `password123`
- **Role**: Student
- **Access**: Student Dashboard only

## Demo Admin User
- **Email**: `admin@edufocus.com`
- **Password**: `admin123`
- **Role**: Admin
- **Access**: Admin Dashboard only

## How to Test

### 1. Student Login
1. Go to the home page (`/`)
2. Select "Student" role in the role toggle
3. Use the student credentials above
4. You will be redirected to `/student/dashboard`

### 2. Role-Based Access Control
- **Students** can only access student routes
- **Admins** can only access admin routes
- **Parents** can only access parent routes
- If you try to access a different role's portal, you'll get an error

## 🎯 **Available Features for Demo Student**

### **Core Dashboard**
- **Dashboard**: Personalization settings and adaptive curriculum
- **Focus Mode**: Timer-based study sessions with breathing exercises
- **Learning Center**: AI-powered explanations and quizzes
- **Cognitive Skills**: Brain training games and challenges
- **Life Skills**: Practical problem-solving activities
- **Projects**: Creative project showcase
- **Progress**: Achievement tracking and analytics
- **Settings**: User preferences and account management

## Security Features
- Role-based middleware protection
- Form validation with error display
- Session-based authentication
- CSRF protection
- Password hashing

## Testing Scenarios
1. **Valid Student Login**: Should redirect to student dashboard
2. **Invalid Credentials**: Should show error message
3. **Wrong Role Selection**: Should show role mismatch error
4. **Unauthorized Access**: Should show 403 error for protected routes
5. **Session Management**: Should maintain login state across pages

## Database Structure
- `users` table with role field
- `students` table linked to users
- Proper foreign key relationships
- Role validation middleware
