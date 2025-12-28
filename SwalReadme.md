# 🍬 SweetAlert2 - Complete Guide

## 📋 Mục lục

1. [Giới thiệu](#giới-thiệu)
2. [Cài đặt](#cài-đặt)
3. [Sử dụng cơ bản](#sử-dụng-cơ-bản)
4. [Các loại Alert](#các-loại-alert)
5. [Tính năng nâng cao](#tính-năng-nâng-cao)
6. [Examples thực tế](#examples-thực-tế)
7. [Customization](#customization)
8. [Troubleshooting](#troubleshooting)
9. [Best Practices](#best-practices)

---

## 🎯 GIỚI THIỆU

**SweetAlert2** là một thư viện JavaScript tạo các popup/modal đẹp, dễ dùng và responsive thay thế cho `alert()`, `confirm()`, `prompt()` mặc định của browser.

### Ưu điểm:

✅ **Đẹp** - UI hiện đại, responsive
✅ **Dễ dùng** - API đơn giản, rõ ràng
✅ **Tùy biến cao** - Nhiều options
✅ **Không phụ thuộc** - Pure JavaScript, không cần jQuery
✅ **Nhẹ** - ~20KB minified + gzipped
✅ **Accessibility** - Hỗ trợ keyboard navigation
✅ **Browser support** - IE11+, Chrome, Firefox, Safari, Edge

### So sánh với alert() mặc định:

```javascript
// ❌ Alert mặc định - XẤU
alert('Hello World!');

// ✅ SweetAlert2 - ĐẸP
Swal.fire('Hello World!');
```

---

## 💾 CÀI ĐẶT

### Method 1: CDN (Khuyến nghị cho bắt đầu) ⭐

**Quick Start - 30 giây:**

```html
<!DOCTYPE html>
<html>
<head>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <button onclick="Swal.fire('Hello!')">Click me</button>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
```

**CDN Options:**

```html
<!-- jsDelivr (Recommended) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- unpkg -->
<script src="https://unpkg.com/sweetalert2@11"></script>

<!-- cdnjs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.3/sweetalert2.min.js"></script>
```

### Method 2: NPM (Cho projects lớn)

```bash
# Install
npm install sweetalert2

# Hoặc với yarn
yarn add sweetalert2
```

**Import trong JavaScript:**

```javascript
// ES6 Module
import Swal from 'sweetalert2'

// CommonJS
const Swal = require('sweetalert2')
```

**Import CSS:**

```javascript
// Trong file JavaScript
import 'sweetalert2/dist/sweetalert2.min.css'
```

Hoặc trong file SCSS/CSS:

```scss
@import 'sweetalert2/src/sweetalert2.scss';
```

### Method 3: Download local

```bash
# Download từ GitHub
wget https://github.com/sweetalert2/sweetalert2/releases/download/v11.10.3/sweetalert2.zip
unzip sweetalert2.zip
```

**Include:**

```html
<link href="path/to/sweetalert2.min.css" rel="stylesheet">
<script src="path/to/sweetalert2.min.js"></script>
```

---

## 🚀 SỬ DỤNG CƠ BẢN

### Cú pháp đơn giản nhất:

```javascript
Swal.fire('Hello World!')
```

### Với title và text:

```javascript
Swal.fire('Title', 'Text message', 'success')
```

### Với options object (Khuyến nghị):

```javascript
Swal.fire({
    title: 'Title',
    text: 'Text message',
    icon: 'success'
})
```

### Kiểm tra kết quả (Promise-based):

```javascript
Swal.fire({
    title: 'Bạn có chắc?',
    text: 'Hành động này không thể hoàn tác!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Có, xóa đi!',
    cancelButtonText: 'Hủy'
}).then((result) => {
    if (result.isConfirmed) {
        // User clicked "Có, xóa đi!"
        console.log('Confirmed!');
    } else if (result.isDismissed) {
        // User clicked "Hủy" or closed
        console.log('Cancelled!');
    }
})
```

---

## 🎨 CÁC LOẠI ALERT

### 1. Success (Thành công)

```javascript
Swal.fire({
    icon: 'success',
    title: 'Thành công!',
    text: 'Dữ liệu đã được lưu',
    timer: 2000,
    timerProgressBar: true
})
```

**Preview:**
```
[✓ Icon xanh]
Thành công!
Dữ liệu đã được lưu
[Progress bar]
[OK button]
```

### 2. Error (Lỗi)

```javascript
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: 'Có lỗi xảy ra!',
    footer: '<a href="#">Tại sao tôi gặp lỗi này?</a>'
})
```

**Preview:**
```
[X Icon đỏ]
Oops...
Có lỗi xảy ra!
[Tại sao tôi gặp lỗi này?]
[OK button]
```

### 3. Warning (Cảnh báo)

```javascript
Swal.fire({
    icon: 'warning',
    title: 'Cảnh báo!',
    text: 'Bạn chưa lưu thay đổi',
    showCancelButton: true,
    confirmButtonText: 'Lưu ngay',
    cancelButtonText: 'Bỏ qua'
})
```

**Preview:**
```
[! Icon vàng]
Cảnh báo!
Bạn chưa lưu thay đổi
[Lưu ngay] [Bỏ qua]
```

### 4. Info (Thông tin)

```javascript
Swal.fire({
    icon: 'info',
    title: 'Thông tin',
    text: 'Phiên bản mới đã có sẵn!'
})
```

**Preview:**
```
[i Icon xanh dương]
Thông tin
Phiên bản mới đã có sẵn!
[OK button]
```

### 5. Question (Câu hỏi)

```javascript
Swal.fire({
    icon: 'question',
    title: 'Bạn có chắc không?',
    text: 'Thao tác này sẽ xóa dữ liệu',
    showCancelButton: true
})
```

**Preview:**
```
[? Icon]
Bạn có chắc không?
Thao tác này sẽ xóa dữ liệu
[OK] [Cancel]
```

### 6. No Icon (Không icon)

```javascript
Swal.fire({
    title: 'Custom Alert',
    text: 'Không có icon',
    // Không có icon property
})
```

---

## 🔥 TÍNH NĂNG NÂNG CAO

### 1. Confirm Dialog (Xác nhận)

```javascript
Swal.fire({
    title: 'Bạn có chắc muốn xóa?',
    text: "Hành động này không thể hoàn tác!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Có, xóa đi!',
    cancelButtonText: 'Hủy'
}).then((result) => {
    if (result.isConfirmed) {
        // User clicked "Có, xóa đi!"
        Swal.fire(
            'Đã xóa!',
            'Dữ liệu đã được xóa.',
            'success'
        )
    }
})
```

### 2. Input Dialog (Nhập liệu)

**Text Input:**

```javascript
Swal.fire({
    title: 'Nhập tên của bạn',
    input: 'text',
    inputPlaceholder: 'Tên của bạn',
    showCancelButton: true,
    inputValidator: (value) => {
        if (!value) {
            return 'Bạn cần nhập tên!'
        }
    }
}).then((result) => {
    if (result.isConfirmed) {
        Swal.fire(`Xin chào ${result.value}!`)
    }
})
```

**Email Input:**

```javascript
Swal.fire({
    title: 'Nhập email',
    input: 'email',
    inputPlaceholder: 'example@example.com'
})
```

**Password Input:**

```javascript
Swal.fire({
    title: 'Nhập mật khẩu',
    input: 'password',
    inputPlaceholder: 'Mật khẩu'
})
```

**Textarea:**

```javascript
Swal.fire({
    title: 'Nhập nội dung',
    input: 'textarea',
    inputPlaceholder: 'Nội dung của bạn...',
    inputAttributes: {
        'aria-label': 'Nhập nội dung'
    }
})
```

**Select Dropdown:**

```javascript
Swal.fire({
    title: 'Chọn quốc gia',
    input: 'select',
    inputOptions: {
        'VN': 'Việt Nam',
        'US': 'United States',
        'UK': 'United Kingdom',
        'JP': 'Japan'
    },
    inputPlaceholder: 'Chọn quốc gia',
    showCancelButton: true
})
```

**Radio Buttons:**

```javascript
Swal.fire({
    title: 'Chọn màu yêu thích',
    input: 'radio',
    inputOptions: {
        '#ff0000': 'Đỏ',
        '#00ff00': 'Xanh lá',
        '#0000ff': 'Xanh dương'
    },
    inputValidator: (value) => {
        if (!value) {
            return 'Bạn cần chọn một màu!'
        }
    }
})
```

**Checkbox:**

```javascript
Swal.fire({
    title: 'Điều khoản và điều kiện',
    input: 'checkbox',
    inputValue: 0,
    inputPlaceholder: 'Tôi đồng ý với điều khoản và điều kiện',
    confirmButtonText: 'Tiếp tục',
    inputValidator: (result) => {
        return !result && 'Bạn cần đồng ý với điều khoản!'
    }
})
```

**File Upload:**

```javascript
Swal.fire({
    title: 'Chọn file',
    input: 'file',
    inputAttributes: {
        'accept': 'image/*',
        'aria-label': 'Upload ảnh'
    }
})
```

**Range Slider:**

```javascript
Swal.fire({
    title: 'Chọn tuổi',
    input: 'range',
    inputLabel: 'Tuổi của bạn',
    inputAttributes: {
        min: 0,
        max: 100,
        step: 1
    },
    inputValue: 25
})
```

### 3. Loading / Progress

**Show Loading:**

```javascript
Swal.fire({
    title: 'Đang xử lý...',
    html: 'Vui lòng chờ',
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
        Swal.showLoading()
    }
})

// Ẩn loading sau khi hoàn thành
setTimeout(() => {
    Swal.close()
}, 3000)
```

**With Progress Bar:**

```javascript
let timerInterval
Swal.fire({
    title: 'Auto close alert!',
    html: 'Đóng sau <b></b> milliseconds.',
    timer: 5000,
    timerProgressBar: true,
    didOpen: () => {
        Swal.showLoading()
        const timer = Swal.getPopup().querySelector('b')
        timerInterval = setInterval(() => {
            timer.textContent = `${Swal.getTimerLeft()}`
        }, 100)
    },
    willClose: () => {
        clearInterval(timerInterval)
    }
}).then((result) => {
    if (result.dismiss === Swal.DismissReason.timer) {
        console.log('Đã đóng do hết thời gian')
    }
})
```

### 4. HTML Content

```javascript
Swal.fire({
    title: '<strong>HTML Content</strong>',
    icon: 'info',
    html:
        'Bạn có thể dùng <b>bold text</b>, ' +
        '<a href="//sweetalert2.github.io">links</a>, ' +
        'và các HTML tags khác',
    showCloseButton: true,
    showCancelButton: true,
    focusConfirm: false,
    confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!',
    cancelButtonText: '<i class="fa fa-thumbs-down"></i>'
})
```

### 5. Multiple Inputs

```javascript
Swal.fire({
    title: 'Đăng ký',
    html:
        '<input id="swal-input1" class="swal2-input" placeholder="Tên">' +
        '<input id="swal-input2" class="swal2-input" placeholder="Email">',
    focusConfirm: false,
    preConfirm: () => {
        return {
            name: document.getElementById('swal-input1').value,
            email: document.getElementById('swal-input2').value
        }
    }
}).then((result) => {
    console.log(result.value)
    // {name: "...", email: "..."}
})
```

### 6. Toast Notifications

```javascript
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

Toast.fire({
    icon: 'success',
    title: 'Đã lưu thành công'
})
```

**Toast Positions:**
- `'top'`, `'top-start'`, `'top-end'`
- `'center'`, `'center-start'`, `'center-end'`
- `'bottom'`, `'bottom-start'`, `'bottom-end'`

### 7. Steps / Wizard

```javascript
const steps = ['1', '2', '3']
const swalQueueStep = Swal.mixin({
    confirmButtonText: 'Tiếp',
    cancelButtonText: 'Quay lại',
    progressSteps: steps,
    showCancelButton: true,
    reverseButtons: true
})

async function wizard() {
    const values = []
    let currentStep
    
    for (currentStep = 0; currentStep < steps.length;) {
        const result = await swalQueueStep.fire({
            title: `Bước ${currentStep + 1}`,
            text: `Nhập thông tin bước ${currentStep + 1}`,
            input: 'text',
            inputValue: values[currentStep],
            currentProgressStep: currentStep
        })
        
        if (result.value) {
            values[currentStep] = result.value
            currentStep++
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            currentStep--
        } else {
            break
        }
    }
    
    if (currentStep === steps.length) {
        Swal.fire({
            title: 'Hoàn thành!',
            html: 'Kết quả: ' + JSON.stringify(values)
        })
    }
}

wizard()
```

### 8. Dynamic Queue

```javascript
const queue = ['Bước 1', 'Bước 2', 'Bước 3']

Swal.queue(queue.map((step) => {
    return {
        title: step,
        confirmButtonText: 'Tiếp',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve()
                }, 1000)
            })
        }
    }
}))
```

### 9. AJAX Request Example

```javascript
Swal.fire({
    title: 'Gửi yêu cầu',
    input: 'text',
    inputAttributes: {
        autocapitalize: 'off'
    },
    showCancelButton: true,
    confirmButtonText: 'Gửi',
    showLoaderOnConfirm: true,
    preConfirm: (data) => {
        return fetch(`//api.example.com/data?q=${data}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(response.statusText)
                }
                return response.json()
            })
            .catch(error => {
                Swal.showValidationMessage(
                    `Request failed: ${error}`
                )
            })
    },
    allowOutsideClick: () => !Swal.isLoading()
}).then((result) => {
    if (result.isConfirmed) {
        Swal.fire({
            title: 'Kết quả',
            html: JSON.stringify(result.value)
        })
    }
})
```

---

## 💡 EXAMPLES THỰC TẾ

### Example 1: Delete Confirmation

```javascript
function deleteItem(id) {
    Swal.fire({
        title: 'Xóa item này?',
        text: "Bạn sẽ không thể khôi phục lại!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Có, xóa đi!',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            // Call API to delete
            $.ajax({
                url: `/api/items/${id}`,
                type: 'DELETE',
                success: function(response) {
                    Swal.fire(
                        'Đã xóa!',
                        'Item đã được xóa thành công.',
                        'success'
                    ).then(() => {
                        // Reload page or remove element
                        location.reload()
                    })
                },
                error: function(xhr) {
                    Swal.fire(
                        'Lỗi!',
                        'Không thể xóa item.',
                        'error'
                    )
                }
            })
        }
    })
}
```

### Example 2: Form Validation

```javascript
function submitForm() {
    Swal.fire({
        title: 'Thông tin liên hệ',
        html:
            '<input id="name" class="swal2-input" placeholder="Tên">' +
            '<input id="email" class="swal2-input" placeholder="Email">' +
            '<textarea id="message" class="swal2-textarea" placeholder="Tin nhắn"></textarea>',
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Gửi',
        cancelButtonText: 'Hủy',
        preConfirm: () => {
            const name = document.getElementById('name').value
            const email = document.getElementById('email').value
            const message = document.getElementById('message').value
            
            if (!name) {
                Swal.showValidationMessage('Vui lòng nhập tên')
                return false
            }
            if (!email) {
                Swal.showValidationMessage('Vui lòng nhập email')
                return false
            }
            if (!/^\S+@\S+\.\S+$/.test(email)) {
                Swal.showValidationMessage('Email không hợp lệ')
                return false
            }
            if (!message) {
                Swal.showValidationMessage('Vui lòng nhập tin nhắn')
                return false
            }
            
            return {name, email, message}
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit to server
            $.post('/api/contact', result.value, function(response) {
                Swal.fire('Thành công!', 'Tin nhắn đã được gửi.', 'success')
            })
        }
    })
}
```

### Example 3: Upload with Progress

```javascript
function uploadFile() {
    Swal.fire({
        title: 'Chọn file để upload',
        input: 'file',
        inputAttributes: {
            'accept': 'image/*',
            'aria-label': 'Upload ảnh'
        },
        showCancelButton: true,
        confirmButtonText: 'Upload',
        showLoaderOnConfirm: true,
        preConfirm: (file) => {
            if (!file) {
                Swal.showValidationMessage('Vui lòng chọn file')
                return false
            }
            
            const formData = new FormData()
            formData.append('file', file)
            
            return $.ajax({
                url: '/api/upload',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    const xhr = new window.XMLHttpRequest()
                    xhr.upload.addEventListener('progress', function(evt) {
                        if (evt.lengthComputable) {
                            const percentComplete = (evt.loaded / evt.total) * 100
                            Swal.update({
                                html: `Đang upload: ${percentComplete.toFixed(2)}%`
                            })
                        }
                    }, false)
                    return xhr
                }
            })
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Upload thành công!',
                text: 'File đã được upload.'
            })
        }
    })
}
```

### Example 4: Login Form

```javascript
function showLoginForm() {
    Swal.fire({
        title: 'Đăng nhập',
        html:
            '<input id="username" class="swal2-input" placeholder="Username" autocomplete="username">' +
            '<input id="password" class="swal2-input" type="password" placeholder="Password" autocomplete="current-password">',
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Đăng nhập',
        cancelButtonText: 'Hủy',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const username = document.getElementById('username').value
            const password = document.getElementById('password').value
            
            if (!username || !password) {
                Swal.showValidationMessage('Vui lòng nhập đầy đủ thông tin')
                return false
            }
            
            return $.post('/api/login', {username, password})
                .then(response => {
                    if (!response.success) {
                        throw new Error(response.message)
                    }
                    return response
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Login failed: ${error.responseJSON?.message || error.message}`
                    )
                })
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Đăng nhập thành công!',
                text: `Xin chào ${result.value.user.name}`
            }).then(() => {
                window.location.href = '/dashboard'
            })
        }
    })
}
```

### Example 5: Auto-save Draft

```javascript
let autoSaveTimer
let draftData = {}

function enableAutoSave() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
    })
    
    // Listen to form changes
    $('form').on('input', function() {
        clearTimeout(autoSaveTimer)
        
        autoSaveTimer = setTimeout(() => {
            // Save draft
            draftData = $(this).serializeArray()
            
            $.post('/api/save-draft', draftData, function(response) {
                Toast.fire({
                    icon: 'success',
                    title: 'Draft saved'
                })
            }).fail(function() {
                Toast.fire({
                    icon: 'error',
                    title: 'Failed to save draft'
                })
            })
        }, 2000) // Save after 2s of inactivity
    })
}
```

---

## 🎨 CUSTOMIZATION

### Custom CSS

```html
<style>
/* Custom popup background */
.swal2-popup {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

/* Custom confirm button */
.swal2-confirm {
    background-color: #28a745 !important;
    border-radius: 20px;
}

/* Custom cancel button */
.swal2-cancel {
    background-color: #dc3545 !important;
    border-radius: 20px;
}

/* Custom title */
.swal2-title {
    font-family: 'Arial', sans-serif;
    font-size: 2em;
}

/* Custom loading spinner */
.swal2-loader {
    border-color: #fff transparent #fff transparent;
}
</style>
```

### Custom Class

```javascript
Swal.fire({
    title: 'Custom styled alert',
    customClass: {
        popup: 'my-popup-class',
        header: 'my-header-class',
        title: 'my-title-class',
        closeButton: 'my-close-button-class',
        icon: 'my-icon-class',
        image: 'my-image-class',
        content: 'my-content-class',
        htmlContainer: 'my-html-container-class',
        input: 'my-input-class',
        inputLabel: 'my-input-label-class',
        validationMessage: 'my-validation-message-class',
        actions: 'my-actions-class',
        confirmButton: 'my-confirm-button-class',
        denyButton: 'my-deny-button-class',
        cancelButton: 'my-cancel-button-class',
        loader: 'my-loader-class',
        footer: 'my-footer-class',
        timerProgressBar: 'my-timer-progress-bar-class'
    }
})
```

### Dark Theme

```javascript
const darkTheme = Swal.mixin({
    background: '#1e1e1e',
    color: '#fff',
    confirmButtonColor: '#0d6efd',
    cancelButtonColor: '#6c757d'
})

darkTheme.fire({
    title: 'Dark Theme',
    text: 'This is a dark themed alert'
})
```

### Custom Animation

```javascript
Swal.fire({
    title: 'Custom animation',
    showClass: {
        popup: 'animate__animated animate__fadeInDown'
    },
    hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
    }
})
```

---

## 🐛 TROUBLESHOOTING

### 1. Swal is not defined

**Lỗi:**
```
Uncaught ReferenceError: Swal is not defined
```

**Nguyên nhân:** SweetAlert2 chưa được load

**Fix:**
```html
<!-- Đảm bảo load SweetAlert2 TRƯỚC khi dùng -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Sau đó mới dùng Swal
    Swal.fire('Hello!')
</script>
```

### 2. Popup không hiển thị

**Nguyên nhân:** CSS chưa được load

**Fix:**
```html
<head>
    <!-- Thêm CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
```

### 3. Button không click được

**Nguyên nhân:** z-index conflict

**Fix:**
```css
.swal2-container {
    z-index: 99999 !important;
}
```

### 4. Input không focus được

**Nguyên nhân:** autofocus conflict

**Fix:**
```javascript
Swal.fire({
    input: 'text',
    didOpen: () => {
        Swal.getInput().focus()
    }
})
```

### 5. Promise not resolving

**Nguyên nhân:** Thiếu return trong preConfirm

**Fix:**
```javascript
Swal.fire({
    preConfirm: () => {
        // ✅ ĐÚNG - Return promise
        return fetch('/api/data')
            .then(response => response.json())
        
        // ❌ SAI - Không return
        fetch('/api/data')
    }
})
```

### 6. Multiple popups overlapping

**Fix:**
```javascript
// Close tất cả popups trước khi mở mới
Swal.close()
Swal.fire('New popup')

// Hoặc check xem có popup đang mở không
if (!Swal.isVisible()) {
    Swal.fire('New popup')
}
```

### 7. Bootstrap modal conflict

**Fix:**
```javascript
// Tăng z-index của Swal
const MySwal = Swal.mixin({
    customClass: {
        container: 'my-swal-container'
    }
})

// CSS
.my-swal-container {
    z-index: 10000 !important;
}
```

---

## ✅ BEST PRACTICES

### 1. Tạo Swal mixin cho project

```javascript
// Tạo file swal-config.js
const MySwal = Swal.mixin({
    confirmButtonText: 'OK',
    cancelButtonText: 'Hủy',
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    reverseButtons: true
})

// Sử dụng
MySwal.fire({
    title: 'Hello',
    text: 'This uses project defaults'
})
```

### 2. Wrapper functions

```javascript
// Success alert
function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Thành công!',
        text: message,
        timer: 2000,
        timerProgressBar: true
    })
}

// Error alert
function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Lỗi!',
        text: message
    })
}

// Confirm dialog
function confirm(message, callback) {
    Swal.fire({
        title: 'Xác nhận',
        text: message,
        icon: 'question',
        showCancelButton: true
    }).then((result) => {
        if (result.isConfirmed && callback) {
            callback()
        }
    })
}

// Usage
showSuccess('Đã lưu thành công!')
showError('Có lỗi xảy ra!')
confirm('Bạn có chắc?', () => {
    console.log('Confirmed!')
})
```

### 3. Toast helper

```javascript
// Toast configuration
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
})

// Toast functions
const toast = {
    success: (message) => Toast.fire({icon: 'success', title: message}),
    error: (message) => Toast.fire({icon: 'error', title: message}),
    warning: (message) => Toast.fire({icon: 'warning', title: message}),
    info: (message) => Toast.fire({icon: 'info', title: message})
}

// Usage
toast.success('Saved!')
toast.error('Failed!')
```

### 4. Async/await pattern

```javascript
async function deleteRecord(id) {
    const result = await Swal.fire({
        title: 'Xóa record này?',
        icon: 'warning',
        showCancelButton: true
    })
    
    if (result.isConfirmed) {
        try {
            const response = await fetch(`/api/records/${id}`, {
                method: 'DELETE'
            })
            
            if (response.ok) {
                await Swal.fire('Đã xóa!', '', 'success')
                // Refresh data
            }
        } catch (error) {
            Swal.fire('Lỗi!', error.message, 'error')
        }
    }
}
```

### 5. Global error handler

```javascript
window.addEventListener('unhandledrejection', function(event) {
    Swal.fire({
        icon: 'error',
        title: 'Lỗi không mong muốn',
        text: event.reason.message || 'Có lỗi xảy ra',
        footer: 'Vui lòng thử lại hoặc liên hệ support'
    })
})
```

### 6. Loading state management

```javascript
class LoadingManager {
    static show(message = 'Đang xử lý...') {
        Swal.fire({
            title: message,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading()
            }
        })
    }
    
    static hide() {
        Swal.close()
    }
    
    static async withLoading(promise, message) {
        this.show(message)
        try {
            const result = await promise
            this.hide()
            return result
        } catch (error) {
            this.hide()
            throw error
        }
    }
}

// Usage
await LoadingManager.withLoading(
    fetch('/api/data').then(r => r.json()),
    'Đang tải dữ liệu...'
)
```

### 7. Form validation helper

```javascript
class SwalForm {
    static async getInput(options) {
        const result = await Swal.fire({
            input: options.type || 'text',
            inputLabel: options.label,
            inputPlaceholder: options.placeholder,
            inputValidator: (value) => {
                if (!value && options.required) {
                    return 'Trường này là bắt buộc!'
                }
                if (options.validator) {
                    return options.validator(value)
                }
            },
            showCancelButton: true
        })
        
        return result.isConfirmed ? result.value : null
    }
}

// Usage
const email = await SwalForm.getInput({
    label: 'Email của bạn',
    placeholder: 'example@example.com',
    required: true,
    validator: (value) => {
        if (!/^\S+@\S+\.\S+$/.test(value)) {
            return 'Email không hợp lệ!'
        }
    }
})
```

---

## 📚 TÀI LIỆU THAM KHẢO

### Official Resources

- [Official Website](https://sweetalert2.github.io/)
- [GitHub Repository](https://github.com/sweetalert2/sweetalert2)
- [NPM Package](https://www.npmjs.com/package/sweetalert2)
- [API Documentation](https://sweetalert2.github.io/#configuration)

### Examples & Demos

- [Official Examples](https://sweetalert2.github.io/#examples)
- [CodePen Collection](https://codepen.io/collection/XRgyVN)

### Community

- [Stack Overflow](https://stackoverflow.com/questions/tagged/sweetalert2)
- [GitHub Issues](https://github.com/sweetalert2/sweetalert2/issues)
- [GitHub Discussions](https://github.com/sweetalert2/sweetalert2/discussions)

---

## 🎯 QUICK REFERENCE

### Cú pháp nhanh:

```javascript
// Simple
Swal.fire('Title')

// With text
Swal.fire('Title', 'Text', 'icon')

// Full options
Swal.fire({
    title: 'Title',
    text: 'Text',
    icon: 'success|error|warning|info|question',
    showCancelButton: true,
    confirmButtonText: 'OK',
    cancelButtonText: 'Cancel'
}).then((result) => {
    if (result.isConfirmed) { }
    if (result.isDismissed) { }
})
```

### Icons:

- `'success'` - ✓ Xanh lá
- `'error'` - ✗ Đỏ
- `'warning'` - ! Vàng
- `'info'` - i Xanh dương
- `'question'` - ? Xám

### Input types:

`'text'`, `'email'`, `'password'`, `'number'`, `'tel'`, `'range'`, `'textarea'`, `'select'`, `'radio'`, `'checkbox'`, `'file'`, `'url'`

### Positions (Toast):

`'top'`, `'top-start'`, `'top-end'`, `'center'`, `'center-start'`, `'center-end'`, `'bottom'`, `'bottom-start'`, `'bottom-end'`

---

**Version:** SweetAlert2 v11
**Last Updated:** 2025
**License:** MIT

🎉 Happy coding with SweetAlert2!