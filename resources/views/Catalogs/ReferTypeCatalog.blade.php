@extends('layouts.PanelMaster')

@section('content')
    <main class="flex-1 bg-gray-100 py-6 px-8">
        <div class="mx-auto lg:mr-72">
            <h1 class="text-2xl font-bold mb-4">تعاریف اولیه - مدیریت بر اطلاعات انواع ارتباط</h1>

            <div class="bg-white rounded shadow p-6 flex flex-col ">
                @can('ایجاد ارتباط')
                    <button id="new-referTypes-button" type="button"
                            class="px-4 py-2 bg-green-500 w-40 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                        انواع ارتباط جدید
                    </button>
                    <form id="new-referTypes">
                        @csrf
                        <div class="mb-4 flex items-center">
                            <div class="fixed z-10 inset-0 overflow-y-auto hidden" id="newReferTypeModal">
                                <div
                                    class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center  sm:block sm:p-0">
                                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                        <div class="absolute inset-0 bg-gray-500 opacity-75 add"></div>
                                    </div>
                                    <div
                                        class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full sm:max-w-[550px]">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                                تعریف انواع ارتباط جدید
                                            </h3>
                                            <div class="mt-4">
                                                <div class="flex flex-col items-right mb-2">
                                                    <label for="name"
                                                           class="block text-gray-700 text-sm font-bold mb-2">
                                                        انواع ارتباط*:</label>
                                                    <input type="text" id="name" name="name" autocomplete="off"
                                                           class="border rounded-md w-full mb-2 px-3 py-2 text-right"
                                                           placeholder="انواع ارتباط را وارد کنید">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="submit"
                                                    class="px-4 py-2 mr-3 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                                                ثبت انواع ارتباط جدید
                                            </button>
                                            <button id="cancel-new-referTypes" type="button"
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
                @can('ویرایش ارتباط')
                    <form id="edit-referTypes">
                        @csrf
                        <div class="mb-4 flex items-center">
                            <div class="fixed z-10 inset-0 overflow-y-auto hidden" id="editReferTypeModal">
                                <div
                                    class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center  sm:block sm:p-0">
                                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                        <div class="absolute inset-0 bg-gray-500 opacity-75 edit"></div>
                                    </div>
                                    <div
                                        class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full sm:max-w-[550px]">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                                ویرایش انواع ارتباط
                                            </h3>
                                            <div class="mt-4">
                                                <div class="flex flex-col items-right mb-2">
                                                    <label for="nameForEdit"
                                                           class="block text-gray-700 text-sm font-bold mb-2">
                                                        انواع ارتباط*:</label>
                                                    <input type="text" id="nameForEdit" name="nameForEdit"
                                                           autocomplete="off"
                                                           class="border rounded-md w-full mb-2 px-3 py-2 text-right"
                                                           placeholder="انواع ارتباط را وارد کنید">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <input type="hidden" name="referTypes_id" id="referTypes_id" value="">
                                            <button type="submit"
                                                    class="px-4 py-2 mr-3 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                                                ویرایش
                                            </button>
                                            <button id="cancel-edit-referTypes" type="button"
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
                        <th class="px-6 py-3  font-bold ">انواع ارتباط</th>
                        <th class="px-6 py-3  font-bold ">عملیات</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                    @foreach ($referTypeList as $referType)
                        <tr class="bg-white">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                {{ $referType->name }}
                            </td>
                            <td class="px-6 py-4">
                                @can('ویرایش ارتباط')
                                    <button type="submit" data-id="{{ $referType->id }}"
                                            class="px-4 py-2 mr-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300 ReferTypeControl">
                                        جزئیات و ویرایش
                                    </button>
                                @endcan
                                @can('تغییر وضعیت ارتباط')
                                    <button type="submit" data-id="{{ $referType->id }}"
                                            class="px-4 py-2 mr-3 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-red-300 changeStatusReferTypeControl">
                                        @if($referType->status==1)
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
                    {{ $referTypeList->links() }}
                </div>
            </div>

        </div>
    </main>
@endsection
