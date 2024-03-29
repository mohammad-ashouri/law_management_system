@extends('layouts.PanelMaster')

@section('content')
    <main class="flex-1 bg-gray-100 py-6 px-8">
        <div class="mx-auto lg:mr-72">
            <h1 class="text-2xl font-bold mb-4">تعاریف اولیه - مدیریت بر اطلاعات گروه بندی</h1>

            <div class="bg-white rounded shadow p-6 flex flex-col ">
                @can('ایجاد گروه')
                    <button id="new-group-button" type="button"
                            class="px-4 py-2 bg-green-500 w-32 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                        گروه جدید
                    </button>
                    <form id="new-group">
                        @csrf
                        <div class="mb-4 flex items-center">
                            <div class="fixed z-10 inset-0 overflow-y-auto hidden" id="newGroupModal">
                                <div
                                    class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center  sm:block sm:p-0">
                                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                        <div class="absolute inset-0 bg-gray-500 opacity-75 add"></div>
                                    </div>
                                    <div
                                        class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full sm:max-w-[550px]">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                                تعریف گروه جدید
                                            </h3>
                                            <div class="mt-4">
                                                <div class="flex flex-col items-right mb-2">
                                                    <label for="name"
                                                           class="block text-gray-700 text-sm font-bold mb-2">نام
                                                        گروه*:</label>
                                                    <input type="text" id="name" name="name" autocomplete="off"
                                                           class="border rounded-md w-full mb-2 px-3 py-2 text-right"
                                                           placeholder="نام گروه را وارد کنید">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="submit"
                                                    class="px-4 py-2 mr-3 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                                                ثبت گروه جدید
                                            </button>
                                            <button id="cancel-new-group" type="button"
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
                @can('ویرایش گروه')
                    <form id="edit-group">
                        @csrf
                        <div class="mb-4 flex items-center">
                            <div class="fixed z-10 inset-0 overflow-y-auto hidden" id="editGroupModal">
                                <div
                                    class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center  sm:block sm:p-0">
                                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                        <div class="absolute inset-0 bg-gray-500 opacity-75 edit"></div>
                                    </div>
                                    <div
                                        class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full sm:max-w-[550px]">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                                ویرایش گروه
                                            </h3>
                                            <div class="mt-4">
                                                <div class="flex flex-col items-right mb-2">
                                                    <label for="nameForEdit"
                                                           class="block text-gray-700 text-sm font-bold mb-2">نام
                                                        گروه*:</label>
                                                    <input type="text" id="nameForEdit" name="nameForEdit"
                                                           autocomplete="off"
                                                           class="border rounded-md w-full mb-2 px-3 py-2 text-right"
                                                           placeholder="نام گروه را وارد کنید">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <input type="hidden" name="group_id" id="group_id" value="">
                                            <button type="submit"
                                                    class="px-4 py-2 mr-3 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                                                ویرایش
                                            </button>
                                            <button id="cancel-edit-group" type="button"
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
                <table class="w-full border-collapse rounded-lg overflow-hidden text-center datasheet">
                    <thead>
                    <tr class="bg-gradient-to-r from-blue-400 to-purple-500 items-center text-center text-white">
                        <th class="px-6 py-3  font-bold ">ردیف</th>
                        <th class="px-6 py-3  font-bold ">نام گروه</th>
                        <th class="px-6 py-3  font-bold ">عملیات</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                    @foreach ($groupList as $group)
                        <tr class="bg-white">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                {{ $group->name }}
                            </td>
                            <td class="px-6 py-4">
                                @can('ویرایش گروه')
                                    <button type="submit" data-id="{{ $group->id }}"
                                            class="px-4 py-2 mr-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300 GroupControl">
                                        جزئیات و ویرایش
                                    </button>
                                @endcan
                                @can('تغییر وضعیت گروه')
                                    <button type="submit" data-id="{{ $group->id }}"
                                            class="px-4 py-2 mr-3 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-red-300 changeStatusGroupControl">
                                        @if($group->status==1)
                                            غیرفعالسازی
                                        @else
                                            فعالسازی
                                        @endif
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div dir="ltr" class="mt-4 flex justify-center" id="laravel-next-prev">
                    {{ $groupList->links() }}
                </div>
            </div>

        </div>
    </main>
@endsection
