@php use App\Models\Catalogs\LawGroup; @endphp
@extends('layouts.PanelMaster')

@section('content')
    <main class="flex-1 bg-gray-100 py-6 px-8">
        <div class="mx-auto lg:mr-72">
            <h1 class="text-2xl font-bold mb-4">مدیریت بر اطلاعات قوانین و مصوبات</h1>

            <div class="bg-white rounded shadow p-6 flex flex-col ">
                <a href="/Laws/new">
                    <button type="button"
                            class="px-4 py-2 mb-3 bg-green-500 w-24 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                        جدید
                    </button>
                </a>
                <table class="w-full border-collapse rounded-lg overflow-hidden text-center datasheet">
                    <thead>
                    <tr class="bg-gradient-to-r from-blue-400 to-purple-500 items-center text-center text-white">
                        <th class="px-6 py-3  font-bold ">ردیف</th>
                        <th class="px-6 py-3  font-bold ">عنوان</th>
                        <th class="px-6 py-3  font-bold ">نوع مصوبه</th>
                        <th class="px-6 py-3  font-bold ">گروه</th>
                        <th class="px-6 py-3  font-bold ">موضوع</th>
                        <th class="px-6 py-3  font-bold ">تاریخ تصویب</th>
                        <th class="px-6 py-3  font-bold ">عملیات</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                    @foreach ($lawList as $law)
                        <tr class="bg-white">
                            <td class="px-6 py-1">{{ $loop->iteration }}</td>
                            <td class="px-6 py-1">{{ $law->title }}</td>
                            <td class="px-6 py-1">{{ $law->type->name }}</td>
                            <td class="px-6 py-1">{{ $law->group->name }}</td>
                            <td class="px-6 py-1">{{ $law->topic->name }}</td>
                            <td class="px-6 py-1">{{ $law->approval_date }}</td>
                            <td class="px-6 py-1">
                                <form action="Laws/edit/{{$law->id}}" method="get">
                                    <button type="submit"
                                            class="px-4 py-2 mr-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300 LawControl">
                                        جزئیات و ویرایش
                                    </button>
                                </form>
                                <button type="submit" data-id="{{ $law->id }}"
                                        class="px-4 py-2 mr-3 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-red-300 changeStatusLawControl">
                                    حذف
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div dir="ltr" class="mt-4 flex justify-center" id="laravel-next-prev">
                    {{ $lawList->links() }}
                </div>
            </div>

        </div>
    </main>
@endsection
