# Equipment Lending System - Quick Start Guide

## 🚀 Quick Start (5 Minutes)

### Step 1: Start the Application
```bash
cd equipment-lending-system
php artisan serve
```

### Step 2: Access the Application
Open your browser and go to: **http://localhost:8000**

### Step 3: Login with Demo Accounts

#### Admin Account (Full Access)
- **Username**: `admin`
- **Password**: `Admin123`
- **Can do**: Everything - manage users, equipment, categories, view analytics

#### Staff Account (Approve Requests)
- **Username**: `staff`
- **Password**: `Staff123`
- **Can do**: Approve borrowing requests, approve returns, generate reports

#### Borrower Account (Borrow Equipment)
- **Username**: `borrower`
- **Password**: `Borrower123`
- **Can do**: View equipment, submit borrowing requests, track history

## 📋 What's Already Set Up

✅ Database migrated and seeded with demo data
✅ 3 user accounts (admin, staff, borrower)
✅ 4 equipment categories
✅ 8 sample equipment items
✅ All features fully functional

## 🎯 Try These Features

### As Admin:
1. **Dashboard** - View system statistics and analytics
2. **Users** - Create, edit, or delete users
3. **Categories** - Manage equipment categories
4. **Equipment** - Add new equipment with images
5. **Reports** - Generate borrowing reports

### As Staff:
1. **Dashboard** - See pending requests
2. **Borrowings** - Approve or reject requests
3. **Returns** - Approve equipment returns
4. **Reports** - Generate reports

### As Borrower:
1. **Dashboard** - Browse available equipment
2. **Equipment** - Search and filter equipment
3. **Borrow** - Submit borrowing requests
4. **My Borrowings** - Track your borrowing history

## 🔄 Complete Workflow Example

1. **Login as Borrower** (`borrower` / `Borrower123`)
   - Go to Equipment page
   - Click "Borrow" on any available item
   - Fill in return date and submit

2. **Login as Staff** (`staff` / `Staff123`)
   - Go to Dashboard
   - See the pending request
   - Click "Approve"

3. **Login as Borrower** again
   - Go to My Borrowings
   - See the approved borrowing
   - Click "Initiate Return"

4. **Login as Staff** again
   - Go to Returns page
   - Approve the return
   - Select equipment condition

5. **Login as Admin** (`admin` / `Admin123`)
   - Go to Reports
   - Generate a report to see the complete transaction

## 🎨 UI Features to Explore

- **Responsive Design** - Try resizing your browser
- **Search & Filter** - Use the equipment search
- **Toast Notifications** - Watch for success/error messages
- **Status Badges** - Color-coded status indicators
- **Modal Forms** - Category management uses modals
- **Empty States** - See helpful messages when no data exists

## 📊 Sample Data Included

### Equipment Categories:
- Electronics
- Tools
- Sports Equipment
- Office Equipment

### Sample Equipment:
- Laptop Dell XPS 15
- Projector Epson EB-X41
- Power Drill Bosch GSB 13 RE
- Digital Camera Canon EOS 90D
- Basketball
- Whiteboard Portable
- Microphone Shure SM58 (in maintenance)
- Ladder Aluminum 6ft

## 🛠️ Common Tasks

### Add New Equipment (Admin)
1. Login as admin
2. Go to Equipment → Add Equipment
3. Fill in details and upload image
4. Submit

### Approve a Borrowing Request (Staff)
1. Login as staff
2. Go to Dashboard or Borrowings
3. Click "Approve" on pending request

### Generate a Report (Admin/Staff)
1. Go to Reports
2. Select filters (date range, status, user)
3. Click "Generate Report"
4. Click "Print Report" for print view

## 🔧 Troubleshooting

### Can't login?
- Make sure you're using the correct credentials
- Check that database is seeded: `php artisan db:seed --class=DemoDataSeeder`

### Images not showing?
- Run: `php artisan storage:link`

### Page not found?
- Make sure server is running: `php artisan serve`
- Check you're accessing: `http://localhost:8000`

## 📱 Mobile Testing

The application is fully responsive! Try accessing it on:
- Desktop (1024px+)
- Tablet (768px - 1023px)
- Mobile (320px - 767px)

## 🎓 Learning the System

### For Developers:
- Check `routes/web.php` for all routes
- Controllers are in `app/Http/Controllers/`
- Services contain business logic in `app/Services/`
- Views are in `resources/views/`

### For Users:
- Each role has a different dashboard
- Navigation menu adapts to your role
- All actions show toast notifications
- Forms have validation with helpful error messages

## 🚀 Next Steps

1. **Customize**: Modify colors in `tailwind.config.js`
2. **Add Data**: Create more categories and equipment
3. **Test Workflows**: Try the complete borrowing cycle
4. **Explore Reports**: Generate reports with different filters
5. **Check Analytics**: View admin dashboard statistics

## 💡 Tips

- Use the search feature to quickly find equipment
- Filter equipment by category or status
- Check the dashboard for quick overview
- Recent activity shows on admin dashboard
- All forms have validation - try submitting empty forms to see error messages

## 📞 Need Help?

- Check `README_COMPLETE.md` for detailed documentation
- Review the specification files in `.kiro/specs/equipment-lending-system/`
- All features are fully implemented and tested

---

**Enjoy using the Equipment Lending System! 🎉**
