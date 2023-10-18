<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_PERSIAN_NAME') }}</title>
    @vite(['resources/css/login.css','resources/css/app.css','resources/js/login.js'])
</head>
<body>

<div class="login-wrap">
    <div class="login-html text-center">
        <input  type="checkbox" name="tab" class="sign-in cursor-none" checked><label class="tab cursor-default">
            {{ env('APP_PERSIAN_NAME') }}
        </label>
        <div class="login-form text-right">
            <form id="loginForm" method="post"
                  action="/login">
                @csrf
                <div class="sign-in-htm">
                    <div class="group">
                        <label for="username" class="label mb-1">نام کاربری</label>
                        <input name="username" autocomplete="off" id="username" type="text" placeholder="نام کاربری را وارد کنید" class="input">
                    </div>
                    <div class="group">
                        <label for="password" class="label mb-1">رمز عبور</label>
                        <input id="password" name="password" autocomplete="new-password" type="password"
                               placeholder="رمز عبور را وارد کنید" class="input">
                    </div>

                    <div class="group flex">
                        <div class="flex justify-evenly md:justify-normal">
                            <img id="captchaImg" src="/captcha" alt="Captcha" class="w-32 h-10  mt-2 rounded">
                            <button type="button" onclick="reloadCaptcha()" title="تازه سازی کلمه امنیتی"
                                    class="h-10 p-1 bg-gray-300 hover:bg-gray-400 rounded mt-2">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <input name="captcha"
                               class="input md:mr-8 "
                               id="captcha" placeholder="کد امنیتی را وارد کنید" type="text">
                    </div>

                    <div class="group">
                        <button class="button" type="submit">ورود</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="./build/plugins/jquery/dist/jquery.js"></script>
<script>
    function reloadCaptcha() {
        var captchaImg = document.getElementById('captchaImg');
        var captchaUrl = "/captcha";
        captchaImg.src = captchaUrl + '?' + Date.now();
    }
</script>
</body>
</html>
