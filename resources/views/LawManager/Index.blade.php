@php use App\Models\Catalogs\LawGroup; @endphp
@extends('layouts.PanelMaster')
@section('content')
    <main class="flex-1 bg-gray-100 py-6 px-8">
        <div class="mx-auto lg:mr-72">
            <h1 class="text-2xl font-bold mb-4">مدیریت بر اطلاعات قوانین و مصوبات</h1>

            <div class="bg-white rounded shadow p-6 flex flex-col ">
                <div class="flex">
                    <a href="/Laws/new">
                        <button type="button"
                                class="px-4 py-2 mb-3 bg-green-500 w-24 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                            جدید
                        </button>
                    </a>
{{--                    @if($filtered)--}}
{{--                        <a href="/Laws">--}}
{{--                            <button type="button" data-te-toggle="modal"--}}
{{--                                    class="px-4 py-2 mb-3 mr-3 bg-red-500 w-32 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-red-300 filter">--}}
{{--                                حذف فیلتر--}}
{{--                            </button>--}}
{{--                        </a>--}}
{{--                    @else--}}
                        <button type="button" data-te-toggle="modal"
                                data-te-target="#searchModal"
                                class="px-4 py-2 mb-3 mr-3 bg-blue-500 w-24 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300 filter">
                            جستجو
                        </button>
{{--                    @endif--}}

                </div>
                <!-- Modal -->
                <form action="/Laws/search" id="LawSearch" method="POST">
                    @csrf
                    <div
                        data-te-modal-init
                        class="fixed left-0 top-0 mt-10 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
                        id="searchModal"
                        tabindex="-1"
                        aria-labelledby="searchModal"
                        aria-hidden="true">
                        <div
                            data-te-modal-dialog-ref
                            class="pointer-events-none relative w-auto opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                            <div
                                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                                <div
                                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
                                    <!--Modal title-->
                                    <h5
                                        class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                                        id="exampleModalLabel">
                                        جستجوی قوانین و مصوبات
                                    </h5>
                                    <!--Close button-->
                                    <button
                                        type="button"
                                        class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                                        data-te-modal-dismiss
                                        aria-label="Close">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="h-6 w-6">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <!--Modal body-->
                                <div class="columns-2 px-4 mb-2">
                                    <label for="lawCode"
                                           class="block text-gray-700 text-sm font-bold mb-2">شماره مصوبه:</label>
                                    <input type="text" id="lawCode" name="lawCode"
                                           class="border rounded-md w-full px-3 py-2 text-right"
                                           placeholder="شماره مصوبه را وارد کنید">
                                    <label for="sessionCode"
                                           class="block text-gray-700 text-sm font-bold mb-2">شماره جلسه:</label>
                                    <input type="text" id="sessionCode" name="sessionCode"
                                           class="border rounded-md w-full px-3 py-2 text-right"
                                           placeholder="شماره جلسه را وارد کنید">
                                </div>
                                <div class="columns-1 px-4 mb-2">
                                    <label for="title"
                                           class="block text-gray-700 text-sm font-bold mb-2">عنوان:</label>
                                    <input type="text" id="title" name="title"
                                           class="border rounded-md w-full px-3 py-2 text-right"
                                           placeholder="عنوان مصوبه را وارد کنید">
                                </div>
                                <div class="columns-3 px-4 mb-2">
                                    <div class=" items-right mb-2 ml-3">
                                        <label for="type" class="block text-gray-700 text-sm font-bold mb-2">نوع
                                            مصوبه:</label>
                                        <select id="type" class="border rounded-md w-full px-3 py-2"
                                                name="type">
                                            <option value="" disabled selected>انتخاب کنید</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="group"
                                               class="block text-gray-700 text-sm font-bold mb-2">گروه:</label>
                                        <select id="group" class="border rounded-md w-full px-3 py-2"
                                                name="group">
                                            <option value="" disabled selected>انتخاب کنید</option>
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="topic"
                                               class="block text-gray-700 text-sm font-bold mb-2">موضوع:</label>
                                        <select id="topic" class="border rounded-md w-full px-3 py-2"
                                                name="topic">
                                            <option value="" disabled selected>انتخاب کنید</option>
                                            @foreach($topics as $topic)
                                                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="columns-1 px-4 mb-2">
                                    <div class=" items-right mb-2 ml-3">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">تاریخ تصویب*:</label>
                                        <div class="flex-1 flex-col items-right mb-2 ml-3">
                                            <select id="approval_day" class="border rounded-md px-3 py-2"
                                                    name="approval_day">
                                                <option value="" disabled selected>روز</option>
                                                @for($a=1;$a<=31;$a++)
                                                    <option value="{{ $a }}">{{ $a }}</option>
                                                @endfor
                                            </select>
                                            <select id="approval_month" class="border rounded-md px-3 py-2"
                                                    name="approval_month">
                                                <option value="" disabled selected>ماه</option>
                                                @for($a=1;$a<=12;$a++)
                                                    <option value="{{ $a }}">{{ $a }}</option>
                                                @endfor
                                            </select>
                                            <select id="approval_year" class="border rounded-md px-3 py-2"
                                                    name="approval_year">
                                                <option value="" disabled selected>سال</option>
                                                @for($a=1370;$a<=1402;$a++)
                                                    <option value="{{ $a }}">{{ $a }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="columns-1 px-4 mb-2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">تاریخ صدور:</label>
                                    <div class="flex-1 flex-col items-right mb-2 ml-3">
                                        <select id="issue_day" class="border rounded-md px-3 py-2"
                                                name="issue_day">
                                            <option value="" disabled selected>روز</option>
                                            @for($a=1;$a<=31;$a++)
                                                <option value="{{ $a }}">{{ $a }}</option>
                                            @endfor
                                        </select>
                                        <select id="issue_month" class="border rounded-md px-3 py-2"
                                                name="issue_month">
                                            <option value="" disabled selected>ماه</option>
                                            @for($a=1;$a<=12;$a++)
                                                <option value="{{ $a }}">{{ $a }}</option>
                                            @endfor
                                        </select>
                                        <select id="issue_year" class="border rounded-md px-3 py-2"
                                                name="issue_year">
                                            <option value="" disabled selected>سال</option>
                                            @for($a=1370;$a<=1402;$a++)
                                                <option value="{{ $a }}">{{ $a }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="columns-1 px-4 mb-2">
                                    <div class=" items-right mb-2 ml-3">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">تاریخ ابلاغ:</label>
                                        <div class="flex-1 flex-col items-right mb-2 ml-3">
                                            <select id="promulgation_day" class="border rounded-md px-3 py-2"
                                                    name="promulgation_day">
                                                <option value="" disabled selected>روز</option>
                                                @for($a=1;$a<=31;$a++)
                                                    <option value="{{ $a }}">{{ $a }}</option>
                                                @endfor
                                            </select>
                                            <select id="promulgation_month" class="border rounded-md px-3 py-2"
                                                    name="promulgation_month">
                                                <option value="" disabled selected>ماه</option>
                                                @for($a=1;$a<=12;$a++)
                                                    <option value="{{ $a }}">{{ $a }}</option>
                                                @endfor
                                            </select>
                                            <select id="promulgation_year" class="border rounded-md px-3 py-2"
                                                    name="promulgation_year">
                                                <option value="" disabled selected>سال</option>
                                                @for($a=1370;$a<=1402;$a++)
                                                    <option value="{{ $a }}">{{ $a }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="columns-1 px-4 mb-2">
                                    <div class=" items-right mb-2 ml-3">
                                        <label for="keywords" class="block text-gray-700 text-sm font-bold mb-2">کلمات
                                            کلیدی*:
                                        </label>
                                        <input type="text" name="keywords" id="keywords" value="">
                                    </div>
                                    <script>
                                        $(document).ready(function () {
                                            $('#selectTextButton').click(function () {
                                                var selectedText = getSelectedText();
                                                $('#keywords').addTag(selectedText);
                                            });

                                            function getSelectedText() {
                                                var textArea = document.getElementById('body');
                                                var selectedText = '';

                                                if (window.getSelection) {
                                                    selectedText = textArea.value.substring(textArea.selectionStart, textArea.selectionEnd);
                                                }

                                                return selectedText;
                                            }

                                            $('#keywords').tagsInput({
                                                selectFirst: true,
                                                autoFill: true,
                                                defaultText: 'کلمه کلیدی را وارد کنید و enter را فشار دهید',
                                                interactive: true,
                                                delimiter: ['||'],
                                            });
                                        });
                                    </script>
                                </div>

                                <!--Modal footer-->
                                <div
                                    class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
                                    <button
                                        type="button"
                                        class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-primary-700 transition duration-150 ease-in-out hover:bg-primary-accent-100 focus:bg-primary-accent-100 focus:outline-none focus:ring-0 active:bg-primary-accent-200"
                                        data-te-modal-dismiss
                                        data-te-ripple-init
                                        data-te-ripple-color="light">
                                        بستن
                                    </button>
                                    <button
                                        type="submit"
                                        class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                                        data-te-ripple-init
                                        data-te-ripple-color="light">
                                        جستجو
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <table class="w-full border-collapse rounded-lg overflow-hidden text-center datasheet">
                    <thead>
                    <tr class="bg-gradient-to-r from-blue-400 to-purple-500 items-center text-center text-white">
                        <th class="px-2 py-3  font-bold ">ردیف</th>
                        <th class="px-6 py-3  font-bold ">شماره مصوبه</th>
                        <th class="px-6 py-3  font-bold ">شماره جلسه</th>
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
                            <td class="px-2 py-1">{{ $loop->iteration }}</td>
                            <td class="px-6 py-1">{{ $law->law_code }}</td>
                            <td class="px-6 py-1">{{ $law->session_code }}</td>
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
                                        class="px-4 py-2 mr-3 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-red-300 deleteLaw">
                                    حذف
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div dir="ltr" class="mt-4 flex justify-center" id="laravel-next-prev">
                    {{--                    {{ $lawList->links() }}--}}
                </div>
            </div>

        </div>
    </main>
@endsection