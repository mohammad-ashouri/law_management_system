@php use App\Models\Province; @endphp
@extends('layouts.PanelMaster')

@section('content')
    <main class="flex-1 bg-gray-100 py-6 px-8 ">
        <div class=" mx-auto lg:mr-72">
            <h1 class="text-2xl font-bold mb-4">مدیریت کاربران</h1>

            <div class="flex">
                @can('ایجاد کاربر')
                    <button id="new-user-button" type="button"
                            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                        کاربر جدید
                    </button>
                    <form id="new-user">
                        @csrf
                        <div class="mt-4 mb-4 flex items-center">
                            <div class="fixed z-10 inset-0 overflow-y-auto hidden" id="newUserModal">
                                <div
                                        class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center  sm:block sm:p-0">
                                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                        <div class="absolute inset-0 bg-gray-500 opacity-75 add"></div>
                                    </div>

                                    <div
                                            class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full sm:max-w-[550px]">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                                تعریف کاربر جدید
                                            </h3>
                                            <div class="mt-4">
                                                <div class="flex flex-col items-right mb-4">
                                                    <label for="name"
                                                           class="block text-gray-700 text-sm font-bold mb-2">نام*:</label>
                                                    <input type="text" id="name" name="name" autocomplete="off"
                                                           class="border rounded-md w-full mb-4 px-3 py-2 text-right"
                                                           placeholder="نام کاربر">
                                                    <label for="family"
                                                           class="block text-gray-700 text-sm font-bold mb-2">نام
                                                        خانوادگی*:</label>
                                                    <input type="text" id="family" name="family" autocomplete="off"
                                                           class="border rounded-md w-full mb-4 px-3 py-2 text-right"
                                                           placeholder="نام خانوادگی کاربر">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="username"
                                                           class="block text-gray-700 text-sm font-bold mb-2">نام
                                                        کاربری*:</label>
                                                    <input type="text" id="username" name="username" autocomplete="off"
                                                           class="border rounded-md w-full px-3 py-2 text-left"
                                                           placeholder="نام کاربری">
                                                </div>
                                                <div class="flex flex-col mb-4">
                                                    <label for="password"
                                                           class="block text-gray-700 text-sm font-bold mb-2">رمز
                                                        عبور*:</label>
                                                    <input type="password" autocomplete="new-password" name="password"
                                                           id="password"
                                                           class="border rounded-md w-full mb-4 px-3 py-2 text-left"
                                                           placeholder="رمزعبور">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="type"
                                                           class="block text-gray-700 text-sm font-bold mb-2">نقش
                                                        کاربر:</label>
                                                    <select id="type" class="border rounded-md w-full px-3 py-2"
                                                            name="type">
                                                        <option value="" disabled selected>انتخاب کنید</option>
                                                        @foreach($allRoles as $role)
                                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="submit"
                                                    class="px-4 py-2 mr-3 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                                                ثبت کاربر جدید
                                            </button>
                                            <button id="cancel-new-user" type="button"
                                                    class="mt-3 w-full inline-flex justify-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-blue-300 sm:mt-0 sm:w-auto">
                                                انصراف
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endcan

                @can('ویرایش کاربر')
                    <button id="edit-user-button" type="button"
                            class="px-4 py-2 mr-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                        ویرایش کاربر
                    </button>
                    <form id="edit-user">
                        @csrf
                        <div class="mt-4 mb-4 flex items-center">
                            <div class="fixed z-10 inset-0 overflow-y-auto hidden" id="editUserModal">
                                <div
                                        class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center  sm:block sm:p-0">
                                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                        <div class="absolute inset-0 bg-gray-500 opacity-75 edit"></div>
                                    </div>

                                    <div
                                            class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full sm:max-w-[550px]">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                                ویرایش کاربر
                                            </h3>
                                            <div class="mt-4">
                                                <div class="flex">
                                                    <label for="userIdForEdit"
                                                           class="w-60 mt-3 text-gray-700 text-sm font-bold mb-2">کاربر
                                                        را انتخاب کنید</label>
                                                    <select id="userIdForEdit" name="userIdForEdit"
                                                            class=" w-full inline-block bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                                        <option value="" selected disabled>انتخاب کنید</option>
                                                        @foreach($userList as $user)
                                                            <option
                                                                    value="{{ $user->id }}">{{ $user->name.' '.$user->family . ' ('.$user->username.')'}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mt-4" id="userEditDiv" hidden>
                                                <div class="flex flex-col items-right mb-4">
                                                    <label for="editedName"
                                                           class="block text-gray-700 text-sm font-bold mb-2">نام*:</label>
                                                    <input type="text" id="editedName" name="editedName"
                                                           autocomplete="off"
                                                           class="border rounded-md w-full mb-4 px-3 py-2 text-right"
                                                           placeholder="نام کاربر">
                                                    <label for="editedFamily"
                                                           class="block text-gray-700 text-sm font-bold mb-2">نام
                                                        خانوادگی*:</label>
                                                    <input type="text" id="editedFamily" name="editedFamily"
                                                           autocomplete="off"
                                                           class="border rounded-md w-full mb-4 px-3 py-2 text-right"
                                                           placeholder="نام خانوادگی کاربر">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="editedType"
                                                           class="block text-gray-700 text-sm font-bold mb-2">نقش
                                                        کاربر:</label>
                                                    <select id="editedType" class="border rounded-md w-full px-3 py-2"
                                                            name="editedType">
                                                        <option value="" disabled selected>انتخاب کنید</option>
                                                        @foreach($allRoles as $role)
                                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="submit"
                                                    class="px-4 py-2 mr-3 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                                                ویرایش
                                            </button>
                                            <button id="cancel-edit-user" type="button"
                                                    class="mt-3 w-full inline-flex justify-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-blue-300 sm:mt-0 sm:w-auto">
                                                انصراف
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endcan
            </div>
            <div class="bg-white rounded shadow p-6 flex flex-col items-center">
                @can('جستجوی کاربر')
                    <div class=" mb-4 flex w-full">
                        <label for="search-Username-UserManager" class="block mt-3 text-sm font-bold text-gray-700">جستجو
                            در
                            کد
                            کاربری:</label>
                        <input id="search-Username-UserManager" autocomplete="off"
                               placeholder="لطفا کد کاربری را وارد نمایید."
                               type="text" name="search-Username-UserManager"
                               class="ml-4 mt-1 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"/>
                        <label for="search-type-UserManager"
                               class="block text-gray-700 text-sm font-bold mt-3 ">جستجو در نقش
                            کاربری:</label>
                        <select id="search-type-UserManager" class="border rounded-md  px-3 "
                                name="search-type-UserManager">
                            <option value="">بدون فیلتر</option>
                            @foreach($userList->pluck('type', 'subject')->unique() as $type => $subject)
                                <option value="{{ $subject }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                @endcan
                <div class="max-w-full overflow-x-auto">
                    <table class="w-full border-collapse rounded-lg overflow-hidden text-center">
                        <thead>
                        <tr class="bg-gradient-to-r from-blue-400 to-purple-500 items-center text-center text-white">
                            <th class=" px-6 py-3  font-bold ">کد کاربری</th>
                            <th class=" px-6 py-3  font-bold ">مشخصات</th>
                            <th class=" px-3 py-3  font-bold ">نوع کاربری</th>
                            <th class=" px-3 py-3  font-bold ">فعال/غیرفعال</th>
                            <th class=" px-3 py-3  font-bold ">نیازمند تغییر رمز عبور</th>
                            <th class=" px-3 py-3  font-bold ">بازنشانی رمز عبور</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300">
                        @foreach ($userList as $user)
                            <tr class="bg-white">
                                <td class="px-6 py-4">{{ $user->username }}</td>
                                <td class="px-6 py-4">{{ $user->name . ' ' . $user->family  }}</td>
                                <td class="px-3 py-4">
                                    @php
                                        $roles=$user->roles
                                    @endphp
                                    @foreach ($roles as $role)
                                        {{ $role->name }}
                                    @endforeach
                                </td>
                                <td class="px-3 py-4">
                                    @can('تغییر وضعیت کاربر')
                                        <button type="submit" data-username="{{ $user->username }}"
                                                @php
                                                    if ($user->active==1){
                                                        echo "class='px-2 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-blue-300 ASUM'";
                                                        echo "data-active=1";
                                                    }elseif ($user->active==0){
                                                        echo "class='px-2 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300 ASUM'";
                                                        echo "data-active=0";
                                                    }
                                                @endphp
                                        >
                                            @php
                                                if ($user->active==1){
                                                    echo 'غیرفعال‌سازی';
                                                }elseif ($user->active==0){
                                                    echo 'فعال‌سازی';
                                                }
                                            @endphp
                                        </button>
                                    @endcan
                                </td>
                                <td class="px-3 py-4">
                                    @can('تغییر وضعیت نیازمند به تغییر رمز عبور')
                                        <button type="button" data-ntcp-username="{{ $user->username }}"
                                                @php
                                                    if ($user->ntcp==1){
                                                        echo "class='px-2 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-blue-300 ntcp'";
                                                        echo "data-ntcp=1";
                                                    }elseif ($user->ntcp==0){
                                                        echo "class='px-2 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300 ntcp'";
                                                        echo "data-ntcp=0";
                                                }
                                                @endphp
                                        >
                                            @php
                                                if ($user->NTCP==1){
                                                    echo 'می باشد';
                                                }elseif ($user->NTCP==0){
                                                    echo 'نمی باشد';
                                                }
                                            @endphp
                                        </button>
                                    @endcan
                                </td>
                                <td class="px-3 py-4">
                                    @can('بازنشانی رمز عبور کاربر')
                                        <button type="submit" data-rp-username="{{ $user->username }}"
                                                class="class='px-2 py-2 p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300 rp"
                                        >
                                            بازنشانی رمز
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div dir="ltr" class="mt-4 flex justify-center" id="laravel-next-prev">
                    {{ $userList->links() }}
                </div>

            </div>

        </div>
    </main>
@endsection
