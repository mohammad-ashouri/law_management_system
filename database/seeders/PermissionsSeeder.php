<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //catalogs
        Permission::create(['name' => 'دسترسی به منوی کاتالوگ اولیه سامانه']);

        Permission::create(['name' => 'لیست انواع مصوبه']);
        Permission::create(['name' => 'ایجاد نوع مصوبه']);
        Permission::create(['name' => 'ویرایش نوع مصوبه']);
        Permission::create(['name' => 'تغییر وضعیت نوع مصوبه']);
        Permission::create(['name' => 'دسترسی به منوی انواع مصوبه']);

        Permission::create(['name' => 'لیست گروه بندی']);
        Permission::create(['name' => 'ایجاد گروه']);
        Permission::create(['name' => 'ویرایش گروه']);
        Permission::create(['name' => 'تغییر وضعیت گروه']);
        Permission::create(['name' => 'دسترسی به منوی گروه بندی']);

        Permission::create(['name' => 'لیست موضوع']);
        Permission::create(['name' => 'ایجاد موضوع']);
        Permission::create(['name' => 'ویرایش موضوع']);
        Permission::create(['name' => 'تغییر وضعیت موضوع']);
        Permission::create(['name' => 'دسترسی به منوی موضوعات']);

        Permission::create(['name' => 'لیست تصویب کنندگان']);
        Permission::create(['name' => 'ایجاد تصویب کننده']);
        Permission::create(['name' => 'ویرایش تصویب کننده']);
        Permission::create(['name' => 'تغییر وضعیت تصویب کننده']);
        Permission::create(['name' => 'دسترسی به منوی تصویب کنندگان']);

        Permission::create(['name' => 'لیست انواع ارتباط']);
        Permission::create(['name' => 'ایجاد ارتباط']);
        Permission::create(['name' => 'ویرایش ارتباط']);
        Permission::create(['name' => 'تغییر وضعیت ارتباط']);
        Permission::create(['name' => 'دسترسی به منوی انواع ارتباط']);

        Permission::create(['name' => 'لیست نقش ها']);
        Permission::create(['name' => 'ایجاد نقش']);
        Permission::create(['name' => 'ویرایش نقش']);
        Permission::create(['name' => 'نمایش جزئیات نقش']);
        Permission::create(['name' => 'حذف نقش']);
        Permission::create(['name' => 'دسترسی به منوی نقش های کاربری']);

        //Law Manager
        Permission::create(['name' => 'لیست قوانین و مصوبات']);
        Permission::create(['name' => 'ایجاد مصوبه']);
        Permission::create(['name' => 'ویرایش مصوبه']);
        Permission::create(['name' => 'نمایش تاریخچه مصوبه']);
        Permission::create(['name' => 'جستجوی مصوبه']);
        Permission::create(['name' => 'حذف مصوبه']);
        Permission::create(['name' => 'دسترسی به منوی قوانین و مصوبات']);

        //Users Manager
        Permission::create(['name' => 'لیست کاربران']);
        Permission::create(['name' => 'ایجاد کاربر']);
        Permission::create(['name' => 'ویرایش کاربر']);
        Permission::create(['name' => 'تغییر وضعیت کاربر']);
        Permission::create(['name' => 'تغییر وضعیت نیازمند به تغییر رمز عبور']);
        Permission::create(['name' => 'بازنشانی رمز عبور کاربر']);
        Permission::create(['name' => 'جستجوی کاربر']);
        Permission::create(['name' => 'دسترسی به منوی مدیریت کاربران']);

        $interviewerRole = Role::create(['name' => 'ادمین کل']);
        $interviewerRole->givePermissionTo([
            'لیست نقش ها',
            'ایجاد نقش',
            'ویرایش نقش',
            'نمایش جزئیات نقش',
            'حذف نقش',
            'دسترسی به منوی نقش های کاربری',
        ]);

        $role = Role::where('name', 'ادمین کل')->first();
        $users = User::get();
        foreach ($users as $user) {
            $user = User::find($user->id);
            $user->assignRole([$role->id]);
        }
    }
}
