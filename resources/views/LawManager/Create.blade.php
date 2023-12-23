@php use App\Models\Catalogs\LawGroup; @endphp
@extends('layouts.PanelMaster')

@section('content')
    <main class="flex-1 bg-gray-100 py-6 px-8">
        <div class="mx-auto lg:mr-72">
            <h1 class="text-2xl font-bold mb-4">تعریف قانون/مصوبه/دستورالعمل جدید</h1>
            <div class="bg-white rounded shadow flex flex-col ">
                <form id="new-law">
                    @csrf
                    <div class="bg-white px-4 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex mt-4">
                            <div class="flex-1 flex-col items-right mb-2 ml-3">
                                <label for="lawCode"
                                       class="block text-gray-700 text-sm font-bold mb-2">شماره مصوبه*:</label>
                                <input type="text" id="lawCode" name="lawCode" autocomplete="off"
                                       class="border rounded-md w-full mb-2 px-3 py-2 text-right"
                                       placeholder="شماره مصوبه را وارد کنید">
                            </div>
                            <div class="flex-1 flex-col items-right mb-2 ml-3">
                                <label for="sessionCode"
                                       class="block text-gray-700 text-sm font-bold mb-2">شماره جلسه:</label>
                                <input type="text" id="sessionCode" name="sessionCode" autocomplete="off"
                                       class="border rounded-md w-full mb-2 px-3 py-2 text-right"
                                       placeholder="شماره جلسه را وارد کنید">
                            </div>
                        </div>

                        <div class="">
                            <div class="flex flex-col items-right mb-2">
                                <label for="title"
                                       class="block text-gray-700 text-sm font-bold mb-2">عنوان*:</label>
                                <input type="text" id="title" name="title" autocomplete="off"
                                       class="border rounded-md w-full mb-2 px-3 py-2 text-right"
                                       placeholder="عنوان را وارد کنید">
                            </div>
                        </div>
                        <div class="flex mt-4">
                            <div class="flex-1 flex-col items-right mb-2 ml-3">
                                <label for="type" class="block text-gray-700 text-sm font-bold mb-2">نوع مصوبه*:</label>
                                <select id="type" class="border rounded-md w-full px-3 py-2"
                                        name="type">
                                    <option value="" disabled selected>انتخاب کنید</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-1 flex-col items-right mb-2 ml-3">
                                <label for="group" class="block text-gray-700 text-sm font-bold mb-2">گروه*:</label>
                                <select id="group" class="border rounded-md w-full px-3 py-2"
                                        name="group">
                                    <option value="" disabled selected>انتخاب کنید</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex mt-4">
                            <div class="flex-1 flex-col items-right mb-2 ml-3">
                                <label for="topic" class="block text-gray-700 text-sm font-bold mb-2">موضوع*:</label>
                                <select id="topic" class="border rounded-md w-full px-3 py-2"
                                        name="topic">
                                    <option value="" disabled selected>انتخاب کنید</option>
                                    @foreach($topics as $topic)
                                        <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-1 flex-col items-right mb-2 ml-3">
                                <label for="approver" class="block text-gray-700 text-sm font-bold mb-2">تصویب
                                    کننده*:</label>
                                <select id="approver" class="border rounded-md w-full px-3 py-2"
                                        name="approver">
                                    <option value="" disabled selected>انتخاب کنید</option>
                                    @foreach($approvers as $approver)
                                        <option value="{{ $approver->id }}">{{ $approver->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="body" class="block text-gray-700 text-sm font-bold mb-2">متن مصوبه*:</label>
                            <textarea id="body" name="body" rows="7"
                                      class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"></textarea>
                        </div>
                        <div class="mt-4">
                            <label for="keywords" class="block text-gray-700 text-sm font-bold mb-2">کلمات
                                کلیدی*:
                                <button type="button" id="selectTextButton"
                                        class="px-4 py-2 mr-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                                    انتخاب از متن
                                </button>
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
                                    width: '700px',
                                    interactive: true,
                                    delimiter: ['||'],
                                });
                            });
                        </script>

                        <div class="flex mt-4">
                            <div class="flex-1 flex-col items-right mb-2 ml-3">
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
                            <div class="flex-1 flex-col items-right mb-2 ml-3">
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
                            <div class="flex-1 flex-col items-right mb-2 ml-3">
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
                        <div class="mt-4">
                            <div class="mt-4">
                                <label for="file" class="block text-gray-700 text-sm font-bold mb-2">فایل پیوست:</label>
                                <input type="file" id="file" name="file" class="border rounded-md w-full px-3 py-2"
                                       accept=".pdf, .doc, .docx, .jpg, .jpeg, .png">
                            </div>
                        </div>
                    </div>
                    <div class="bg-white px-4 py-3 sm:px-6 sm:flex-row-reverse text-right">
                        <button type="button" id="addReferer"
                                class="px-4 py-2 mr-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                            ایجاد عطف
                        </button>

                        <div class="mt-4 mb-4 flex items-center">
{{--                                                        <div class="fixed z-10 inset-0 overflow-y-auto hidden" id="addRefererModal">--}}
                            <div class="fixed z-10 inset-0 overflow-y-auto " id="addRefererModal">
                                <div
                                    class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center  sm:block sm:p-0">
                                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                        <div class="absolute inset-0 bg-gray-500 opacity-75 addreferer"></div>
                                    </div>

                                    <div
                                        class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full sm:max-w-[550px]">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                                جستجوی مصوبه
                                            </h3>
                                            <div class="mt-4">
                                                <div class="flex flex-col items-right mb-4">
                                                    <label for="to_refer_law_code"
                                                           class="block text-gray-700 text-sm font-bold mb-2">کد
                                                        مصوبه*:</label>
                                                    <input type="text" id="to_refer_law_code" name="to_refer_law_code"
                                                           autocomplete="off"
                                                           class="border rounded-md w-full mb-4 px-3 py-2 text-right"
                                                           placeholder="کد مصوبه را وارد کرده و بر روی دکمه جستجو کلیک کنید">
                                                    <button type="button" id="get-referer"
                                                            class="px-4 py-2 mr-3 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                                                        جستجو
                                                    </button>
                                                </div>
                                                <div class=" items-right mb-4">
                                                    <div>
                                                        <label for="refer_law_code"
                                                               class=" text-gray-700 text-sm font-bold mb-2">کد
                                                            مصوبه:</label>
                                                        <span id="refer_law_code"></span>
                                                    </div>
                                                    <div>
                                                        <label for="refer_law_title"
                                                               class=" text-gray-700 text-sm font-bold mb-2">عنوان:</label>
                                                        <span id="refer_law_title"></span>
                                                    </div>
                                                    <div>
                                                        <label for="refer_law_type"
                                                               class=" text-gray-700 text-sm font-bold mb-2">نوع مصوبه:</label>
                                                        <span id="refer_law_type"></span>
                                                    </div>
                                                    <div>
                                                        <label for="refer_law_group"
                                                               class=" text-gray-700 text-sm font-bold mb-2">گروه:</label>
                                                        <span id="refer_law_group"></span>
                                                    </div>
                                                    <div>
                                                        <label for="refer_law_approver"
                                                               class=" text-gray-700 text-sm font-bold mb-2">تصویب کننده:</label>
                                                        <span id="refer_law_approver"></span>
                                                    </div>
                                                    <div>
                                                        <label for="refer_law_topic"
                                                               class=" text-gray-700 text-sm font-bold mb-2">موضوع:</label>
                                                        <span id="refer_law_topic"></span>
                                                    </div>
                                                    <div>
                                                        <label for="refer_law_approval_date"
                                                               class=" text-gray-700 text-sm font-bold mb-2">تاریخ تصویب:</label>
                                                        <span id="refer_law_approval_date"></span>
                                                    </div>
                                                </div>
                                                <div class="flex-1 flex-col items-right mb-2 ml-3">
                                                    <label for="refer_to" class="block text-gray-700 text-sm font-bold mb-2">تنظیم به عنوان*:</label>
                                                    <select id="refer_to" class="border rounded-md w-full px-3 py-2"
                                                            name="refer_to">
                                                        <option value="" disabled selected>انتخاب کنید</option>
                                                            <option value="1">عطف</option>
                                                            <option value="2">متمم</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="button"
                                                    class="px-4 py-2 mr-3 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                                                ثبت جدید
                                            </button>
                                            <button id="cancel-new-referer" type="button"
                                                    class="mt-3 w-full inline-flex justify-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-blue-300 sm:mt-0 sm:w-auto">
                                                انصراف
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="px-4 py-2 mr-3 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                            ثبت مصوبه جدید
                        </button>
                        <button id="backward_page" type="button"
                                class="mt-3 w-full inline-flex justify-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-blue-300 sm:mt-0 sm:w-auto">
                            بازگشت
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </main>
@endsection
