# Voyager UI Components Guide

This guide provides comprehensive documentation on the Voyager UI components available in the School Management System. These components ensure a consistent, modern, and responsive user interface across the entire application.

## Table of Contents

1. [Design Variables](#1-design-variables)
2. [Layout Components](#2-layout-components)
3. [Form Elements](#3-form-elements)
4. [Card Components](#4-card-components)
5. [Navigation Components](#5-navigation-components)
6. [Animated Elements](#6-animated-elements)
7. [Modals & Dialogs](#7-modals--dialogs)
8. [Data Display Components](#8-data-display-components)
9. [Helper Utilities](#9-helper-utilities)
10. [Implementation Examples](#10-implementation-examples)

## 1. Design Variables

The Voyager UI system uses CSS variables to ensure color consistency throughout the application:

```css
:root {
    --voyager-primary: #22A7F0;      /* Primary blue color */
    --voyager-secondary: #2B3D51;    /* Secondary dark blue */
    --voyager-dark: #1A2226;         /* Dark background color */
    --voyager-bg: #F9FAFC;           /* Light background color */
    --voyager-sidebar: #2A3F54;      /* Sidebar background */
    --voyager-accent: #62a8ea;       /* Accent/highlight color */
}
```

To use these variables in custom CSS:

```css
.my-element {
    background-color: var(--voyager-primary);
    color: white;
}
```

## 2. Layout Components

### Voyager Panel

Modern panel component with drop shadow and rounded corners:

```html
<div class="voyager-panel">
    <div class="voyager-panel-heading">
        <h3 class="voyager-panel-title">
            <i class="voyager-lock"></i> Panel Title
        </h3>
    </div>
    <div class="voyager-panel-body">
        Panel content goes here...
    </div>
    <div class="voyager-panel-footer">
        Footer content...
    </div>
</div>
```

### Voyager Container

Container with proper padding and responsive behavior:

```html
<div class="voyager-container">
    Content here...
</div>
```

### Voyager Row & Columns

Grid system based on Bootstrap's row and column system:

```html
<div class="voyager-row">
    <div class="voyager-col-md-6">
        Left column content
    </div>
    <div class="voyager-col-md-6">
        Right column content
    </div>
</div>
```

## 3. Form Elements

### Voyager Form

Form wrapper with consistent styling:

```html
<form class="voyager-form">
    <!-- Form elements go here -->
</form>
```

### Voyager Form Group

Group containing a label and input:

```html
<div class="voyager-form-group">
    <label for="field-id" class="voyager-form-label">Field Label</label>
    <input type="text" id="field-id" class="voyager-form-control">
</div>
```

### Voyager Input Group

Input with icon or addon:

```html
<div class="input-group voyager-input-group">
    <span class="input-group-text voyager-input-group-text">
        <i class="fas fa-envelope"></i>
    </span>
    <input type="email" class="form-control voyager-form-control">
</div>
```

### Voyager Button

Stylized buttons with animations:

```html
<button class="voyager-btn voyager-btn-primary">Primary Button</button>
<button class="voyager-btn voyager-btn-secondary">Secondary Button</button>
<button class="voyager-btn voyager-btn-success">Success Button</button>
<button class="voyager-btn voyager-btn-danger">Danger Button</button>
```

## 4. Card Components

### Voyager Card

Basic card component with optional image:

```html
<div class="voyager-card">
    <img src="image.jpg" class="voyager-card-img-top">
    <div class="voyager-card-body">
        <h5 class="voyager-card-title">Card Title</h5>
        <p class="voyager-card-text">Card content goes here...</p>
    </div>
    <div class="voyager-card-footer">
        Footer content...
    </div>
</div>
```

### Voyager Info Card

Specialized card for displaying summary information:

```html
<div class="voyager-info-card voyager-info-card-primary">
    <div class="voyager-info-card-icon">
        <i class="fas fa-users"></i>
    </div>
    <div class="voyager-info-card-content">
        <h4 class="voyager-info-card-title">1,245</h4>
        <p class="voyager-info-card-text">Total Students</p>
    </div>
</div>
```

### Voyager Login Card

Special card layout for login pages:

```html
<div class="voyager-login-card fade-in-up">
    <div class="row g-0">
        <div class="col-lg-6 voyager-login-left">
            <!-- Left side content -->
        </div>
        <div class="col-lg-6 voyager-login-right">
            <!-- Form content -->
        </div>
    </div>
</div>
```

## 5. Navigation Components

### Voyager Navbar

Top navigation bar:

```html
<nav class="voyager-navbar">
    <div class="voyager-navbar-brand">
        <img src="logo.png" alt="Logo">
    </div>
    <ul class="voyager-navbar-nav">
        <li class="voyager-nav-item">
            <a href="#" class="voyager-nav-link">Home</a>
        </li>
        <!-- More nav items -->
    </ul>
</nav>
```

### Voyager Sidebar

Side navigation menu:

```html
<div class="voyager-sidebar">
    <div class="voyager-sidebar-header">
        <img src="logo.png" alt="Logo">
    </div>
    <ul class="voyager-sidebar-nav">
        <li class="voyager-sidebar-item">
            <a href="#" class="voyager-sidebar-link">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <!-- More sidebar items -->
    </ul>
</div>
```

### Voyager Tabs

Tab navigation:

```html
<ul class="voyager-tabs">
    <li class="voyager-tab-item">
        <a href="#tab1" class="voyager-tab-link active">Tab 1</a>
    </li>
    <li class="voyager-tab-item">
        <a href="#tab2" class="voyager-tab-link">Tab 2</a>
    </li>
</ul>

<div class="voyager-tab-content">
    <div id="tab1" class="voyager-tab-pane active">
        Content for Tab 1
    </div>
    <div id="tab2" class="voyager-tab-pane">
        Content for Tab 2
    </div>
</div>
```

## 6. Animated Elements

### Fade In Up Animation

Add fade-in animation to any element:

```html
<div class="fade-in-up">
    Content will fade in from bottom to top
</div>
```

### Hover Effects

Various hover effects for interactive elements:

```html
<div class="voyager-hover-lift">Lifts slightly on hover</div>
<div class="voyager-hover-glow">Glows on hover</div>
<a href="#" class="voyager-link">Link with underline animation</a>
```

## 7. Modals & Dialogs

### Voyager Modal

Custom styled modal dialog:

```html
<div class="voyager-modal">
    <div class="voyager-modal-dialog">
        <div class="voyager-modal-content">
            <div class="voyager-modal-header">
                <h5 class="voyager-modal-title">Modal Title</h5>
                <button type="button" class="voyager-close">×</button>
            </div>
            <div class="voyager-modal-body">
                Modal content goes here...
            </div>
            <div class="voyager-modal-footer">
                <button type="button" class="voyager-btn voyager-btn-secondary">Close</button>
                <button type="button" class="voyager-btn voyager-btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
```

### Voyager Alert

Alert messages with different styles:

```html
<div class="voyager-alert voyager-alert-success">
    <i class="voyager-check"></i> Success! Your changes have been saved.
</div>

<div class="voyager-alert voyager-alert-danger">
    <i class="voyager-x"></i> Error! Please fix the issues and try again.
</div>
```

## 8. Data Display Components

### Voyager Table

Enhanced table styling:

```html
<table class="voyager-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>John Doe</td>
            <td>john@example.com</td>
            <td>
                <button class="voyager-btn voyager-btn-sm voyager-btn-primary">Edit</button>
            </td>
        </tr>
    </tbody>
</table>
```

### Voyager Badge

Status indicator badges:

```html
<span class="voyager-badge voyager-badge-primary">New</span>
<span class="voyager-badge voyager-badge-success">Approved</span>
<span class="voyager-badge voyager-badge-warning">Pending</span>
<span class="voyager-badge voyager-badge-danger">Rejected</span>
```

### Voyager Progress Bar

Progress indicators:

```html
<div class="voyager-progress">
    <div class="voyager-progress-bar" role="progressbar" style="width: 75%">75%</div>
</div>
```

## 9. Helper Utilities

### Icon Circles

Icon with circular background:

```html
<span class="voyager-icon-circle">
    <i class="fas fa-check"></i>
</span>
```

### Dividers

Content dividers with optional text:

```html
<div class="voyager-divider">
    <span>OR</span>
</div>
```

### Spacing Utilities

Consistent margin and padding:

```html
<div class="voyager-m-2">Margin level 2</div>
<div class="voyager-p-3">Padding level 3</div>
<div class="voyager-mt-4">Margin top level 4</div>
<div class="voyager-mb-3">Margin bottom level 3</div>
```

## 10. Implementation Examples

### Login Page Example

Here's a complete implementation of a login page using Voyager UI components:

```html
<body class="voyager-login">
    <div class="container voyager-login-container">
        <div class="voyager-login-card fade-in-up">
            <div class="row g-0">
                <div class="col-lg-6 voyager-login-left">
                    <div class="voyager-logo-container">
                        <img src="/images/school-logo.png" alt="School Logo">
                        <h2>School Portal</h2>
                    </div>
                    
                    <div class="voyager-welcome-text">
                        <h2>Welcome to Your Portal</h2>
                        <p>Access your academic information, grades, assignments, and communicate with teachers all in one place.</p>
                    </div>
                    
                    <ul class="voyager-features-list">
                        <li><i class="fas fa-graduation-cap"></i>View Grades & Progress</li>
                        <li><i class="fas fa-tasks"></i>Track Assignments</li>
                        <li><i class="fas fa-calendar-alt"></i>Check Attendance</li>
                        <li><i class="fas fa-envelope"></i>Communicate with Teachers</li>
                        <li><i class="fas fa-bullhorn"></i>Stay Updated with Announcements</li>
                    </ul>
                </div>
                
                <div class="col-lg-6 voyager-login-right">
                    <div class="voyager-login-form">
                        <h3>Sign In to Your Account</h3>
                        <p class="subtitle">Enter your credentials to access the portal</p>

                        <form action="/login" method="POST">
                            <div class="voyager-form-group">
                                <label for="email" class="voyager-form-label">Email Address</label>
                                <div class="input-group voyager-input-group">
                                    <span class="input-group-text voyager-input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" name="email" id="email" class="form-control voyager-form-control">
                                </div>
                            </div>

                            <div class="voyager-form-group">
                                <label for="password" class="voyager-form-label">Password</label>
                                <div class="input-group voyager-input-group">
                                    <span class="input-group-text voyager-input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" name="password" id="password" class="form-control voyager-form-control">
                                </div>
                            </div>

                            <button type="submit" class="voyager-btn">
                                <i class="fas fa-sign-in-alt me-2"></i> Sign In to Portal
                            </button>

                            <div class="text-center mt-3">
                                <a href="/forgot-password" class="voyager-link">
                                    <i class="fas fa-key me-1"></i>Forgot your password?
                                </a>
                            </div>
                        </form>

                        <div class="voyager-divider">
                            <span>Need help?</span>
                        </div>

                        <div class="voyager-quick-access">
                            <a href="/" class="voyager-quick-access-btn">
                                <i class="fas fa-home"></i>
                                <div class="mt-1">Home</div>
                            </a>
                            <a href="/admin" class="voyager-quick-access-btn">
                                <i class="fas fa-cog"></i>
                                <div class="mt-1">Admin</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
```

### Dashboard Panel Example

```html
<div class="voyager-panel">
    <div class="voyager-panel-heading">
        <h3 class="voyager-panel-title">
            <i class="fas fa-chart-line"></i> Academic Performance
        </h3>
    </div>
    <div class="voyager-panel-body">
        <div class="row">
            <div class="col-md-6">
                <h4>Current Semester GPA</h4>
                <div class="voyager-progress">
                    <div class="voyager-progress-bar bg-success" style="width: 85%">3.4/4.0</div>
                </div>
            </div>
            <div class="col-md-6">
                <h4>Attendance Rate</h4>
                <div class="voyager-progress">
                    <div class="voyager-progress-bar bg-info" style="width: 92%">92%</div>
                </div>
            </div>
        </div>
    </div>
</div>
```

## Usage Guidelines

1. **Include the CSS**: Make sure to include the Voyager UI CSS in your Blade templates:
   ```html
   <link rel="stylesheet" href="{{ asset('css/voyager-ui/login.css') }}">
   ```

2. **Combine with Bootstrap**: Voyager UI components are designed to work alongside Bootstrap, so include both:
   ```html
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/voyager-ui/login.css') }}">
   ```

3. **Font Awesome Icons**: Many components use Font Awesome icons. Include the Font Awesome library:
   ```html
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   ```

## Extending the UI System

To add new components or modify existing ones, edit the CSS files in the `public/css/voyager-ui/` directory. Consider creating additional specialized CSS files for different sections of your application while maintaining the core design variables.
