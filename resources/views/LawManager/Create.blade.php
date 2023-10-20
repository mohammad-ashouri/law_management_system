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
                        <div class="">
                            <div class="flex flex-col items-right mb-2">
                                <label for="name"
                                       class="block text-gray-700 text-sm font-bold mb-2">عنوان*:</label>
                                <input type="text" id="name" name="name" autocomplete="off"
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
                            <div class="flex-1 flex-col items-right mb-2" style="flex: 1;">
                                <label for="topic" class="block text-gray-700 text-sm font-bold mb-2">موضوع*:</label>
                                <select id="topic" class="border rounded-md w-full px-3 py-2"
                                        name="topic">
                                    <option value="" disabled selected>انتخاب کنید</option>
                                    @foreach($topics as $topic)
                                        <option value="{{ $topic->id }}">{{ $topic->name }}</option>
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
                                <button type="button" id="selectTextButton" class="px-4 py-2 mr-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                                    انتخاب از متن
                                </button>
                            </label>
                            <input type="text" name="keywords" id="keywords" value="">
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('#selectTextButton').click(function() {
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
                                <input type="file" id="file" name="file" class="border rounded-md w-full px-3 py-2" accept=".pdf, .doc, .docx, .jpg, .jpeg, .png">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="px-4 py-2 mr-3 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                            ثبت مصوبه جدید
                        </button>
                        <a href="/Laws">
                            <button id="cancel-new-law" type="button"
                                    class="mt-3 w-full inline-flex justify-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-blue-300 sm:mt-0 sm:w-auto">
                                بازگشت
                            </button>
                        </a>
                    </div>
                </form>

            </div>

        </div>
    </main>
@endsection
