@php use App\Models\Province; @endphp
<!DOCTYPE html>
<html dir="rtl" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>
        {{ env('APP_PERSIAN_NAME') }}
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
{{--    <link href="http://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">--}}
{{--    <script src="http://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>--}}
{{--    <script--}}
{{--        src="https://code.jquery.com/jquery-3.7.1.js"--}}
{{--        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="--}}
{{--        crossorigin="anonymous"></script>--}}
    <script src="./build/plugins/jquery/dist/jquery.js"></script>
    <link href="./build/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
    <script src="./build/plugins/select2/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'یک گزینه را انتخاب کنید',
            });
        });
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="flex flex-col min-h-screen mt-14">
<div id="loading_popup" class="hidden">
    <div class="loader_popup"></div>
    <p class="animate-pulse text-blue-700 text-xl mr-3">در حال پردازش اطلاعات...</p>
</div>

<header class="w-full fixed top-0 right-0 bg-cu-light py-5 md:px-2 lg:px-0">
    <div class="flex items-center lg:mx-2 mx-5">
        <div>
            <style>
                ::-webkit-scrollbar {
                    width: 12px;
                }
                ::-webkit-scrollbar-thumb {
                    background-color: #4A90E2;
                    border-radius: 6px;
                }
                ::-webkit-scrollbar-thumb:hover {
                    background-color: #357ABD;
                    border-radius: 10px;
                }
                li.active {
                    /* overflow: hidden; */
                    position: relative;

                    /* width: 100px; */
                    /* height: 100px; */
                }

                li.active a {
                    transition: 0.5s;
                    background: #f0f6fb;
                    color: rgb(27, 27, 27);
                    /* border-radius: 20px; */
                    border-top-right-radius: 50px;
                    border-bottom-right-radius: 50px;

                }

                li.active a:focus {
                    background: #f0f6fb;

                }

                li.active a svg {
                    color: rgb(34, 34, 34);
                }


                li.active:before,
                li.active:after {
                    content: "";
                    display: block;
                    width: 60px;
                    height: 60px;
                    position: absolute;
                    border-radius: 50%;
                }

                li.active:before {
                    bottom: -52px;
                    left: 0px;
                    box-shadow: -30px 26px 0px 0px #f0f6fb;
                    transform: rotateX(180deg);

                }

                li.active:after {
                    top: -52px;
                    left: 0px;
                    box-shadow: -30px 26px 0px 0px #f0f6fb;
                }

                .menu {
                    height: 100vh;
                    max-height: calc(100vh - 8%);
                    position: fixed;
                    top: 8%;
                    right: 0;
                    overflow-y: auto;
                }

                /* .mr-350 {
                    transition: .5s;
                    margin-right: -350px;
                } */
                .drawer-side {
                    position: fixed !important;
                }
            </style>

            <aside data-theme="wireframe" class="bg-cu-light ">
                <div class="drawer lg:drawer-open">
                    <input id="my-drawer-2" type="checkbox" class="drawer-toggle"/>
                    <div class="drawer-content flex flex-col items-center justify-center">
                        <!-- Page content here -->
                        <label for="my-drawer-2" class="cursor-pointer inline lg:hidden drawer-button"
                               id="toggle-menu-n">
                            <svg viewBox="0 0 100 80" width="30" height="40">
                                <rect width="100" height="20" rx="10"></rect>
                                <rect y="30" width="100" height="20" rx="10"></rect>
                                <rect y="60" width="100" height="20" rx="10"></rect>
                            </svg>
                        </label>

                    </div>
                    <div class="drawer-side">
                        <label for="my-drawer-2" class="drawer-overlay"></label>
                        @php
                            $userInfo=\Illuminate\Support\Facades\DB::table('users')
                                    ->where('username', session('username'))
                                    ->first();
                        @endphp
                        <ul id="menu"
                            class="menu pr-8 pl-0 w-72 bg-cu-blue text-transparent pt-5 overflow-x-hidden rounded-se-3xl block">

                            <div class="ml-7 mb-6 text-center mt-5">
                                <div class="avatar flex justify-center ">
                                    @if($userInfo->user_image)
                                        @php
                                        $src=substr($userInfo->user_image,6);
                                        $src='storage'.$src;
                                        @endphp
                                        <div style="background: url({{ $src }}) no-repeat; background-size: cover;" class="w-16 h-16 rounded-full">
                                        </div>
                                    @else
                                        <div id="user_icon" class="w-16 h-16 rounded-full">
                                        </div>
                                    @endif

                                </div>
                                <p class="pt-2 text-cu-light">
                                    {{ $userInfo->username.' | '. $userInfo->name . ' '. $userInfo->family }}
                                </p>
                                <p class="pt-1 text-cu-light">
                                    @if($userInfo->type===1)
                                        {{ $userInfo->subject }}
                                    @elseif($userInfo->type===2)
                                        @php
                                        $provinceInfo=Province::find($userInfo->province_id)
                                        @endphp
                                        {{ $userInfo->subject . ' ' . $provinceInfo->name }}
                                    @endif
                                </p>
                            </div>
                            <li class="menu-item" id="dashboard">
                                <a href="{{ route('dashboard') }}"
                                   class="flex items-center p-3 my-1 text-cu-light rounded-s-full dark:text-white hover:bg-gray-100 light:hover:bg-gray-700 group">
                                    <svg
                                        class="w-5 h-5 dark:text-white transition duration-75 dark:group-hover:text-gray-700"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        viewBox="0 0 25 25">
                                        <path
                                            d="M23.121,9.069,15.536,1.483a5.008,5.008,0,0,0-7.072,0L.879,9.069A2.978,2.978,0,0,0,0,11.19v9.817a3,3,0,0,0,3,3H21a3,3,0,0,0,3-3V11.19A2.978,2.978,0,0,0,23.121,9.069ZM15,22.007H9V18.073a3,3,0,0,1,6,0Zm7-1a1,1,0,0,1-1,1H17V18.073a5,5,0,0,0-10,0v3.934H3a1,1,0,0,1-1-1V11.19a1.008,1.008,0,0,1,.293-.707L9.878,2.9a3.008,3.008,0,0,1,4.244,0l7.585,7.586A1.008,1.008,0,0,1,22,11.19Z"/>
                                    </svg>
                                    <span class="mr-3">داشبورد</span>
                                </a>
                            </li>
                            @php
                                $menus = session('menus');
                            @endphp

                            @foreach ($menus as $menu)
                                <li>
                                    @if (isset($menu['childs']) && count($menu['childs']) > 0)
                                        <details id="{{ 'details-' . $menu['title'] }}">
                                            <summary
                                                class="flex items-center p-3 my-1 text-cu-light rounded-s-full dark:text-white hover:bg-gray-100 light:hover:bg-gray-700 group">
                                                <svg
                                                    class="ml-3 flex-shrink-0 w-5 h-5  transition duration-75 text-cu-light group-hover:text-cu-light dark:group-hover:text-gray-700"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor" viewBox="0 0 25 25">
                                                    @if(@$menu['path1'])
                                                        <path
                                                            d="{{ $menu['path1'] }}"></path>
                                                    @endif
                                                    @if(@$menu['path2'])
                                                        <path
                                                            d="{{ @$menu['path2'] }}"></path>
                                                    @endif
                                                    @if(@$menu['path3'])
                                                        <path
                                                            d="{{ @$menu['path3'] }}"></path>
                                                    @endif
                                                    @if(@$menu['path4'])
                                                        <path
                                                            d="{{ @$menu['path4'] }}"></path>
                                                    @endif
                                                    @if(@$menu['path5'])
                                                        <path
                                                            d="{{ @$menu['path5'] }}"></path>
                                                    @endif
                                                    @if(@$menu['path6'])
                                                        <path
                                                            d="{{ @$menu['path6'] }}"></path>
                                                    @endif
                                                </svg>
                                                {{ $menu['title'] }}
                                            </summary>
                                            <ul class="text-white w-full mr-2">
                                                @foreach ($menu['childs'] as $child)
                                                    <li class="menu-item mr-8" id="{{ $child['title'] }}">

                                                        <a href="{{ $child['link'] }}"
                                                           class="flex items-center p-3 my-1 text-cu-light rounded-s-full dark:text-white hover:bg-gray-100 light:hover:bg-gray-700 group">
                                                            <svg
                                                                class="w-5 h-5 dark:text-white transition duration-75 dark:group-hover:text-gray-700"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="currentColor"
                                                                viewBox="0 0 25 25">
                                                                <path
                                                                    d="{{ @$child['path1'] }}"></path>
                                                                <path
                                                                    d="{{ @$child['path2'] }}"></path>
                                                            </svg>
                                                            {{ $child['title'] }}</a>
                                                    </li>
                                                    <li>
                                                @endforeach

                                            </ul>

                                            @else
                                                <li class="menu-item" id="menu{{ $menu['link'] }}">
                                                    <a href="{{ $menu['link'] }}"
                                                       class="flex items-center p-3 my-1 text-cu-light rounded-s-full dark:text-white hover:bg-gray-100 light:hover:bg-gray-700 group">
                                                        @if(@$menu['path1'])
                                                            <svg
                                                                class="w-5 h-5 dark:text-white transition duration-75 dark:group-hover:text-gray-700"
                                                                aria-hidden="true"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                fill="currentColor"
                                                                viewBox="0 0 22 21">
                                                                <path
                                                                    d="{{ $menu['path1'] }}"></path>
                                                                <path
                                                                    d="{{ @$menu['path2'] }}"></path>
                                                                <path
                                                                    d="{{ @$menu['path3'] }}"></path>
                                                                <path
                                                                    d="{{ @$menu['path4'] }}"></path>
                                                                <path
                                                                    d="{{ @$menu['path5'] }}"></path>
                                                                <path
                                                                    d="{{ @$menu['path6'] }}"></path>
                                                            </svg>
                                                        @endif
                                                        <span class=" mr-3 ">
                                                            {{ $menu['title'] }}
                                                        </span>
                                                    </a>
                                                </li>
                                            @endif
                                        </details>
                                </li>
                            @endforeach

                            <li class="menu-item" id="changePassword">
                                <a href="{{ route('Profile') }}"
                                   class="flex items-center p-3 my-1 text-cu-light rounded-s-full dark:text-white hover:bg-gray-100 light:hover:bg-gray-700 group">
                                    <svg
                                        class="w-5 h-5 text-cu-light transition duration-75  group-hover:text-cu-light dark:group-hover:text-gray-700"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        viewBox="0 0 25 25">
                                        <path
                                            d="M7.505,24A7.5,7.5,0,0,1,5.469,9.283,7.368,7.368,0,0,1,9.35,9.235l7.908-7.906A4.5,4.5,0,0,1,20.464,0h0A3.539,3.539,0,0,1,24,3.536a4.508,4.508,0,0,1-1.328,3.207L22,7.415A2.014,2.014,0,0,1,20.586,8H19V9a2,2,0,0,1-2,2H16v1.586A1.986,1.986,0,0,1,15.414,14l-.65.65a7.334,7.334,0,0,1-.047,3.88,7.529,7.529,0,0,1-6.428,5.429A7.654,7.654,0,0,1,7.505,24Zm0-13a5.5,5.5,0,1,0,5.289,6.99,5.4,5.4,0,0,0-.1-3.3,1,1,0,0,1,.238-1.035L14,12.586V11a2,2,0,0,1,2-2h1V8a2,2,0,0,1,2-2h1.586l.672-.672A2.519,2.519,0,0,0,22,3.536,1.537,1.537,0,0,0,20.465,2a2.52,2.52,0,0,0-1.793.743l-8.331,8.33a1,1,0,0,1-1.036.237A5.462,5.462,0,0,0,7.5,11ZM5,18a1,1,0,1,0,1-1A1,1,0,0,0,5,18Z"/>
                                    </svg>
                                    <span class=" mr-3 ">پروفایل</span>
                                </a>
                            </li>
                            <li class="menu-item logout" id="logout">
                                <a href="{{ route('logout') }}"
                                   class="flex items-center p-3 my-1 text-cu-light rounded-s-full dark:text-white hover:bg-gray-100 light:hover:bg-gray-700 group">
                                    <svg
                                        class="flex-shrink-0 w-5 h-5  transition duration-75 text-cu-light group-hover:text-cu-light dark:group-hover:text-gray-700"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 18 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
                                    </svg>
                                    <span class="mr-3">خروج</span>
                                </a>
                            </li>


                        </ul>
                    </div>
                </div>

                <script>
                    if (window.location.pathname === '/dashboard') {
                        const lis = document.querySelectorAll('li');
                        lis.forEach(li => li.classList.remove('active'));
                        dashboard.classList.add('active');
                    }

                    // Get all the menu items
                    const menuItems = document.querySelectorAll('.menu-item');

                    // Function to handle click on menu items
                    function handleMenuItemClick(event) {
                        // Remove the active class from all menu items
                        menuItems.forEach(item => item.classList.remove('active'));

                        // Add the active class to the clicked menu item
                        event.currentTarget.classList.add('active');

                        // Save the selected menu item ID to the localStorage
                        localStorage.setItem('selectedMenuItem', event.currentTarget.id);
                    }

                    // Add event listeners to each menu item
                    menuItems.forEach(item => {
                        item.addEventListener('click', handleMenuItemClick);
                    });

                    // On page load, check if there's a selected menu item in the localStorage and set it as active
                    document.addEventListener('DOMContentLoaded', () => {
                        const selectedMenuItem = localStorage.getItem('selectedMenuItem');
                        if (selectedMenuItem) {
                            const menuItem = document.getElementById(selectedMenuItem);
                            if (menuItem) {
                                menuItem.classList.add('active');
                            }
                        }
                    });

                    // Function to handle click on child menu items
                    function handleChildMenuItemClick(event) {
                        const detailsElement = event.currentTarget.closest('details');
                        if (detailsElement) {
                            // Set the 'open' attribute for the details element
                            detailsElement.setAttribute('open', true);
                        }

                        // Remove the active class from all child menu items
                        const childMenuItems = document.querySelectorAll('.menu-item');
                        childMenuItems.forEach(item => item.classList.remove('active'));

                        // Add the active class to the clicked child menu item
                        event.currentTarget.classList.add('active');

                        // Save the selected child menu item ID to the localStorage
                        localStorage.setItem('selectedChildMenuItem', event.currentTarget.id);
                    }

                    // Add event listeners to each child menu item
                    const childMenuItems = document.querySelectorAll('.menu-item');
                    childMenuItems.forEach(item => {
                        item.addEventListener('click', handleChildMenuItemClick);
                    });

                    // On page load, check if there's a selected child menu item in the localStorage and set it as active
                    document.addEventListener('DOMContentLoaded', () => {
                        const selectedChildMenuItem = localStorage.getItem('selectedChildMenuItem');
                        if (selectedChildMenuItem) {
                            const childMenuItem = document.getElementById(selectedChildMenuItem);
                            if (childMenuItem) {
                                childMenuItem.classList.add('active');

                                const detailsElement = childMenuItem.closest('details');
                                if (detailsElement) {
                                    // Set the 'open' attribute for the details element
                                    detailsElement.setAttribute('open', true);
                                }
                            }
                        }
                    });


                    function handleLogout() {
                        // Clear the selected menu item and child menu item from localStorage
                        localStorage.removeItem('selectedMenuItem');
                        localStorage.removeItem('selectedChildMenuItem');
                    }

                    // Add event listener to the "خروج" (Logout) menu item
                    const logoutMenuItem = document.getElementById('logout');
                    logoutMenuItem.addEventListener('click', handleLogout);

                </script>


            </aside>

        </div>
        <div class="flex justify-center w-full lg:w-auto">
            <h3 class=" text-gray-700 text-center font-bold text-lg">
                    <span class="text-cu-blblack">
                        {{ env('APP_PERSIAN_NAME') }}
                    </span>
            </h3>
        </div>
    </div>
</header>

<!-- Main Content -->

<div class="flex-1 flex overflow-x-scroll">
    @yield('content')
</div>

<footer class="bg-gray-800 text-gray-300 py-4 px-8">
    <div class="max-w-4xl mx-auto text-center">
            <span>کلیه حقوق مادی این سامانه متعلق به
                {{ env('APP_PERSIAN_NAME') }}
                مرکز فناوری اطلاعات حوزه های علمیه کشور
                می باشد.</span>
    </div>
</footer>


</body>

</html>
