import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';
import $ from 'jquery';
import Swal from 'sweetalert2';
window.Swal = Swal;

function swalFire(title = null, text, icon, confirmButtonText) {
    Swal.fire({
        title: title, text: text, icon: icon, confirmButtonText: confirmButtonText,
    });
}


//Check Login Form
$('#loginForm').submit(function (e) {
    e.preventDefault();

    var form = $(this);
    var url = form.attr('action');
    var data = form.serialize();

    $.ajax({
        type: 'POST', url: url, data: data, headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }, success: function (response) {
            if (response.success) {
                localStorage.setItem('selectedTab', 1);
                window.location.href = response.redirect;
            } else {
                if (response.errors.username) {
                    swalFire('خطای نام کاربری', response.errors.username[0], 'error', 'تلاش مجدد');
                    reloadCaptcha();
                    captcha.value = '';
                }

                if (response.errors.password) {
                    swalFire('خطای رمز عبور', response.errors.password[0], 'error', 'تلاش مجدد');
                    reloadCaptcha();
                    captcha.value = '';
                }

                if (response.errors.captcha) {
                    swalFire('کد امنیتی نامعتبر', response.errors.captcha[0], 'error', 'تلاش مجدد');
                    reloadCaptcha();
                    captcha.value = '';
                }
                if (response.errors.loginError) {
                    swalFire('نام کاربری یا رمز عبور نامعتبر', response.errors.loginError[0], 'error', 'تلاش مجدد');
                    reloadCaptcha();
                    captcha.value = '';
                }
            }
        }, error: function (xhr, textStatus, errorThrown) {
            if (xhr.responseJSON['YouAreLocked']) {
                swalFire('دسترسی غیرمجاز', 'آی پی شما به دلیل تعداد درخواست های زیاد بلاک شده است. لطفا یک ساعت دیگر مجددا تلاش کنید.', 'error', 'تایید');
                const fields = [username, password, captcha];
                fields.forEach(field => {
                    field.disabled = true;
                    field.value = null;
                    field.style.backgroundColor = 'gray';
                });
            } else {
                swalFire('خطای ناشناخته', 'ارتباط با سرور برقرار نشد.', 'error', 'تلاش مجدد');
                console.clear();
            }

        }
    });
});
