@php use Morilog\Jalali\Jalalian; @endphp
@extends('layouts.PanelMaster')

@section('content')
    <main class="flex-1 bg-cu-light py-6 px-8">
        <div class="mx-auto lg:mr-72">
            <h1 class="text-2xl font-bold mb-4">ایجاد بکاپ کامل از دیتابیس</h1>
            <div class="bg-white rounded shadow p-6 mb-4">
                @can('ایجاد بکاپ دیتابیس')
                    <form id="create-backup">
                        <button type="button"
                                class="px-4 py-2 mr-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300 GetPersonEquipmentsReport">
                            ایجاد بکاپ
                        </button>
                    </form>
                @endcan
                <hr>
                <div>
                    <table class="w-full border-collapse rounded-lg overflow-hidden text-center mt-3">
                        <thead>
                        <tr class="bg-gradient-to-r from-blue-400 to-purple-500 items-center text-center text-white">
                            <th class=" px-6 py-3 w-9 font-bold ">ردیف</th>
                            <th class=" px-3 py-3  font-bold ">نام فایل</th>
                            <th class=" px-3 py-3  font-bold ">تاریخ ایجاد</th>
                            <th class=" px-3 py-3  font-bold ">کاربر ایجاد کننده</th>
                            <th class=" px-3 py-3  font-bold ">فایل</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($backups as $backup)
                            <tr>
                                <td class="py-2">{{ $loop->iteration }}</td>
                                @php
                                    $afterSlash = strstr($backup->filename, '/');
                                @endphp
                                <td class="py-2">{{ substr($afterSlash, 1) }}</td>
                                @php
                                    $jalaliDate = Jalalian::fromDateTime($backup->created_at);
                                    $formattedJalaliDate = $jalaliDate->format('Y/m/d H:i:s');
                                @endphp
                                <td class="py-2">{{ $formattedJalaliDate }}</td>
                                <td class="py-2">{{ $backup->creatorInfo->name .' '. $backup->creatorInfo->family }}</td>
                                <td class="py-2">
                                    <a href="{{ env('APP_URL').'/storage/backup/'.$backup->filename }}">
                                        دانلود
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div dir="ltr" class="mt-4 flex justify-center" id="laravel-next-prev">
                        {{ $backups->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

