@extends('layouts.PanelMaster')

@section('content')
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
    <main class="flex-1 bg-cu-light py-6 px-8">
        <div class="mx-auto lg:mr-72">
            <h1 class="text-2xl font-bold mb-4">داشبورد</h1>
            <div class="bg-white rounded shadow p-6">
                <p>
                    به پنل سامانه قوانین و مصوبات منابع انسانی حوزه های علمیه کشور خوش آمدید.
                </p>
            </div>
        </div>
        <div class="mx-auto mt-3 lg:mr-72">
        <h1 class="text-2xl font-bold mb-4">مقایسه دو متن</h1>
            <div class="bg-white rounded shadow p-6">
            <div class="mt-4">
                            <label for="body1" class="block text-gray-700 text-sm font-bold mb-2">متن اول*:</label>
                            <textarea id="body1" name="body1" placeholder="متن اول را وارد کنید"
                                      class="border rounded-md w-full h-96 px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"></textarea>
                        </div>
            <div class="mt-4">
                            <label for="body2" class="block text-gray-700 text-sm font-bold mb-2">متن دوم*:</label>
                            <textarea id="body2" name="body2" placeholder="متن دوم را وارد کنید"
                                      class="border rounded-md w-full h-96 px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"></textarea>
                        </div>
                        <div class="mt-3 text-left">
                            <button type="button"
                                class="px-4 py-2 mr-3 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300 compare">
                            مقایسه
                        </button>
                    </div>
            <div class="mt-4" id="compared"></div>
            </div>
            </div>

        </div>
    </main>
@endsection

