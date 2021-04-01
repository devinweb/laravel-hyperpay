<!DOCTYPE html>
<html lang="ar" class="h-full" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ __('Payment Confirmation') }} - {{ config('app.name', 'Laravel') }}</title>

    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://github.com/Kamonlojn/svg-icons-animate/blob/master/svg-icons-animate.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <script>
        
</script>

<style>
    body {
        font-family: 'Tajawal', sans-serif;
     }
     * {
    margin: 0;
    padding: 0;
}

.svg-box {
    display:inline-block;
    position: relative;
    width:150px;
}

.green-stroke {
    stroke:#7CB342;
}

.red-stroke {
    stroke: #FF6245;
}

.yellow-stroke {
    stroke: #FFC107;
}


.circular circle.path {
    stroke-dasharray: 330;
    stroke-dashoffset: 0;
    stroke-linecap: round;
    opacity: 0.4;
    animation: 0.7s draw-circle ease-out;
}

/*------- Checkmark ---------*/
.checkmark{
	stroke-width: 6.25;
    stroke-linecap: round;
	position:absolute;
    /* top: 56px; */
    left: 49px;
    width: 52px;
    height: 40px;
}

.checkmark path {
    animation: 1s draw-check ease-out;
}

@keyframes draw-circle {
    0% {
        stroke-dasharray: 0,330;
        stroke-dashoffset: 0;
        opacity: 1;
    }

    80% {
        stroke-dasharray: 330,330;
        stroke-dashoffset: 0;
        opacity: 1;
    }

    100%{
        opacity: 0.4;
    }
}

@keyframes draw-check {
    0% {
        stroke-dasharray: 49,80;
        stroke-dashoffset: 48;
        opacity: 0;
    }

    50% {
        stroke-dasharray: 49,80;
        stroke-dashoffset: 48;
        opacity: 1;
    }

    100% {
        stroke-dasharray: 130,80;
        stroke-dashoffset: 48;
    }
}

/*---------- Cross ----------*/

.cross {
    stroke-width:6.25;
    stroke-linecap: round;
    position: absolute;
    /* left: 49px; */
    width: 52px;
    height: 40px;
}

.cross .first-line {
    animation: 0.7s draw-first-line ease-out;
}

.cross .second-line {
    animation: 0.7s draw-second-line ease-out;
}

@keyframes draw-first-line {
    0% {
        stroke-dasharray: 0,56;
        stroke-dashoffset: 0;
    }

    50% {
        stroke-dasharray: 0,56;
        stroke-dashoffset: 0;
    }

    100% {
        stroke-dasharray: 56,330;
        stroke-dashoffset: 0;
    }
}

@keyframes draw-second-line {
    0% {
        stroke-dasharray: 0,55;
        stroke-dashoffset: 1;
    }

    50% {
        stroke-dasharray: 0,55;
        stroke-dashoffset: 1;
    }

    100% {
        stroke-dasharray: 55,0;
        stroke-dashoffset: 70;
    }
}

.alert-sign {
    stroke-width:6.25;
    stroke-linecap: round;
    position: absolute;
    top: 40px;
    left: 68px;
    width: 15px;
    height: 70px;
    animation: 0.5s alert-sign-bounce cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.alert-sign .dot {
    stroke:none;
    fill: #FFC107;
}

@keyframes alert-sign-bounce {
    0% {
        transform: scale(0);
        opacity: 0;
    }

    50% {
        transform: scale(0);
        opacity: 1;
    }

    100% {
        transform: scale(1);
    }
}
    .green-stroke {
        stroke:#10B981;
    }

    .red-stroke {
        stroke: #EF4444;
    }

    .yellow-stroke {
        stroke: #F59E0B;
    }
</style>

</head>
<body class="text-gray-800  leading-normal p-8 h-full">
    <div id="app" class="h-full  w-1/2 flex container mx-auto md:flex">
        <div class="flex-1 flex flex-col p-4 relative">
            <div class="absolute flex items-center bg-white justify-center inset-0 z-10" v-if="paymentStatusLoading">
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

            <div class="w-full flex items-center justify-center" v-else>
                <div class="w-full flex items-center justify-center flex-col" v-if="hasSuccess">
                    <div class="w-40 h-40 flex items-center justify-center relative" >
                        <svg class="circular green-stroke flex items-center justify-center">
                            <circle class="path" cx="75" cy="75" r="50" fill="none" stroke-width="5" stroke-miterlimit="10"/>
                        </svg>
                        <svg class="checkmark green-stroke">
                            <g transform="matrix(0.79961,8.65821e-32,8.39584e-32,0.79961,-489.57,-205.679)">
                                <path class="checkmark__check" fill="none" d="M616.306,283.025L634.087,300.805L673.361,261.53"/>
                            </g>
                        </svg>
                    </div>
                    <div class="mt-8 flex items-center flex-col">
                        <span class="text-gray-500 text-lg font-normal mb-4">فريق التسيير و التدبير</span>
                        <div class="flex items-start justify-start">
                            <span class="text-gray-900 text-4xl font-medium">$18.00</span>
                            <div class="flex flex-col mr-1">
                                <span class="text-gray-500 leading-none">في</span>
                                <span class="text-gray-500 leading-none">الشهر</span>
                            </div>
                        </div>

                        <div class="flex-1 flex mt-8">
                            <a href="{{ url('/hyperpay/checkout') }}" class="text-blue-500">العودة للصفحة الرئيسية</a>
                        </div>
                    </div>
                </div>
                <div class="w-full flex items-center justify-center flex-col " v-if="hasError">
                    <div class="w-40 h-40 flex items-center justify-center relative" >
                        <svg class="circular red-stroke flex items-center justify-center">
                            <circle class="path" cx="75" cy="75" r="50" fill="none" stroke-width="5" stroke-miterlimit="10"/>
                        </svg>
                        <svg class="cross red-stroke flex items-center justify-center">
                            <g transform="matrix(0.79961,8.65821e-32,8.39584e-32,0.79961,-502.652,-204.518)">
                                <path class="first-line" d="M634.087,300.805L673.361,261.53" fill="none"/>
                            </g>
                            <g transform="matrix(-1.28587e-16,-0.79961,0.79961,-1.28587e-16,-204.752,543.031)">
                                <path class="second-line" d="M634.087,300.805L673.361,261.53"/>
                            </g>
                        </svg>
                    </div>
                    <div class="mt-8 flex items-center flex-col">
                        <span class="text-gray-500 text-lg font-normal mb-4">@{{ errorMessage }}</span>
                        <!-- <div class="flex items-start justify-start">
                            <span class="text-gray-900 text-4xl font-medium">$18.00</span>
                            <div class="flex flex-col mr-1">
                                <span class="text-gray-500 leading-none">في</span>
                                <span class="text-gray-500 leading-none">الشهر</span>
                            </div>
                        </div> -->

                        <div class="flex-1 flex mt-8">
                            <a href="{{ url('/hyperpay/checkout') }}" class="text-blue-500">العودة للصفحة الرئيسية</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var app = new Vue({
            el: '#app',

            data: {
                paymentStatusLoading: true,
                error: {},
                success: {}
            },
            computed: {
                hasError () {
                    return _.size(this.error)
                },
                hasSuccess () {
                    return _.size(this.success)
                },
                errorMessage () {
                    if (this.hasError) {
                        return this.error.message
                    }
                }
            },
            methods: {
                paymentStatus() {
                    console.log('brand ----->', '{{ $id }}')
                    this.paymentStatusLoading = true;
                    axios.post('/hyperpay/payment-status', {
                        id: '{{ $id }}',
                        resourcePath: '{{ $resourcePath}}'
                    }, {
                        headers: {
                                'ek-current-fqdn': 'main.ekliel.net'
                            }
                    }).then(({data}) => {
                        this.paymentStatusLoading = false;
                        this.success = data
                    }).catch((error) => {
                        this.paymentStatusLoading = false;
                        this.error = error.response.data
                    })
                },
               
            },
            mounted() {
                this.paymentStatus()
            }
        })
    </script>
</body>
