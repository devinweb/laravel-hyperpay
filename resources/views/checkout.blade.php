<!DOCTYPE html>
<html lang="ar" class="h-full" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ __('Payment Confirmation') }} - {{ config('app.name', 'Laravel') }}</title>

    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700&display=swap" rel="stylesheet">
    <script>
        
</script>

<style>
     body {
        font-family: 'Tajawal', sans-serif;
     }
     .container {
         max-width: 920px;
     }
    
    /* start hyPerPay Form styles */

    .min-h-payment-form {
        min-height: 412px
    }

    .wpwl-group{
        text-align: right;
        margin-bottom: 15px;
        font-family: 'Tajawal', sans-serif;
    }
    
    /* hide brand options */
    .wpwl-wrapper.wpwl-wrapper-brand{
        display: none;
    }
    /* input and options style */
    .wpwl-control {
        text-align: right;
        border-radius: 6px;
        border: 0;
        box-shadow: 0 0 0 1px #e0e0e0, 0 2px 4px 0 rgb(0 0 0 / 7%), 0 1px 1.5px 0 rgb(0 0 0 / 5%);
    }

    /* card background style */
    .wpwl-form-card {
        background-image: none;
        background-color: white;
        box-shadow: none;
        border: 0;
    }

    /* style the first column in the card brand */
    .wpwl-group.wpwl-group-brand {
        margin-bottom: 32px;

    }

    /* style pay button */
    .wpwl-button {
        margin-top: 12px;
        width: 100%;
        background-color: #2563EB;
        border-color: #3B82F6

    }
    .wpwl-button:hover,.wpwl-button-pay:hover, .wpwl-button-pay:focus, .wpwl-button-pay:active, .wpwl-button-pay[disabled], .wpwl-button-pay[disabled]:hover, .wpwl-button-pay[disabled]:focus, .wpwl-button-pay[disabled]:active
    {
        background-color: #1D4ED8;
        border-color: #1D4ED8;
    }

    .wpwl-button.wpwl-button-pay.wpwl-button-error {
        background-color: #EF4444;
        border-color: #EF4444;
    }

    /* style error messages */
    .wpwl-hint {
        font-size: 12px;
        color: #EF4444
    }

    /* end hyPerPay Form styles */

</style>

</head>
<body class="text-gray-800  leading-normal p-8 h-full">
    <div id="app" class="h-full flex container mx-auto md:flex">
        <div class="flex-1 flex flex-col p-4">
            <div class="items-start flex text-gray-600">
                <div class="flex cursor-pointer h-8 group items-center justify-start">
                    <div class="h-6 w-6 group-hover:text-gray-800 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="16" height="16" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-sm mr-2 group-hover:text-gray-800 mt-1">
                        العودة
                    </span>
                </div>
            </div>
            <div class="mt-8">
                <span class="text-gray-500 text-lg font-normal">الإشتراك في فريق التسيير و التدبير</span>
                <div class="flex items-start justify-start">
                    <span class="text-gray-900 text-4xl font-medium">$18.00</span>
                    <div class="flex flex-col mr-1">
                        <span class="text-gray-500 leading-none">في</span>
                        <span class="text-gray-500 leading-none">الشهر</span>
                    </div>
                </div>
            </div>

            <section class="mt-6">
                <div class="flex flex-start">
                    <div class="w-10 h-10 bg-gray-100 rounded-xl ml-2 border border-1 border-gray-200 overflow-hidden">
                        <img src="https://stripe-camo.global.ssl.fastly.net/78f71532054ad1bbbe84a7bbff50e02cb538b9d7/68747470733a2f2f66696c65732e7374726970652e636f6d2f6c696e6b732f666c5f746573745f634c744c316b506c6b6e6d6d39307273634b4f3834593962">
                    </div>
                    <div class="flex-1 flex-col flex">
                        <div class="flex-1 flex">
                            <div class="flex flex-col flex-1">
                                <span class="text-gray-700 text-sm">فريق التسيير و التدبير</span>
                                <span class="text-gray-500 text-sm">تدفع شهريا</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-900">$18.00</span>
                            </div>
                        </div>
                        <div class="flex-1 flex mt-6">
                            <div class="flex flex-1 justify-between">
                                <span class="text-gray-900">مبلغ إجمالي</span>
                                 <div class="flex flex-col">
                                    <span class="text-gray-900">$18.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="h-px my-4 bg-gray-100 w-full"></div>
                        <div class="flex-1 flex">
                            <span class="text-blue-500">إضافة كود الخصم</span>
                        </div>
                        <div class="h-px my-4 bg-gray-100 w-full"></div>
                        <div class="flex-1 flex">
                            <div class="flex flex-1 justify-between">
                                <span class="text-gray-900">المجموع الكلي</span>
                                 <div class="flex flex-col">
                                    <span class="text-gray-900">$18.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
        </div>
        <div class="flex-1 border-0 border-r border-1">
            <div class="w-full flex items-center justify-center  mt-4 mb-8">
                <h2 class="text-gray-900 text-2xl font-semibold">طرف الدفع المتاحة</h2>
            </div>
            <div class="w-full max-w-lg flex justify-around">
                <div class="cursor-pointer p-2 items-center justify-center relative">
                    <div class="absolute check-svg-position top-0 right-0 z-10 bg-white rounded-full" v-if="brand=='VISA_MASTER'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-green-500" height="24" width="24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM9.29 16.29L5.7 12.7a.996.996 0 111.41-1.41L10 14.17l6.88-6.88a.996.996 0 111.41 1.41l-7.59 7.59a.996.996 0 01-1.41 0z" /></svg>
                    </div>
                    <div class="cursor-pointer relative w-32 h-20 p-2 bg-white border-2 border-solid border-gray-200 rounded-lg flex items-center justify-center relative" :class="{'border-green-500': brand=='VISA_MASTER'}" @click="setPaymentGateway('VISA_MASTER')">
                        <svg class="inline-block w-24 text-white shadow-solid" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 504 504"><path d="M504 252c0 83.2-67.2 151.2-151.2 151.2-83.2 0-151.2-68-151.2-151.2 0-83.2 67.2-151.2 150.4-151.2 84.8 0 152 68 152 151.2z" fill="#ffb600"/><path d="M352.8 100.8c83.2 0 151.2 68 151.2 151.2 0 83.2-67.2 151.2-151.2 151.2-83.2 0-151.2-68-151.2-151.2" fill="#f7981d"/><path d="M352.8 100.8c83.2 0 151.2 68 151.2 151.2 0 83.2-67.2 151.2-151.2 151.2" fill="#ff8500"/><path d="M149.6 100.8C67.2 101.6 0 168.8 0 252s67.2 151.2 151.2 151.2c39.2 0 74.4-15.2 101.6-39.2 5.6-4.8 10.4-10.4 15.2-16h-31.2c-4-4.8-8-10.4-11.2-15.2h53.6c3.2-4.8 6.4-10.4 8.8-16h-71.2c-2.4-4.8-4.8-10.4-6.4-16h83.2c4.8-15.2 8-31.2 8-48 0-11.2-1.6-21.6-3.2-32h-92.8c.8-5.6 2.4-10.4 4-16h83.2c-1.6-5.6-4-11.2-6.4-16H216c2.4-5.6 5.6-10.4 8.8-16h53.6c-3.2-5.6-7.2-11.2-12-16h-29.6c4.8-5.6 9.6-10.4 15.2-15.2-26.4-24.8-62.4-39.2-101.6-39.2 0-1.6 0-1.6-.8-1.6z" fill="#ff5050"/><path d="M0 252c0 83.2 67.2 151.2 151.2 151.2 39.2 0 74.4-15.2 101.6-39.2 5.6-4.8 10.4-10.4 15.2-16h-31.2c-4-4.8-8-10.4-11.2-15.2h53.6c3.2-4.8 6.4-10.4 8.8-16h-71.2c-2.4-4.8-4.8-10.4-6.4-16h83.2c4.8-15.2 8-31.2 8-48 0-11.2-1.6-21.6-3.2-32h-92.8c.8-5.6 2.4-10.4 4-16h83.2c-1.6-5.6-4-11.2-6.4-16H216c2.4-5.6 5.6-10.4 8.8-16h53.6c-3.2-5.6-7.2-11.2-12-16h-29.6c4.8-5.6 9.6-10.4 15.2-15.2-26.4-24.8-62.4-39.2-101.6-39.2h-.8" fill="#e52836"/><path d="M151.2 403.2c39.2 0 74.4-15.2 101.6-39.2 5.6-4.8 10.4-10.4 15.2-16h-31.2c-4-4.8-8-10.4-11.2-15.2h53.6c3.2-4.8 6.4-10.4 8.8-16h-71.2c-2.4-4.8-4.8-10.4-6.4-16h83.2c4.8-15.2 8-31.2 8-48 0-11.2-1.6-21.6-3.2-32h-92.8c.8-5.6 2.4-10.4 4-16h83.2c-1.6-5.6-4-11.2-6.4-16H216c2.4-5.6 5.6-10.4 8.8-16h53.6c-3.2-5.6-7.2-11.2-12-16h-29.6c4.8-5.6 9.6-10.4 15.2-15.2-26.4-24.8-62.4-39.2-101.6-39.2h-.8" fill="#cb2026"/><g fill="#fff"><path d="M204.8 290.4l2.4-13.6c-.8 0-2.4.8-4 .8-5.6 0-6.4-3.2-5.6-4.8l4.8-28h8.8l2.4-15.2h-8l1.6-9.6h-16s-9.6 52.8-9.6 59.2c0 9.6 5.6 13.6 12.8 13.6 4.8 0 8.8-1.6 10.4-2.4zM210.4 264.8c0 22.4 15.2 28 28 28 12 0 16.8-2.4 16.8-2.4l3.2-15.2s-8.8 4-16.8 4c-17.6 0-14.4-12.8-14.4-12.8H260s2.4-10.4 2.4-14.4c0-10.4-5.6-23.2-23.2-23.2-16.8-1.6-28.8 16-28.8 36zm28-23.2c8.8 0 7.2 10.4 7.2 11.2H228c0-.8 1.6-11.2 10.4-11.2zM340 290.4l3.2-17.6s-8 4-13.6 4c-11.2 0-16-8.8-16-18.4 0-19.2 9.6-29.6 20.8-29.6 8 0 14.4 4.8 14.4 4.8l2.4-16.8s-9.6-4-18.4-4c-18.4 0-36.8 16-36.8 46.4 0 20 9.6 33.6 28.8 33.6 6.4 0 15.2-2.4 15.2-2.4zM116.8 227.2c-11.2 0-19.2 3.2-19.2 3.2L95.2 244s7.2-3.2 17.6-3.2c5.6 0 10.4.8 10.4 5.6 0 3.2-.8 4-.8 4h-7.2c-13.6 0-28.8 5.6-28.8 24 0 14.4 9.6 17.6 15.2 17.6 11.2 0 16-7.2 16.8-7.2l-.8 6.4H132l6.4-44c0-19.2-16-20-21.6-20zm3.2 36c0 2.4-1.6 15.2-11.2 15.2-4.8 0-6.4-4-6.4-6.4 0-4 2.4-9.6 14.4-9.6 2.4.8 3.2.8 3.2.8zM153.6 292c4 0 24 .8 24-20.8 0-20-19.2-16-19.2-24 0-4 3.2-5.6 8.8-5.6 2.4 0 11.2.8 11.2.8l2.4-14.4s-5.6-1.6-15.2-1.6c-12 0-24 4.8-24 20.8 0 18.4 20 16.8 20 24 0 4.8-5.6 5.6-9.6 5.6-7.2 0-14.4-2.4-14.4-2.4l-2.4 14.4c.8 1.6 4.8 3.2 18.4 3.2zM472.8 214.4l-3.2 21.6s-6.4-8-15.2-8c-14.4 0-27.2 17.6-27.2 38.4 0 12.8 6.4 26.4 20 26.4 9.6 0 15.2-6.4 15.2-6.4l-.8 5.6h16l12-76.8-16.8-.8zm-7.2 42.4c0 8.8-4 20-12.8 20-5.6 0-8.8-4.8-8.8-12.8 0-12.8 5.6-20.8 12.8-20.8 5.6 0 8.8 4 8.8 13.6zM29.6 291.2l9.6-57.6 1.6 57.6H52l20.8-57.6-8.8 57.6h16.8l12.8-76.8H67.2l-16 47.2-.8-47.2H27.2l-12.8 76.8h15.2zM277.6 291.2c4.8-26.4 5.6-48 16.8-44 1.6-10.4 4-14.4 5.6-18.4h-3.2c-7.2 0-12.8 9.6-12.8 9.6l1.6-8.8h-15.2L260 292h17.6v-.8zM376.8 227.2c-11.2 0-19.2 3.2-19.2 3.2l-2.4 13.6s7.2-3.2 17.6-3.2c5.6 0 10.4.8 10.4 5.6 0 3.2-.8 4-.8 4h-7.2c-13.6 0-28.8 5.6-28.8 24 0 14.4 9.6 17.6 15.2 17.6 11.2 0 16-7.2 16.8-7.2l-.8 6.4H392l6.4-44c.8-19.2-16-20-21.6-20zm4 36c0 2.4-1.6 15.2-11.2 15.2-4.8 0-6.4-4-6.4-6.4 0-4 2.4-9.6 14.4-9.6 2.4.8 2.4.8 3.2.8zM412 291.2c4.8-26.4 5.6-48 16.8-44 1.6-10.4 4-14.4 5.6-18.4h-3.2c-7.2 0-12.8 9.6-12.8 9.6l1.6-8.8h-15.2L394.4 292H412v-.8z"/></g><g fill="#dce5e5"><path d="M180 279.2c0 9.6 5.6 13.6 12.8 13.6 5.6 0 10.4-1.6 12-2.4l2.4-13.6c-.8 0-2.4.8-4 .8-5.6 0-6.4-3.2-5.6-4.8l4.8-28h8.8l2.4-15.2h-8l1.6-9.6M218.4 264.8c0 22.4 7.2 28 20 28 12 0 16.8-2.4 16.8-2.4l3.2-15.2s-8.8 4-16.8 4c-17.6 0-14.4-12.8-14.4-12.8H260s2.4-10.4 2.4-14.4c0-10.4-5.6-23.2-23.2-23.2-16.8-1.6-20.8 16-20.8 36zm20-23.2c8.8 0 10.4 10.4 10.4 11.2H228c0-.8 1.6-11.2 10.4-11.2zM340 290.4l3.2-17.6s-8 4-13.6 4c-11.2 0-16-8.8-16-18.4 0-19.2 9.6-29.6 20.8-29.6 8 0 14.4 4.8 14.4 4.8l2.4-16.8s-9.6-4-18.4-4c-18.4 0-28.8 16-28.8 46.4 0 20 1.6 33.6 20.8 33.6 6.4 0 15.2-2.4 15.2-2.4zM95.2 244.8s7.2-3.2 17.6-3.2c5.6 0 10.4.8 10.4 5.6 0 3.2-.8 4-.8 4h-7.2c-13.6 0-28.8 5.6-28.8 24 0 14.4 9.6 17.6 15.2 17.6 11.2 0 16-7.2 16.8-7.2l-.8 6.4H132l6.4-44c0-18.4-16-19.2-22.4-19.2m12 34.4c0 2.4-9.6 15.2-19.2 15.2-4.8 0-6.4-4-6.4-6.4 0-4 2.4-9.6 14.4-9.6 2.4.8 11.2.8 11.2.8zM136 290.4s4.8 1.6 18.4 1.6c4 0 24 .8 24-20.8 0-20-19.2-16-19.2-24 0-4 3.2-5.6 8.8-5.6 2.4 0 11.2.8 11.2.8l2.4-14.4s-5.6-1.6-15.2-1.6c-12 0-16 4.8-16 20.8 0 18.4 12 16.8 12 24 0 4.8-5.6 5.6-9.6 5.6M469.6 236s-6.4-8-15.2-8c-14.4 0-19.2 17.6-19.2 38.4 0 12.8-1.6 26.4 12 26.4 9.6 0 15.2-6.4 15.2-6.4l-.8 5.6h16l12-76.8m-20.8 41.6c0 8.8-7.2 20-16 20-5.6 0-8.8-4.8-8.8-12.8 0-12.8 5.6-20.8 12.8-20.8 5.6 0 12 4 12 13.6zM29.6 291.2l9.6-57.6 1.6 57.6H52l20.8-57.6-8.8 57.6h16.8l12.8-76.8h-20l-22.4 47.2-.8-47.2h-8.8l-27.2 76.8h15.2zM260.8 291.2h16.8c4.8-26.4 5.6-48 16.8-44 1.6-10.4 4-14.4 5.6-18.4h-3.2c-7.2 0-12.8 9.6-12.8 9.6l1.6-8.8M355.2 244.8s7.2-3.2 17.6-3.2c5.6 0 10.4.8 10.4 5.6 0 3.2-.8 4-.8 4h-7.2c-13.6 0-28.8 5.6-28.8 24 0 14.4 9.6 17.6 15.2 17.6 11.2 0 16-7.2 16.8-7.2l-.8 6.4H392l6.4-44c0-18.4-16-19.2-22.4-19.2m12 34.4c0 2.4-9.6 15.2-19.2 15.2-4.8 0-6.4-4-6.4-6.4 0-4 2.4-9.6 14.4-9.6 3.2.8 11.2.8 11.2.8zM395.2 291.2H412c4.8-26.4 5.6-48 16.8-44 1.6-10.4 4-14.4 5.6-18.4h-3.2c-7.2 0-12.8 9.6-12.8 9.6l1.6-8.8"/></g></svg>
                        <div class="w-4"></div>
                        <svg class="inline-block w-24 text-white shadow-solid" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 504 504"><path fill="#3c58bf" d="M184.8 324.4l25.6-144h40l-24.8 144z"/><path fill="#293688" d="M184.8 324.4l32.8-144h32.8l-24.8 144z"/><path d="M370.4 182c-8-3.2-20.8-6.4-36.8-6.4-40 0-68.8 20-68.8 48.8 0 21.6 20 32.8 36 40s20.8 12 20.8 18.4c0 9.6-12.8 14.4-24 14.4-16 0-24.8-2.4-38.4-8l-5.6-2.4-5.6 32.8c9.6 4 27.2 8 45.6 8 42.4 0 70.4-20 70.4-50.4 0-16.8-10.4-29.6-34.4-40-14.4-7.2-23.2-11.2-23.2-18.4 0-6.4 7.2-12.8 23.2-12.8 13.6 0 23.2 2.4 30.4 5.6l4 1.6 6.4-31.2z" fill="#3c58bf"/><path d="M370.4 182c-8-3.2-20.8-6.4-36.8-6.4-40 0-61.6 20-61.6 48.8 0 21.6 12.8 32.8 28.8 40s20.8 12 20.8 18.4c0 9.6-12.8 14.4-24 14.4-16 0-24.8-2.4-38.4-8l-5.6-2.4-5.6 32.8c9.6 4 27.2 8 45.6 8 42.4 0 70.4-20 70.4-50.4 0-16.8-10.4-29.6-34.4-40-14.4-7.2-23.2-11.2-23.2-18.4 0-6.4 7.2-12.8 23.2-12.8 13.6 0 23.2 2.4 30.4 5.6l4 1.6 6.4-31.2z" fill="#293688"/><path d="M439.2 180.4c-9.6 0-16.8.8-20.8 10.4l-60 133.6h43.2l8-24h51.2l4.8 24H504l-33.6-144h-31.2zm-18.4 96c2.4-7.2 16-42.4 16-42.4s3.2-8.8 5.6-14.4l2.4 13.6s8 36 9.6 44h-33.6v-.8z" fill="#3c58bf"/><path d="M448.8 180.4c-9.6 0-16.8.8-20.8 10.4l-69.6 133.6h43.2l8-24h51.2l4.8 24H504l-33.6-144h-21.6zm-28 96c3.2-8 16-42.4 16-42.4s3.2-8.8 5.6-14.4l2.4 13.6s8 36 9.6 44h-33.6v-.8z" fill="#293688"/><path d="M111.2 281.2l-4-20.8c-7.2-24-30.4-50.4-56-63.2l36 128h43.2l64.8-144H152l-40.8 100z" fill="#3c58bf"/><path d="M111.2 281.2l-4-20.8c-7.2-24-30.4-50.4-56-63.2l36 128h43.2l64.8-144H160l-48.8 100z" fill="#293688"/><path d="M0 180.4l7.2 1.6c51.2 12 86.4 42.4 100 78.4l-14.4-68c-2.4-9.6-9.6-12-18.4-12H0z" fill="#ffbc00"/><path d="M0 180.4c51.2 12 93.6 43.2 107.2 79.2l-13.6-56.8c-2.4-9.6-10.4-15.2-19.2-15.2L0 180.4z" fill="#f7981d"/><path d="M0 180.4c51.2 12 93.6 43.2 107.2 79.2l-9.6-31.2c-2.4-9.6-5.6-19.2-16.8-23.2L0 180.4z" fill="#ed7c00"/><g fill="#051244"><path d="M151.2 276.4L124 249.2l-12.8 30.4-3.2-20c-7.2-24-30.4-50.4-56-63.2l36 128h43.2l20-48zM225.6 324.4l-34.4-35.2-6.4 35.2zM317.6 274.8c3.2 3.2 4.8 5.6 4 8.8 0 9.6-12.8 14.4-24 14.4-16 0-24.8-2.4-38.4-8l-5.6-2.4-5.6 32.8c9.6 4 27.2 8 45.6 8 25.6 0 46.4-7.2 58.4-20l-34.4-33.6zM364 324.4h37.6l8-24h51.2l4.8 24H504L490.4 266l-48-46.4 2.4 12.8s8 36 9.6 44h-33.6c3.2-8 16-42.4 16-42.4s3.2-8.8 5.6-14.4"/></g></svg>
                    </div>
                </div>

                <div class="cursor-pointer p-2 flex items-center justify-center relative" >
                    <div class="absolute check-svg-position top-0 right-0 z-10 bg-white rounded-full" v-if="brand=='MADA'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-green-500" height="24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM9.29 16.29L5.7 12.7a.996.996 0 111.41-1.41L10 14.17l6.88-6.88a.996.996 0 111.41 1.41l-7.59 7.59a.996.996 0 01-1.41 0z" /></svg>
                    </div>
                    <div class="cursor-pointer relative w-32 h-20 p-2 bg-white border-2 border-solid border-gray-200 rounded-lg flex items-center justify-center relative" :class="{'border-green-500': brand=='MADA'}" @click="setPaymentGateway('MADA')">
                        <svg class="inline-block w-24 text-white shadow-solid" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#84b740" d="M31 267.501h190.354v63.414H31z"/><path fill="#259bd6" d="M31 180.972h190.354v63.47H31z"/><path d="M411.708 318.029l-.848.17c-2.939.565-4.013.791-6.161.791-4.974 0-10.852-2.543-10.852-14.525 0-6.161 1.017-14.356 10.286-14.356h.057c1.582.113 3.391.283 6.782 1.3l.735.226.001 26.394zm1.526-59.74l-1.526.283v22.155l-1.356-.396-.396-.113c-1.526-.452-5.03-1.469-8.421-1.469-18.538 0-22.438 14.017-22.438 25.772 0 16.108 9.043 25.377 24.812 25.377 6.669 0 11.586-.678 16.56-2.317 4.578-1.469 6.217-3.561 6.217-8.026v-63.583a960.053 960.053 0 01-13.452 2.317M466.814 318.425l-.791.226-2.826.735c-2.656.678-5.03 1.074-6.839 1.074-4.352 0-6.952-2.148-6.952-5.821 0-2.374 1.074-6.387 8.195-6.387h9.212v10.173zm-6.5-39.959c-5.708 0-11.586 1.017-18.877 3.278l-4.748 1.413 1.583 10.738 4.635-1.526c4.861-1.583 10.908-2.6 15.43-2.6 2.035 0 8.252 0 8.252 6.726v2.939h-8.647c-15.769 0-23.06 5.03-23.06 15.825 0 9.212 6.726 14.751 18.029 14.751 3.504 0 8.365-.678 12.547-1.696l.226-.057.226.057 1.413.226c4.408.791 8.986 1.583 13.451 2.43v-35.268c0-11.415-6.895-17.236-20.46-17.236M356.998 318.425l-.791.226-2.826.735c-2.656.678-4.974 1.074-6.839 1.074-4.352 0-6.952-2.148-6.952-5.821 0-2.374 1.074-6.387 8.139-6.387h9.212v10.173h.057zm-6.443-39.959c-5.765 0-11.586 1.017-18.877 3.278l-4.748 1.413 1.582 10.738 4.635-1.526c4.861-1.583 10.908-2.6 15.43-2.6 2.035 0 8.252 0 8.252 6.726v2.939h-8.647c-15.769 0-23.116 5.03-23.116 15.825 0 9.212 6.726 14.751 18.086 14.751 3.504 0 8.365-.678 12.547-1.696l.226-.057.226.057 1.356.226c4.465.791 8.986 1.583 13.451 2.487v-35.268c.057-11.528-6.838-17.293-20.403-17.293M297.485 278.579c-7.178 0-13.112 2.374-15.316 3.391l-.565.283-.509-.396c-3.052-2.204-7.517-3.335-13.734-3.335-5.482 0-10.625.791-16.221 2.43-4.804 1.469-6.669 3.787-6.669 8.139v40.298h15.034v-37.246l.735-.226c3.052-1.017 4.861-1.187 6.613-1.187 4.352 0 6.556 2.317 6.556 6.839v31.876h14.808v-32.498c0-1.922-.396-3.052-.452-3.278l-.509-.961 1.017-.452c2.261-1.017 4.748-1.526 7.347-1.526 2.995 0 6.556 1.187 6.556 6.839v31.876h14.751V296.1c0-11.813-6.33-17.521-19.442-17.521M455.736 222.343c-2.204 0-5.878-.226-8.76-.791l-.848-.17v-21.76c0-1.809-.339-2.939-.396-3.109l-.452-.904.961-.396c.226-.113.452-.17.735-.283l.17-.113 1.017-.339c.17-.057.283-.113.396-.113 3.335-.904 6.387-.791 7.743-.904h.057c9.212 0 10.286 8.195 10.286 14.356-.057 11.983-5.992 14.526-10.909 14.526m-.056-41.371h-.396c-8.647 0-17.521 2.374-20.686 7.008-1.696 2.261-2.656 5.087-2.713 8.421v22.438c0 1.922-.396 2.656-.452 2.826l-.509.961h-27.298v-15.599h-.057c-.339-16.447-10.06-25.49-24.133-25.49h-13.734c-.565 4.013-1.017 6.839-1.582 10.852h13.677c7.178 0 10.965 6.104 10.965 15.486v15.712l-.961-.509c-.17-.057-1.356-.452-3.222-.452h-23.625c-.452 2.995-1.017 6.895-1.639 10.795h72.626c2.487-.509 5.369-.961 7.856-1.356 3.674 1.809 10.512 2.769 15.203 2.769 15.769 0 25.999-10.569 25.999-26.846-.056-16.108-9.946-26.677-25.319-27.016M328.344 240.034h.678c15.769 0 23.116-5.2 23.116-18.029 0-9.213-6.726-16.56-18.029-16.56h-14.525c-4.352 0-6.952-2.487-6.952-6.669 0-2.826 1.074-6.33 8.195-6.33h31.763c.678-4.126 1.017-6.726 1.639-10.852h-33.007c-15.373 0-23.116 6.443-23.116 17.182 0 10.625 6.726 16.164 18.029 16.164h14.525c4.352 0 6.952 3.448 6.952 7.065 0 2.374-1.074 7.291-8.139 7.291h-2.43l-46.515-.113h-8.478c-7.178 0-12.208-4.069-12.208-13.508v-6.5c0-9.834 3.9-15.938 12.208-15.938h13.79c.622-4.182 1.017-6.839 1.583-10.795H268.602c-14.073 0-23.794 9.439-24.133 25.885v7.348c.339 16.447 10.06 24.303 24.133 24.303h13.734l25.207.057h15.034l5.767-.001z" fill="#27292d"/></svg>
                    </div>
                </div>
            </div>
            <div class="m-4 flex mt-10 min-h-payment-form relative" dir="ltr" id="hyperpay-form">
                <div class="absolute flex items-center bg-white justify-center inset-0 z-10" v-if="hyPayPayFormLoading">
                    <svg width="60" height="15" viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="#E5E7EB">
                        <circle cx="15" cy="15" r="15">
                            <animate attributeName="r" from="15" to="15"
                                    begin="0s" dur="0.8s"
                                    values="15;9;15" calcMode="linear"
                                    repeatCount="indefinite" />
                            <animate attributeName="fill-opacity" from="1" to="1"
                                    begin="0s" dur="0.8s"
                                    values="1;.5;1" calcMode="linear"
                                    repeatCount="indefinite" />
                        </circle>
                        <circle cx="60" cy="15" r="9" fill-opacity="0.3">
                            <animate attributeName="r" from="9" to="9"
                                    begin="0s" dur="0.8s"
                                    values="9;15;9" calcMode="linear"
                                    repeatCount="indefinite" />
                            <animate attributeName="fill-opacity" from="0.5" to="0.5"
                                    begin="0s" dur="0.8s"
                                    values=".5;1;.5" calcMode="linear"
                                    repeatCount="indefinite" />
                        </circle>
                        <circle cx="105" cy="15" r="15">
                            <animate attributeName="r" from="15" to="15"
                                    begin="0s" dur="0.8s"
                                    values="15;9;15" calcMode="linear"
                                    repeatCount="indefinite" />
                            <animate attributeName="fill-opacity" from="1" to="1"
                                    begin="0s" dur="0.8s"
                                    values="1;.5;1" calcMode="linear"
                                    repeatCount="indefinite" />
                        </circle>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <script>
        var wpwlOptions = null;
        var app = new Vue({
            el: '#app',

            data: {
                showCheckoutForm: false,
                redirect_url: null,
                amount: 100,
                paymentType: 'VISA MASTER',
                brands: [],
                brand: 'VISA_MASTER',
                hyPayPayFormLoading: true,
                
            },
            methods: {
                getForm (redirect_url, paymentType) {
                    return `<form
                    id="paymentWidgets"
                    class="paymentWidgets"
                    action="${redirect_url}"
                    data-brands="${paymentType}"
                ></form>`
                },
                setPaymentGateway (brand) {
                    this.brand = brand
                },
                getFormToken() {
                    axios.post('/hyperpay/payment', {
                            brand:(this.brand == 'VISA_MASTER') ? 'VISA' : this.brand
                        },
                        {
                        headers: {
                                'ek-current-fqdn': 'main.ekliel.net'
                            }
                    }).then(({data}) => {
                        this.setBrands();
                        this.removeFormAndScript();
                        const paymentWrapper = document.createElement( "div" );
                        paymentWrapper.setAttribute("id", "parent_paymentWidgets");
                        paymentWrapper.setAttribute("class", "w-full")
                        paymentWrapper.innerHTML = this.getForm(data.shopperResultUrl, this.paymentType);
                        var hyperpay_form = document.getElementById('hyperpay-form');
                        hyperpay_form.append(paymentWrapper);
                        this.attachScriptInTheHtmlHead(data)
                    }).catch((error) => {
                        console.log('error ---->', error)
                    })
                },
                attachScriptInTheHtmlHead({script_url}) {
                    this.script_url = script_url
                    this.showCheckoutForm = true
                    let scriptTag = document.createElement('script')
                    scriptTag.setAttribute('src', script_url)
                    scriptTag.setAttribute('id', 'hyperpay_script')
                    document.head.appendChild(scriptTag)
                },
                setBrands () {
                    if (this.brand == 'VISA_MASTER') {
                        this.paymentType = 'VISA MASTER'
                    }
                    
                    if (this.brand == 'MADA') {
                        this.paymentType = 'MADA'
                    }
                },
                removeFormAndScript () {
                   var  paymentWidgets = document.getElementById('parent_paymentWidgets');
                   var  paymentScript = document.getElementById('hyperpay_script')
                   if(paymentWidgets) {
                       paymentWidgets.remove();
                   }

                   if (paymentScript) {
                       paymentScript.remove();
                   }
                }
            },
            watch: {
                brand: function(val, oldVal) {
                    console.log('val --->',val, this.brand)
                    if(this.brand !== oldVal) {
                        console.log('val --->',oldVal)
                        this.hyPayPayFormLoading = true
                        this.getFormToken()
                    }
                }
            },
            mounted() {
                this.getFormToken()
                
                 wpwlOptions = {
                    locale: "ar",
                    style: "card",
                    brandDetection: true,
                    // showCVVHint: true,
                    brandDetectionType: "regex",
                    
                    brandDetectionPriority: ["MADA", "VISA", "MASTER"],

                    imageStyle: "svg",
                    spinner: {
                        color: 'transparent'
                    },
                    threeDIframeSize:{'width':'100%', 'height':'400px'},
                    onReady: function() {
                        ready = true;
                        setTimeout(() => {
                            this.hyPayPayFormLoading = false
                        }, 1000)
                    }.bind(this),
                    onChangeBrand: function(val) {
                        
                    }.bind(this),
                    onDetectBrand: function(brands){
                       
                    }.bind(this),
                    iframeStyles: {
                        'card-number-placeholder': {
                            'color': '#9CA3AF',
                            'font-size': '14px',
                            'font-family': 'Tajawal'
                        },
                        'cvv-placeholder': {
                            'color': '#9CA3AF',
                            'font-size': '14px',
                            'font-family': 'Tajawal'
                        }
                    }
                }
            }
        })
    </script>
</body>
