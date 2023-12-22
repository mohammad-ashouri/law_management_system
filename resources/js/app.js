import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';
import $ from 'jquery';
import Swal from 'sweetalert2';
// Initialization for ES Users
import {initTE, Modal, Ripple,} from "tw-elements";

initTE({Modal, Ripple});

window.Swal = Swal;

function swalFire(title = null, text, icon, confirmButtonText) {
    Swal.fire({
        title: title, text: text, icon: icon, confirmButtonText: confirmButtonText,
    });
}

function toggleModal(modalID) {
    var modal = document.getElementById(modalID);
    if (modal.classList.contains('modal-active')) {
        modal.classList.remove('animate-fade-in');
        modal.classList.add('animate-fade-out');
        setTimeout(() => {
            modal.classList.remove('modal-active');
            modal.classList.remove('animate-fade-out');
        }, 150);
    } else {
        modal.classList.add('modal-active');
        modal.classList.remove('animate-fade-out');
        modal.classList.add('animate-fade-in');
    }
}

function hasOnlyPersianCharacters(input) {
    var persianPattern = /^[\u0600-\u06FF0-9()\s]+$/;
    return persianPattern.test(input);
}

function hasOnlyEnglishCharacters(input) {
    var englishPattern = /^[a-zA-Z0-9\s-]+$/;
    return englishPattern.test(input);
}

function swalFireWithQuestion() {
    Swal.fire({
        title: 'آیا مطمئن هستید؟',
        text: 'این مقدار به صورت دائمی اضافه خواهد شد.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'خیر',
        confirmButtonText: 'بله',
    }).then((result) => {
        if (result.isConfirmed) {

        } else if (result.dismiss === Swal.DismissReason.cancel) {

        }
    });
}

function hasNumber(text) {
    return /\d/.test(text);
}

function resetFields() {
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => input.value = "");
    const selectors = document.querySelectorAll('select');
    selectors.forEach(select => select.value = "");
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => textarea.value = "");

    // const radios = document.querySelectorAll('input');
    // radios.forEach(input => input.selected = "");
    // const checkboxes = document.querySelectorAll("input");
    // checkboxes.forEach(input => input.selected = "");
}

function showLoadingPopup() {
    loading_popup.style.display = 'flex';
}

function hideLoadingPopup() {
    loading_popup.style.display = 'none';
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'), results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

//Get Jalali time and date
function getJalaliDate() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            type: 'GET',
            url: "/date",
            success: function (response) {
                resolve(response);
            },
            error: function (error) {
                reject(error);
            }
        });
    });
}

$(document).ready(function () {
    hideLoadingPopup();
    $('#backward_page').on('click', function () {
        window.history.back();
    });
    let pathname = window.location.pathname;
    if (pathname.includes("Laws/edit")) {
        $('#edit-law').on('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'آیا مطمئن هستید؟',
                text: 'این مقدار در سامانه اضافه خواهد شد.',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'خیر',
                confirmButtonText: 'بله',
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoadingPopup();
                    var form = $(this);
                    var formData = new FormData(form[0]);
                    $.ajax({
                        type: 'POST',
                        url: '/Laws/update',
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        }, success: function (response) {
                            if (response.errors) {
                                hideLoadingPopup();

                                for (let errorType in response.errors) {
                                    switch (errorType) {
                                        case 'wrongError':
                                        case 'nullTitle':
                                        case 'nullLawCode':
                                        case 'dupLawCode':
                                        case 'nullSessionCode':
                                        case 'nullType':
                                        case 'nullGroup':
                                        case 'nullApprover':
                                        case 'nullTopic':
                                        case 'nullBody':
                                        case 'nullKeyword':
                                        case 'nullApprovalDate':
                                        case 'nullIssueDate':
                                        case 'nullPromulgationDate':
                                        case 'wrongFile':
                                        case 'nullFile':
                                            swalFire('خطا!', response.errors[errorType][0], 'error', 'تلاش مجدد');
                                            break;
                                    }
                                }
                            } else if (response.success) {
                                hideLoadingPopup();
                                window.location.href = response.redirect;
                            }
                        }
                    });
                }
            });
        });
    } else {
        switch (pathname) {
            case "/Profile":
                resetFields();
                $('#change-password').submit(function (e) {
                    e.preventDefault();
                    var form = $(this);
                    var data = form.serialize();

                    $.ajax({
                        type: 'POST', url: "/ChangePasswordInc", data: data, headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        }, success: function (response) {
                            if (response.success) {
                                swalFire('عملیات موفقیت آمیز بود!', response.errors.passwordChanged[0], 'success', 'بستن');
                                oldPass.value = '';
                                newPass.value = '';
                                repeatNewPass.value = '';
                            } else {
                                if (response.errors.oldPassNull) {
                                    swalFire('خطا!', response.errors.oldPassNull[0], 'error', 'تلاش مجدد');
                                } else if (response.errors.newPassNull) {
                                    swalFire('خطا!', response.errors.newPassNull[0], 'error', 'تلاش مجدد');
                                } else if (response.errors.repeatNewPassNull) {
                                    swalFire('خطا!', response.errors.repeatNewPassNull[0], 'error', 'تلاش مجدد');
                                } else if (response.errors.lowerThan8) {
                                    swalFire('خطا!', response.errors.lowerThan8[0], 'error', 'تلاش مجدد');
                                } else if (response.errors.higherThan12) {
                                    swalFire('خطا!', response.errors.higherThan12[0], 'error', 'تلاش مجدد');
                                } else if (response.errors.wrongRepeat) {
                                    swalFire('خطا!', response.errors.wrongRepeat[0], 'error', 'تلاش مجدد');
                                } else if (response.errors.wrongPassword) {
                                    swalFire('خطا!', response.errors.wrongPassword[0], 'error', 'تلاش مجدد');
                                } else {
                                    location.reload();
                                }
                            }
                        }, error: function (xhr, textStatus, errorThrown) {
                            // console.log(xhr);
                        }
                    });
                });
                $('#change-user-image').submit(function (e) {
                    e.preventDefault();
                    var form = $(this);
                    var formData = new FormData(form[0]);
                    $.ajax({
                        type: 'POST',
                        url: "/ChangeUserImage",
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.success) {
                                location.reload();
                            } else {
                                if (response.errors.wrongImage) {
                                    swalFire('خطا!', response.errors.wrongImage[0], 'error', 'تلاش مجدد');
                                } else {
                                    location.reload();
                                }
                            }
                        }, error: function (xhr, textStatus, errorThrown) {
                            // console.log(xhr);
                        }
                    });
                });
                break;
            case "/UserManager":
                resetFields();
                //Search In User Manager
                $('#search-Username-UserManager').on('input', function () {
                    var inputUsername = $('#search-Username-UserManager').val().trim().toLowerCase();
                    var type = $('#search-type-UserManager').val();
                    $.ajax({
                        url: '/Search', type: 'GET', data: {
                            username: inputUsername, type: type, work: 'UserManagerSearch'
                        }, success: function (data) {
                            var tableBody = $('.w-full.border-collapse.rounded-lg.overflow-hidden.text-center tbody');
                            tableBody.empty();

                            data.forEach(function (user) {
                                var type;
                                switch (user.type) {
                                    case 1:
                                        type = 'ادمین کل';
                                        break;
                                    case 2:
                                        type = 'کارشناس ستاد';
                                        break;
                                    case 3:
                                        type = 'کارشناس فناوری استان';
                                        break;
                                }
                                var row = '<tr class="bg-white"><td class="px-6 py-4">' + user.username + '</td><td class="px-6 py-4">' + user.name + '</td><td class="px-6 py-4">' + type + '</td>';
                                if (user.active == 1) {
                                    row += '<td class="px-6 py-4">' + '<button type="submit" data-username="' + user.username + '" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-blue-300 ASUM" data-active="1">غیرفعال‌سازی</button>';
                                } else if (user.active == 0) {
                                    row += '<td class="px-6 py-4">' + '<button type="submit" data-username="' + user.username + '" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300 ASUM" data-active="0">فعال‌سازی</button>';
                                }
                                row += '</td>';
                                if (user.NTCP == 1) {
                                    row += '<td class="px-6 py-4">' + '<button type="submit" data-ntcp-username="' + user.username + '" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-blue-300 ntcp" data-ntcp="1">می باشد</button>';
                                } else if (user.NTCP == 0) {
                                    row += '<td class="px-6 py-4">' + '<button type="submit" data-ntcp-username="' + user.username + '" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300 ntcp" data-ntcp="0">نمی باشد</button>';
                                }
                                row += '</td>';
                                row += '<td class="px-6 py-4">' + '<button type="submit" data-rp-username="' + user.username + '" class="px-4 py-2 p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300 rp">بازنشانی رمز</button>';
                                row += '</td>';
                                row += '</tr>';
                                tableBody.append(row);
                            });
                        }, error: function () {
                            console.log('خطا در ارتباط با سرور');
                        }
                    });
                });
                $('#search-type-UserManager').on('change', function () {
                    var inputUsername = $('#search-Username-UserManager').val().trim().toLowerCase();
                    var type = $('#search-type-UserManager').val();
                    $.ajax({
                        url: '/Search', type: 'GET', data: {
                            username: inputUsername, type: type, work: 'UserManagerSearch'
                        }, success: function (data) {
                            var tableBody = $('.w-full.border-collapse.rounded-lg.overflow-hidden.text-center tbody');
                            tableBody.empty();

                            data.forEach(function (user) {
                                var type;
                                switch (user.type) {
                                    case 1:
                                        type = 'ادمین کل';
                                        break;
                                    case 2:
                                        type = 'کارشناس ستاد';
                                        break;
                                    case 3:
                                        type = 'کارشناس فناوری استان';
                                        break;
                                }
                                var row = '<tr class="bg-white"><td class="px-6 py-4">' + user.username + '</td><td class="px-6 py-4">' + user.name + '</td><td class="px-6 py-4">' + type + '</td>';
                                if (user.active == 1) {
                                    row += '<td class="px-6 py-4">' + '<button type="submit" data-username="' + user.username + '" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-blue-300 ASUM" data-active="1">غیرفعال‌سازی</button>';
                                } else if (user.active == 0) {
                                    row += '<td class="px-6 py-4">' + '<button type="submit" data-username="' + user.username + '" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300 ASUM" data-active="0">فعال‌سازی</button>';
                                }
                                row += '</td>';
                                if (user.NTCP == 1) {
                                    row += '<td class="px-6 py-4">' + '<button type="submit" data-ntcp-username="' + user.username + '" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-blue-300 ntcp" data-ntcp="1">می باشد</button>';
                                } else if (user.NTCP == 0) {
                                    row += '<td class="px-6 py-4">' + '<button type="submit" data-ntcp-username="' + user.username + '" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300 ntcp" data-ntcp="0">نمی باشد</button>';
                                }
                                row += '</td>';
                                row += '<td class="px-6 py-4">' + '<button type="submit" data-rp-username="' + user.username + '" class="px-4 py-2 p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300 rp">بازنشانی رمز</button>';
                                row += '</td>';
                                row += '</tr>';
                                tableBody.append(row);
                            });
                        }, error: function () {
                            console.log('خطا در ارتباط با سرور');
                        }
                    });
                });
                //Activation Status In User Manager
                $(document).on('click', '.ASUM', function (e) {
                    const username = $(this).data('username');
                    const active = $(this).data('active');
                    let status = null;
                    if (active == 1) {
                        status = 'غیرفعال';
                    } else if (active == 0) {
                        status = 'فعال';
                    }
                    e.preventDefault();
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'کاربر انتخاب شده ' + status + ' خواهد شد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST', url: '/ChangeUserActivationStatus', headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, data: {
                                    username: username,
                                }, success: function (response) {
                                    if (response.success) {
                                        swalFire('عملیات موفقیت آمیز بود!', response.message.changedUserActivation[0], 'success', 'بستن');
                                        const activeButton = $(`button[data-username="${username}"]`);
                                        if (active == 1) {
                                            activeButton.removeClass('bg-red-500').addClass('bg-green-500');
                                            activeButton.removeClass('hover:bg-red-600').addClass('hover:bg-green-600');
                                            activeButton.text('فعال‌سازی');
                                            activeButton.data('active', 0);
                                        } else if (active == 0) {
                                            activeButton.removeClass('bg-green-500').addClass('bg-red-500');
                                            activeButton.removeClass('hover:bg-green-600').addClass('hover:bg-red-600');
                                            activeButton.text('غیرفعال‌سازی');
                                            activeButton.data('active', 1);
                                        }
                                    } else {
                                        swalFire('خطا!', response.errors.changedUserActivationFailed[0], 'error', 'تلاش مجدد');
                                    }
                                }
                            });
                        }
                    });
                });
                //NTCP Status In User Manager
                $(document).on('click', '.ntcp', function (e) {
                    const username = $(this).data('ntcp-username');
                    const NTCP = $(this).data('ntcp');
                    let status = null;
                    if (NTCP == 1) {
                        status = 'نمی باشد';
                    } else if (NTCP == 0) {
                        status = 'می باشد';
                    }
                    e.preventDefault();
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'کاربر انتخاب شده نیازمند تغییر رمزعبور ' + status + '؟',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST', url: '/ChangeUserNTCP', headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, data: {
                                    username: username,
                                }, success: function (response) {
                                    if (response.success) {
                                        swalFire('عملیات موفقیت آمیز بود!', response.message.changedUserNTCP[0], 'success', 'بستن');
                                        const ntcpButton = $(`button[data-ntcp-username="${username}"]`);
                                        if (NTCP == 1) {
                                            ntcpButton.removeClass('bg-red-500').addClass('bg-green-500');
                                            ntcpButton.removeClass('hover:bg-red-600').addClass('hover:bg-green-600');
                                            ntcpButton.text('نمی باشد');
                                            ntcpButton.data('ntcp', 0);
                                        } else if (NTCP == 0) {
                                            ntcpButton.removeClass('bg-green-500').addClass('bg-red-500');
                                            ntcpButton.removeClass('hover:bg-green-600').addClass('hover:bg-red-600');
                                            ntcpButton.text('می باشد');
                                            ntcpButton.data('ntcp', 1);
                                        }
                                    } else {
                                        swalFire('خطا!', response.errors.changedUserNTCPFailed[0], 'error', 'تلاش مجدد');
                                    }
                                }
                            });
                        }
                    });
                });
                //Reset Password In User Manager
                $(document).on('click', '.rp', function (e) {
                    const username = $(this).data('rp-username');
                    let status = null;

                    e.preventDefault();
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'رمز عبور کاربر انتخاب شده به 12345678 بازنشانی خواهد شد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST', url: '/ResetPassword', headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, data: {
                                    username: username,
                                }, success: function (response) {
                                    if (response.success) {
                                        swalFire('عملیات موفقیت آمیز بود!', response.message.passwordResetted[0], 'success', 'بستن');
                                    } else {
                                        swalFire('خطا!', response.errors.resetPasswordFailed[0], 'error', 'تلاش مجدد');
                                    }
                                }
                            });
                        }
                    });
                });
                //Showing Or Hiding Modal
                $('#new-user-button, #cancel-new-user').on('click', function () {
                    toggleModal(newUserModal.id);
                });
                $('#edit-user-button, #cancel-edit-user').on('click', function () {
                    toggleModal(editUserModal.id);
                });
                $('#type').on('change', function () {
                    let provinceDiv = document.getElementById('provinceDiv');
                    if (this.value === '2') {
                        provinceDiv.classList.remove('hidden');
                    } else {
                        provinceDiv.classList.add('hidden');
                    }
                });
                $('#editedType').on('change', function () {
                    let provinceDiv = document.getElementById('editedProvinceDiv');
                    if (this.value === '2') {
                        provinceDiv.classList.remove('hidden');
                    } else {
                        provinceDiv.classList.add('hidden');
                    }
                });
                //New User
                $('#new-user').submit(function (e) {
                    e.preventDefault();
                    var name = document.getElementById('name').value;
                    var family = document.getElementById('family').value;
                    var username = document.getElementById('username').value;
                    var password = document.getElementById('password').value;
                    var repeat_password = document.getElementById('repeat-password').value;
                    var type = document.getElementById('type').value;

                    if (name.length === 0) {
                        swalFire('خطا!', 'نام وارد نشده است.', 'error', 'تلاش مجدد');
                    } else if (family.length === 0) {
                        swalFire('خطا!', 'نام وارد نشده است.', 'error', 'تلاش مجدد');
                    } else if (!hasOnlyPersianCharacters(name)) {
                        swalFire('خطا!', 'نام نمی تواند مقدار غیر از کاراکتر فارسی یا عدد داشته باشد.', 'error', 'تلاش مجدد');
                    } else if (!hasOnlyPersianCharacters(family)) {
                        swalFire('خطا!', 'نام خانوادگی نمی تواند مقدار غیر از کاراکتر فارسی یا عدد داشته باشد.', 'error', 'تلاش مجدد');
                    } else if (username.length === 0) {
                        swalFire('خطا!', 'نام کاربری وارد نشده است.', 'error', 'تلاش مجدد');
                    } else if (password.length === 0) {
                        swalFire('خطا!', 'رمز عبور وارد نشده است.', 'error', 'تلاش مجدد');
                    } else if (repeat_password.length === 0) {
                        swalFire('خطا!', 'تکرار رمز عبور وارد نشده است.', 'error', 'تلاش مجدد');
                    } else if (type.length === 0) {
                        swalFire('خطا!', 'نوع کاربری انتخاب نشده است.', 'error', 'تلاش مجدد');
                    } else if (hasOnlyPersianCharacters(username)) {
                        swalFire('خطا!', 'نام کاربری نمی تواند مقدار فارسی داشته باشد.', 'error', 'تلاش مجدد');
                    } else if (hasOnlyPersianCharacters(password)) {
                        swalFire('خطا!', 'رمز عبور نمی تواند مقدار فارسی داشته باشد.', 'error', 'تلاش مجدد');
                    } else if (hasOnlyPersianCharacters(repeat_password)) {
                        swalFire('خطا!', 'تکرار رمز عبور نمی تواند مقدار فارسی داشته باشد.', 'error', 'تلاش مجدد');
                    } else if (password !== repeat_password) {
                        swalFire('خطا!', 'رمز عبور و تکرار آن برابر نیست.', 'error', 'تلاش مجدد');
                    } else {
                        var form = $(this);
                        var data = form.serialize();

                        $.ajax({
                            type: 'POST', url: '/NewUser', data: data, headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            }, success: function (response) {
                                if (response.errors && response.errors.userFounded) {
                                    swalFire('خطا!', response.errors.userFounded[0], 'error', 'تلاش مجدد');
                                } else if (response.errors && response.errors.emptyProvince) {
                                    swalFire('خطا!', response.errors.emptyProvince[0], 'error', 'تلاش مجدد');
                                } else if (response.success) {
                                    swalFire('عملیات موفقیت آمیز بود!', response.message.userAdded[0], 'success', 'بستن');
                                    toggleModal(newUserModal.id);
                                    resetFields();
                                }

                            }
                        });
                    }
                });
                //Getting User Information
                $('#userIdForEdit').change(function (e) {
                    e.preventDefault();
                    if (userIdForEdit.value === null || userIdForEdit.value === '') {
                        swalFire('خطا!', 'کاربر انتخاب نشده است.', 'error', 'تلاش مجدد');
                    } else {
                        $.ajax({
                            type: 'GET', url: '/GetUserInfo', data: {
                                userID: userIdForEdit.value
                            }, success: function (response) {
                                userEditDiv.hidden = false;
                                editedName.value = response.name;
                                editedFamily.value = response.family;
                                editedType.value = response.type;
                                editedProvince.value = response.province_id;
                                let provinceDiv = document.getElementById('editedProvinceDiv');
                                if (response.type == '3') {
                                    provinceDiv.classList.remove('hidden');
                                } else {
                                    provinceDiv.classList.add('hidden');
                                }
                            }
                        });
                    }
                });
                //Edit User
                $('#edit-user').submit(function (e) {
                    e.preventDefault();
                    var userID = userIdForEdit.value;
                    var name = editedName.value;
                    var family = editedFamily.value;
                    var type = editedType.value;

                    if (name.length === 0) {
                        swalFire('خطا!', 'نام وارد نشده است.', 'error', 'تلاش مجدد');
                    } else if (family.length === 0) {
                        swalFire('خطا!', 'نام خانوادگی وارد نشده است.', 'error', 'تلاش مجدد');
                    } else if (!hasOnlyPersianCharacters(name)) {
                        swalFire('خطا!', 'نام نمی تواند مقدار غیر از کاراکتر فارسی یا عدد داشته باشد.', 'error', 'تلاش مجدد');
                    } else if (!hasOnlyPersianCharacters(family)) {
                        swalFire('خطا!', 'نام نمی تواند مقدار غیر از کاراکتر فارسی یا عدد داشته باشد.', 'error', 'تلاش مجدد');
                    } else if (userID.length === 0) {
                        swalFire('خطا!', 'کاربر انتخاب نشده است.', 'error', 'تلاش مجدد');
                    } else if (type.length === 0) {
                        swalFire('خطا!', 'نوع کاربری انتخاب نشده است.', 'error', 'تلاش مجدد');
                    } else {
                        var form = $(this);
                        var data = form.serialize();

                        $.ajax({
                            type: 'POST', url: '/EditUser', data: data, headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            }, success: function (response) {
                                if (response.errors && response.errors.userFounded) {
                                    swalFire('خطا!', response.errors.userFounded[0], 'error', 'تلاش مجدد');
                                } else if (response.errors && response.errors.emptyProvince) {
                                    swalFire('خطا!', response.errors.emptyProvince[0], 'error', 'تلاش مجدد');
                                } else if (response.success) {
                                    swalFire('عملیات موفقیت آمیز بود!', response.message.userEdited[0], 'success', 'بستن');
                                    toggleModal(editUserModal.id);
                                    resetFields();
                                }

                            }
                        });
                    }
                });
                break;
            case '/Types':
                resetFields();

                $('#new-type-button, #cancel-new-type').on('click', function () {
                    toggleModal(newTypeModal.id);
                });
                $('.absolute.inset-0.bg-gray-500.opacity-75.add').on('click', function () {
                    toggleModal(newTypeModal.id)
                });
                $('.absolute.inset-0.bg-gray-500.opacity-75.edit').on('click', function () {
                    toggleModal(editTypeModal.id)
                });
                $('#cancel-edit-type').on('click', function () {
                    toggleModal(editTypeModal.id);
                });
                $('#new-type').on('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'این مقدار به صورت دائمی اضافه خواهد شد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = $(this);
                            var data = form.serialize();
                            $.ajax({
                                type: 'POST', url: '/Types/create', data: data, headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, success: function (response) {
                                    if (response.errors) {
                                        if (response.errors.nullName) {
                                            swalFire('خطا!', response.errors.nullName[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.dupName) {
                                            swalFire('خطا!', response.errors.dupName[0], 'error', 'تلاش مجدد');
                                        }
                                    } else if (response.success) {
                                        location.reload();
                                        resetFields();
                                    }
                                }
                            });
                        }
                    });
                });
                $('.TypeControl').on('click', function () {
                    $.ajax({
                        type: 'GET', url: '/Types/getInfo', data: {
                            id: $(this).data('id')
                        }, success: function (response) {
                            if (response) {
                                type_id.value = response.id;
                                nameForEdit.value = response.name;
                                toggleModal(editTypeModal.id);
                            }
                        }
                    });
                });
                $('#edit-type').on('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'با ویرایش این مقدار، تمامی فیلدها تغییر خواهند کرد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = $(this);
                            var data = form.serialize();
                            $.ajax({
                                type: 'POST', url: '/Types/update', data: data, headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, success: function (response) {
                                    if (response.errors) {
                                        if (response.errors.nullName) {
                                            swalFire('خطا!', response.errors.nullName[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.dupName) {
                                            swalFire('خطا!', response.errors.dupName[0], 'error', 'تلاش مجدد');
                                        }
                                    } else if (response.success) {
                                        location.reload();
                                        resetFields();
                                    }
                                }
                            });
                        }
                    });
                });
                $('.changeStatusTypeControl').on('click', function () {
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'وضعیت این کاتالوگ تغییر خواهد کرد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST', url: '/Types/changeStatus', data: {
                                    id: $(this).data('id')
                                }, headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, success: function (response) {
                                    location.reload();
                                }
                            });
                        }
                    });

                });
                break;
            case '/Groups':
                resetFields();

                $('#new-group-button, #cancel-new-group').on('click', function () {
                    toggleModal(newGroupModal.id);
                });
                $('.absolute.inset-0.bg-gray-500.opacity-75.add').on('click', function () {
                    toggleModal(newGroupModal.id)
                });
                $('.absolute.inset-0.bg-gray-500.opacity-75.edit').on('click', function () {
                    toggleModal(editGroupModal.id)
                });
                $('#cancel-edit-group').on('click', function () {
                    toggleModal(editGroupModal.id);
                });
                $('#new-group').on('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'این مقدار به صورت دائمی اضافه خواهد شد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = $(this);
                            var data = form.serialize();
                            $.ajax({
                                type: 'POST', url: '/Groups/create', data: data, headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, success: function (response) {
                                    if (response.errors) {
                                        if (response.errors.nullName) {
                                            swalFire('خطا!', response.errors.nullName[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.dupName) {
                                            swalFire('خطا!', response.errors.dupName[0], 'error', 'تلاش مجدد');
                                        }
                                    } else if (response.success) {
                                        location.reload();
                                        resetFields();
                                    }
                                }
                            });
                        }
                    });
                });
                $('.GroupControl').on('click', function () {
                    $.ajax({
                        type: 'GET', url: '/Groups/getInfo', data: {
                            id: $(this).data('id')
                        }, success: function (response) {
                            if (response) {
                                group_id.value = response.id;
                                nameForEdit.value = response.name;
                                toggleModal(editGroupModal.id);
                            }
                        }
                    });
                });
                $('#edit-group').on('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'با ویرایش این مقدار، تمامی فیلدها تغییر خواهند کرد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = $(this);
                            var data = form.serialize();
                            $.ajax({
                                type: 'POST', url: '/Groups/update', data: data, headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, success: function (response) {
                                    if (response.errors) {
                                        if (response.errors.nullName) {
                                            swalFire('خطا!', response.errors.nullName[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.dupName) {
                                            swalFire('خطا!', response.errors.dupName[0], 'error', 'تلاش مجدد');
                                        }
                                    } else if (response.success) {
                                        location.reload();
                                        resetFields();
                                    }
                                }
                            });
                        }
                    });
                });
                $('.changeStatusGroupControl').on('click', function () {
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'وضعیت این کاتالوگ تغییر خواهد کرد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST', url: '/Groups/changeStatus', data: {
                                    id: $(this).data('id')
                                }, headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, success: function (response) {
                                    location.reload();
                                }
                            });
                        }
                    });

                });
                break;
            case '/Topics':
                resetFields();

                $('#new-topic-button, #cancel-new-topic').on('click', function () {
                    toggleModal(newTopicModal.id);
                });
                $('.absolute.inset-0.bg-gray-500.opacity-75.add').on('click', function () {
                    toggleModal(newTopicModal.id)
                });
                $('.absolute.inset-0.bg-gray-500.opacity-75.edit').on('click', function () {
                    toggleModal(editTopicModal.id)
                });
                $('#cancel-edit-topic').on('click', function () {
                    toggleModal(editTopicModal.id);
                });
                $('#new-topic').on('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'این مقدار به صورت دائمی اضافه خواهد شد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = $(this);
                            var data = form.serialize();
                            $.ajax({
                                type: 'POST', url: '/Topics/create', data: data, headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, success: function (response) {
                                    if (response.errors) {
                                        if (response.errors.nullName) {
                                            swalFire('خطا!', response.errors.nullName[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.dupName) {
                                            swalFire('خطا!', response.errors.dupName[0], 'error', 'تلاش مجدد');
                                        }
                                    } else if (response.success) {
                                        location.reload();
                                        resetFields();
                                    }
                                }
                            });
                        }
                    });
                });
                $('.TopicControl').on('click', function () {
                    $.ajax({
                        type: 'GET', url: '/Topics/getInfo', data: {
                            id: $(this).data('id')
                        }, success: function (response) {
                            if (response) {
                                topic_id.value = response.id;
                                nameForEdit.value = response.name;
                                toggleModal(editTopicModal.id);
                            }
                        }
                    });
                });
                $('#edit-topic').on('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'با ویرایش این مقدار، تمامی فیلدها تغییر خواهند کرد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = $(this);
                            var data = form.serialize();
                            $.ajax({
                                type: 'POST', url: '/Topics/update', data: data, headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, success: function (response) {
                                    if (response.errors) {
                                        if (response.errors.nullName) {
                                            swalFire('خطا!', response.errors.nullName[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.dupName) {
                                            swalFire('خطا!', response.errors.dupName[0], 'error', 'تلاش مجدد');
                                        }
                                    } else if (response.success) {
                                        location.reload();
                                        resetFields();
                                    }
                                }
                            });
                        }
                    });
                });
                $('.changeStatusTopicControl').on('click', function () {
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'وضعیت این کاتالوگ تغییر خواهد کرد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST', url: '/Topics/changeStatus', data: {
                                    id: $(this).data('id')
                                }, headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, success: function (response) {
                                    location.reload();
                                }
                            });
                        }
                    });

                });
                break;
            case '/Approvers':
                resetFields();

                $('#new-approver-button, #cancel-new-approver').on('click', function () {
                    toggleModal(newApproverModal.id);
                });
                $('.absolute.inset-0.bg-gray-500.opacity-75.add').on('click', function () {
                    toggleModal(newApproverModal.id)
                });
                $('.absolute.inset-0.bg-gray-500.opacity-75.edit').on('click', function () {
                    toggleModal(editApproverModal.id)
                });
                $('#cancel-edit-approver').on('click', function () {
                    toggleModal(editApproverModal.id);
                });
                $('#new-approver').on('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'این مقدار به صورت دائمی اضافه خواهد شد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = $(this);
                            var data = form.serialize();
                            $.ajax({
                                type: 'POST', url: '/Approvers/create', data: data, headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, success: function (response) {
                                    if (response.errors) {
                                        if (response.errors.nullName) {
                                            swalFire('خطا!', response.errors.nullName[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.dupName) {
                                            swalFire('خطا!', response.errors.dupName[0], 'error', 'تلاش مجدد');
                                        }
                                    } else if (response.success) {
                                        location.reload();
                                        resetFields();
                                    }
                                }
                            });
                        }
                    });
                });
                $('.ApproverControl').on('click', function () {
                    $.ajax({
                        type: 'GET', url: '/Approvers/getInfo', data: {
                            id: $(this).data('id')
                        }, success: function (response) {
                            if (response) {
                                approver_id.value = response.id;
                                nameForEdit.value = response.name;
                                toggleModal(editApproverModal.id);
                            }
                        }
                    });
                });
                $('#edit-approver').on('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'با ویرایش این مقدار، تمامی فیلدها تغییر خواهند کرد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = $(this);
                            var data = form.serialize();
                            $.ajax({
                                type: 'POST', url: '/Approvers/update', data: data, headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, success: function (response) {
                                    if (response.errors) {
                                        if (response.errors.nullName) {
                                            swalFire('خطا!', response.errors.nullName[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.dupName) {
                                            swalFire('خطا!', response.errors.dupName[0], 'error', 'تلاش مجدد');
                                        }
                                    } else if (response.success) {
                                        location.reload();
                                        resetFields();
                                    }
                                }
                            });
                        }
                    });
                });
                $('.changeStatusApproverControl').on('click', function () {
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'وضعیت این کاتالوگ تغییر خواهد کرد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST', url: '/Approvers/changeStatus', data: {
                                    id: $(this).data('id')
                                }, headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, success: function (response) {
                                    location.reload();
                                }
                            });
                        }
                    });

                });
                break;
            case '/Laws':
                // resetFields();
                $('.LawControl').on('click', function () {
                    $.ajax({
                        type: 'GET', url: '/Laws/getInfo', data: {
                            id: $(this).data('id')
                        }, success: function (response) {
                            if (response) {
                                law_id.value = response.id;
                                nameForEdit.value = response.name;
                                toggleModal(editLawModal.id);
                            }
                        }
                    });
                });
                $('.deleteLaw').on('click', function () {
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'این مصوبه برای همیشه حذف خواهد شد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST', url: '/Laws/delete', data: {
                                    id: $(this).data('id')
                                }, headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, success: function (response) {
                                    if (response.errors) {
                                        if (response.errors.wrongError) {
                                            swalFire('خطا!', response.errors.wrongError[0], 'error', 'تلاش مجدد');
                                        }
                                    } else if (response.success) {
                                        location.reload();
                                    }
                                }
                            });
                        }
                    });

                });
                $('#LawSearch').on('submit', function (e) {
                    e.preventDefault();
                    if (approval_day.value !== '' && approval_month.value === '' && approval_year.value === '') {
                        swalFire('خطا!', 'تاریخ تصویب را به صورت کامل وارد نمایید.', 'error', 'تلاش مجدد');
                    } else if (approval_day.value === '' && approval_month.value !== '' && approval_year.value === '') {
                        swalFire('خطا!', 'تاریخ تصویب را به صورت کامل وارد نمایید.', 'error', 'تلاش مجدد');
                    } else if (approval_day.value === '' && approval_month.value === '' && approval_year.value !== '') {
                        swalFire('خطا!', 'تاریخ تصویب را به صورت کامل وارد نمایید.', 'error', 'تلاش مجدد');
                    } else if (approval_day.value === '' && approval_month.value !== '' && approval_year.value !== '') {
                        swalFire('خطا!', 'تاریخ تصویب را به صورت کامل وارد نمایید.', 'error', 'تلاش مجدد');
                    } else if (approval_day.value !== '' && approval_month.value === '' && approval_year.value !== '') {
                        swalFire('خطا!', 'تاریخ تصویب را به صورت کامل وارد نمایید.', 'error', 'تلاش مجدد');
                    } else if (approval_day.value !== '' && approval_month.value !== '' && approval_year.value === '') {
                        swalFire('خطا!', 'تاریخ تصویب را به صورت کامل وارد نمایید.', 'error', 'تلاش مجدد');
                    } else if (issue_day.value !== '' && issue_month.value === '' && issue_year.value === '') {
                        swalFire('خطا!', 'تاریخ صدور را به صورت کامل انتخاب نمایید.', 'error', 'تلاش مجدد');
                    } else if (issue_day.value === '' && issue_month.value !== '' && issue_year.value === '') {
                        swalFire('خطا!', 'تاریخ صدور را به صورت کامل انتخاب نمایید.', 'error', 'تلاش مجدد');
                    } else if (issue_day.value === '' && issue_month.value === '' && issue_year.value !== '') {
                        swalFire('خطا!', 'تاریخ صدور را به صورت کامل انتخاب نمایید.', 'error', 'تلاش مجدد');
                    } else if (issue_day.value === '' && issue_month.value !== '' && issue_year.value !== '') {
                        swalFire('خطا!', 'تاریخ صدور را به صورت کامل انتخاب نمایید.', 'error', 'تلاش مجدد');
                    } else if (issue_day.value !== '' && issue_month.value === '' && issue_year.value !== '') {
                        swalFire('خطا!', 'تاریخ صدور را به صورت کامل انتخاب نمایید.', 'error', 'تلاش مجدد');
                    } else if (issue_day.value !== '' && issue_month.value !== '' && issue_year.value === '') {
                        swalFire('خطا!', 'تاریخ ابلاع را به صورت کامل انتخاب نمایید.', 'error', 'تلاش مجدد');
                    } else if (promulgation_day.value !== '' && promulgation_month.value === '' && promulgation_year.value === '') {
                        swalFire('خطا!', 'تاریخ ابلاع را به صورت کامل انتخاب نمایید.', 'error', 'تلاش مجدد');
                    } else if (promulgation_day.value === '' && promulgation_month.value !== '' && promulgation_year.value === '') {
                        swalFire('خطا!', 'تاریخ ابلاع را به صورت کامل انتخاب نمایید.', 'error', 'تلاش مجدد');
                    } else if (promulgation_day.value === '' && promulgation_month.value === '' && promulgation_year.value !== '') {
                        swalFire('خطا!', 'تاریخ ابلاع را به صورت کامل انتخاب نمایید.', 'error', 'تلاش مجدد');
                    } else if (promulgation_day.value === '' && promulgation_month.value !== '' && promulgation_year.value !== '') {
                        swalFire('خطا!', 'تاریخ ابلاع را به صورت کامل انتخاب نمایید.', 'error', 'تلاش مجدد');
                    } else if (promulgation_day.value !== '' && promulgation_month.value === '' && promulgation_year.value !== '') {
                        swalFire('خطا!', 'تاریخ ابلاع را به صورت کامل انتخاب نمایید.', 'error', 'تلاش مجدد');
                    } else if (promulgation_day.value !== '' && promulgation_month.value !== '' && promulgation_year.value === '') {
                        swalFire('خطا!', 'تاریخ ابلاع را به صورت کامل انتخاب نمایید.', 'error', 'تلاش مجدد');
                    } else {
                        this.submit();
                    }
                });
                break;
            case '/Laws/new':
                resetFields();
                $('#new-law').on('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'آیا مطمئن هستید؟',
                        text: 'این مقدار در سامانه اضافه خواهد شد.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'خیر',
                        confirmButtonText: 'بله',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showLoadingPopup();
                            var form = $(this);
                            var formData = new FormData(form[0]);
                            $.ajax({
                                type: 'POST',
                                url: '/Laws/create',
                                data: formData,
                                contentType: false,
                                processData: false,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                }, success: function (response) {
                                    if (response.errors) {
                                        if (response.errors.nullTitle) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.nullTitle[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.nullLawCode) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.nullLawCode[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.dupLawCode) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.dupLawCode[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.nullSessionCode) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.nullSessionCode[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.nullType) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.nullType[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.nullGroup) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.nullGroup[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.nullApprover) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.nullApprover[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.nullTopic) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.nullTopic[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.nullBody) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.nullBody[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.nullKeyword) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.nullKeyword[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.nullApprovalDate) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.nullApprovalDate[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.nullIssueDate) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.nullIssueDate[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.nullPromulgationDate) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.nullPromulgationDate[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.wrongFile) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.wrongFile[0], 'error', 'تلاش مجدد');
                                        } else if (response.errors.nullFile) {
                                            hideLoadingPopup();
                                            swalFire('خطا!', response.errors.nullFile[0], 'error', 'تلاش مجدد');
                                        }
                                    } else if (response.success) {
                                        window.location.href = response.redirect;
                                    }
                                }
                            });
                        }
                    });
                });
                break;
        }
    }
});
