<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;

class SchoolMenuItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('name', 'admin')->firstOrFail();

        // Academic Management Menu
        $academicMenuItem = MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Academic',
            'url' => '',
            'target' => '_self',
            'icon_class' => 'voyager-study',
            'color' => null,
            'parent_id' => null,
            'order' => 2,
        ]);

        // Classes
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Classes',
            'url' => '/admin/classes',
            'target' => '_self',
            'icon_class' => 'voyager-book',
            'color' => null,
            'parent_id' => $academicMenuItem->id,
            'order' => 1,
        ]);

        // Subjects
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Subjects',
            'url' => '/admin/subjects',
            'target' => '_self',
            'icon_class' => 'voyager-bookmark',
            'color' => null,
            'parent_id' => $academicMenuItem->id,
            'order' => 2,
        ]);

        // Timetable
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Timetable',
            'url' => '/admin/timetables',
            'target' => '_self',
            'icon_class' => 'voyager-calendar',
            'color' => null,
            'parent_id' => $academicMenuItem->id,
            'order' => 3,
        ]);

        // People Management
        $peopleMenuItem = MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'People',
            'url' => '',
            'target' => '_self',
            'icon_class' => 'voyager-people',
            'color' => null,
            'parent_id' => null,
            'order' => 3,
        ]);

        // Students
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Students',
            'url' => '/admin/students',
            'target' => '_self',
            'icon_class' => 'voyager-person',
            'color' => null,
            'parent_id' => $peopleMenuItem->id,
            'order' => 1,
        ]);

        // Teachers
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Teachers',
            'url' => '/admin/teachers',
            'target' => '_self',
            'icon_class' => 'voyager-study',
            'color' => null,
            'parent_id' => $peopleMenuItem->id,
            'order' => 2,
        ]);

        // Parents
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Parents',
            'url' => '/admin/parents',
            'target' => '_self',
            'icon_class' => 'voyager-people',
            'color' => null,
            'parent_id' => $peopleMenuItem->id,
            'order' => 3,
        ]);

        // Examinations
        $examsMenuItem = MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Examinations',
            'url' => '',
            'target' => '_self',
            'icon_class' => 'voyager-file-text',
            'color' => null,
            'parent_id' => null,
            'order' => 4,
        ]);

        // Exam Types
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Exam Types',
            'url' => '/admin/exam-types',
            'target' => '_self',
            'icon_class' => 'voyager-categories',
            'color' => null,
            'parent_id' => $examsMenuItem->id,
            'order' => 1,
        ]);

        // Exams
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Exams',
            'url' => '/admin/exams',
            'target' => '_self',
            'icon_class' => 'voyager-file-text',
            'color' => null,
            'parent_id' => $examsMenuItem->id,
            'order' => 2,
        ]);

        // Results
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Results',
            'url' => '/admin/exam-results',
            'target' => '_self',
            'icon_class' => 'voyager-certificate',
            'color' => null,
            'parent_id' => $examsMenuItem->id,
            'order' => 3,
        ]);

        // Finances
        $financeMenuItem = MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Finances',
            'url' => '',
            'target' => '_self',
            'icon_class' => 'voyager-dollar',
            'color' => null,
            'parent_id' => null,
            'order' => 5,
        ]);

        // Fee Types
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Fee Types',
            'url' => '/admin/fee-types',
            'target' => '_self',
            'icon_class' => 'voyager-categories',
            'color' => null,
            'parent_id' => $financeMenuItem->id,
            'order' => 1,
        ]);

        // Invoices
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Invoices',
            'url' => '/admin/invoices',
            'target' => '_self',
            'icon_class' => 'voyager-receipt',
            'color' => null,
            'parent_id' => $financeMenuItem->id,
            'order' => 2,
        ]);

        // Payments
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Payments',
            'url' => '/admin/payments',
            'target' => '_self',
            'icon_class' => 'voyager-wallet',
            'color' => null,
            'parent_id' => $financeMenuItem->id,
            'order' => 3,
        ]);

        // Attendance
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Attendance',
            'url' => '/admin/attendance',
            'target' => '_self',
            'icon_class' => 'voyager-check-circle',
            'color' => null,
            'parent_id' => null,
            'order' => 6,
        ]);

        // Reports
        $reportsMenuItem = MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Reports',
            'url' => '',
            'target' => '_self',
            'icon_class' => 'voyager-bar-chart',
            'color' => null,
            'parent_id' => null,
            'order' => 7,
        ]);

        // Academic Reports
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Academic Reports',
            'url' => '/admin/reports/academic',
            'target' => '_self',
            'icon_class' => 'voyager-book',
            'color' => null,
            'parent_id' => $reportsMenuItem->id,
            'order' => 1,
        ]);

        // Financial Reports
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Financial Reports',
            'url' => '/admin/reports/financial',
            'target' => '_self',
            'icon_class' => 'voyager-dollar',
            'color' => null,
            'parent_id' => $reportsMenuItem->id,
            'order' => 2,
        ]);

        // Attendance Reports
        MenuItem::create([
            'menu_id' => $menu->id,
            'title' => 'Attendance Reports',
            'url' => '/admin/reports/attendance',
            'target' => '_self',
            'icon_class' => 'voyager-check-circle',
            'color' => null,
            'parent_id' => $reportsMenuItem->id,
            'order' => 3,
        ]);
    }
}
