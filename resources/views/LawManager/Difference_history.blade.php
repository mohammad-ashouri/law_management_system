@extends('layouts.PanelMaster')
@section('content')
    <main class="flex-1 bg-gray-100 py-6 px-8">
        <style>
            /* Difference Highlighting and Strike-through
    ------------------------------------------------ */
            ins {
                color: #333333;
                background-color: #eaffea;
                text-decoration: none;
            }

            del {
                color: #AA3333;
                background-color: #ffeaea;
                text-decoration: line-through;
            }

            /* Image Diffing
            ------------------------------------------------ */
            del.diffimg.diffsrc {
                display: inline-block;
                position: relative;
            }

            del.diffimg.diffsrc:before {
                position: absolute;
                content: "";
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background: repeating-linear-gradient(
                    to left top,
                    rgba(255, 0, 0, 0),
                    rgba(255, 0, 0, 0) 49.5%,
                    rgba(255, 0, 0, 1) 49.5%,
                    rgba(255, 0, 0, 1) 50.5%
                ), repeating-linear-gradient(
                    to left bottom,
                    rgba(255, 0, 0, 0),
                    rgba(255, 0, 0, 0) 49.5%,
                    rgba(255, 0, 0, 1) 49.5%,
                    rgba(255, 0, 0, 1) 50.5%
                );
            }

            /* List Diffing
            ------------------------------------------------ */
            /* List Styles */
            .diff-list {
                list-style: none;
                counter-reset: section;
                display: table;
            }

            .diff-list > li.normal,
            .diff-list > li.removed,
            .diff-list > li.replacement {
                display: table-row;
            }

            .diff-list > li > div {
                display: inline;
            }

            .diff-list > li.replacement:before,
            .diff-list > li.new:before {
                color: #333333;
                background-color: #eaffea;
                text-decoration: none;
            }

            .diff-list > li.removed:before {
                counter-increment: section;
                color: #AA3333;
                background-color: #ffeaea;
                text-decoration: line-through;
            }

            /* List Counters / Numbering */
            .diff-list > li.normal:before,
            .diff-list > li.removed:before,
            .diff-list > li.replacement:before {
                width: 15px;
                overflow: hidden;
                content: counters(section, ".") ". ";
                display: table-cell;
                text-indent: -1em;
                padding-left: 1em;
            }

            .diff-list > li.normal:before,
            li.replacement + li.replacement:before,
            .diff-list > li.replacement:first-child:before {
                counter-increment: section;
            }

            ol.diff-list li.removed + li.replacement {
                counter-increment: none;
            }

            ol.diff-list li.removed + li.removed + li.replacement {
                counter-increment: section -1;
            }

            ol.diff-list li.removed + li.removed + li.removed + li.replacement {
                counter-increment: section -2;
            }

            ol.diff-list li.removed + li.removed + li.removed + li.removed + li.replacement {
                counter-increment: section -3;
            }

            ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
                counter-increment: section -4;
            }

            ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
                counter-increment: section -5;
            }

            ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
                counter-increment: section -6;
            }

            ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
                counter-increment: section -7;
            }

            ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
                counter-increment: section -8;
            }

            ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
                counter-increment: section -9;
            }

            ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
                counter-increment: section -10;
            }

            ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
                counter-increment: section -11;
            }

            /* Exception Lists */
            ul.exception,
            ul.exception li:before {
                list-style: none;
                content: none;
            }

            .diff-list ul.exception ol {
                list-style: none;
                counter-reset: exception-section;
                /* Creates a new instance of the section counter with each ol element */
            }

            .diff-list ul.exception ol > li:before {
                counter-increment: exception-section;
                content: counters(exception-section, ".") ".";
            }
        </style>
        <div class="mx-auto lg:mr-72">
        <h1 class="text-2xl font-bold mb-4">تاریخچه تغییرات مصوبه:
            {{ $lawName[0] }}
        </h1>
        </div>
        @if($lawDiffs->isNotEmpty())
            <div class="mx-auto lg:mr-72">
                <div class="bg-white rounded shadow p-6 flex flex-col ">
                    <div class="flex">
                        <table class="w-full border-collapse rounded-lg overflow-hidden text-center datasheet">
                            <thead>
                            <tr class="bg-gradient-to-r from-blue-400 to-purple-500 items-center text-center text-white">
                                <th class="px-2 py-3  font-bold ">ردیف</th>
                                <th class="px-6 py-3  font-bold ">کاربر ویرایشگر</th>
                                <th class="px-6 py-3  font-bold ">تاریخ ویرایش</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300">
                            @foreach($lawDiffs as $lawDiff)
                                @php
                                    $htmlDiff = new \Caxy\HtmlDiff\HtmlDiff($lawDiff->old, $lawDiff->new);
                                             $content = $htmlDiff->build();
                                @endphp
                                <tr class="bg-gray-300">
                                    <td class="w-2">{{ $loop->iteration }}</td>
                                    <td>{{ $lawDiff->editorInfo->name . ' ' . $lawDiff->editorInfo->family }}</td>
                                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($lawDiff->created_at)->format('H:m Y/m/d') }}</td>
                                </tr>
                                <tr class="bg-white">
                                    <td class="px-2 py-1" colspan="3">
                                        <p>
                                            {!! $content !!}
                                        </p>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        @else
            <div class="mx-auto lg:mr-72">
                <div class="bg-white rounded shadow p-6 flex flex-col ">
                    <p>هیچ تغییراتی برای این مصوبه وارد نشده است.</p>
                </div>
            </div>
        @endif
    </main>
@endsection
