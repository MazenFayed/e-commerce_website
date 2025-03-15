<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';
import { ref } from 'vue'
import PieChart from '@/Components/piechart.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import donutchart from '@/Components/Donutchart.vue'
import XYchart from '@/Components/XYchart.vue'

const username = ref();
function submit() {
    router.visit(route('Pageusername'), {
        method: 'get',
        preserveState: true,
        replace: true,
        data: {
            username: username
        }
    })
}
</script>
<template>
    <div class=" divide-y divide-solid">
        <div class=" flex flex-col mt-10 mb-10">
            <div class="flex items-center justify-center mt   overflow-x-auto">
                <svg xmlns="http://www.w3.org/2000/svg" width="102" height="102" viewBox="0 0 102 102" class="h-12"
                    id="instagram">
                    <defs>
                        <radialGradient id="a" cx="6.601" cy="99.766" r="129.502" gradientUnits="userSpaceOnUse">
                            <stop offset=".09" stop-color="#fa8f21"></stop>
                            <stop offset=".78" stop-color="#d82d7e"></stop>
                        </radialGradient>
                        <radialGradient id="b" cx="70.652" cy="96.49" r="113.963" gradientUnits="userSpaceOnUse">
                            <stop offset=".64" stop-color="#8c3aaa" stop-opacity="0"></stop>
                            <stop offset="1" stop-color="#8c3aaa"></stop>
                        </radialGradient>
                    </defs>
                    <path fill="url(#a)"
                        d="M25.865,101.639A34.341,34.341,0,0,1,14.312,99.5a19.329,19.329,0,0,1-7.154-4.653A19.181,19.181,0,0,1,2.5,87.694,34.341,34.341,0,0,1,.364,76.142C.061,69.584,0,67.617,0,51s.067-18.577.361-25.14A34.534,34.534,0,0,1,2.5,14.312,19.4,19.4,0,0,1,7.154,7.154,19.206,19.206,0,0,1,14.309,2.5,34.341,34.341,0,0,1,25.862.361C32.422.061,34.392,0,51,0s18.577.067,25.14.361A34.534,34.534,0,0,1,87.691,2.5a19.254,19.254,0,0,1,7.154,4.653A19.267,19.267,0,0,1,99.5,14.309a34.341,34.341,0,0,1,2.14,11.553c.3,6.563.361,8.528.361,25.14s-.061,18.577-.361,25.14A34.5,34.5,0,0,1,99.5,87.694,20.6,20.6,0,0,1,87.691,99.5a34.342,34.342,0,0,1-11.553,2.14c-6.557.3-8.528.361-25.14.361s-18.577-.058-25.134-.361"
                        data-name="Path 16"></path>
                    <path fill="url(#b)"
                        d="M25.865,101.639A34.341,34.341,0,0,1,14.312,99.5a19.329,19.329,0,0,1-7.154-4.653A19.181,19.181,0,0,1,2.5,87.694,34.341,34.341,0,0,1,.364,76.142C.061,69.584,0,67.617,0,51s.067-18.577.361-25.14A34.534,34.534,0,0,1,2.5,14.312,19.4,19.4,0,0,1,7.154,7.154,19.206,19.206,0,0,1,14.309,2.5,34.341,34.341,0,0,1,25.862.361C32.422.061,34.392,0,51,0s18.577.067,25.14.361A34.534,34.534,0,0,1,87.691,2.5a19.254,19.254,0,0,1,7.154,4.653A19.267,19.267,0,0,1,99.5,14.309a34.341,34.341,0,0,1,2.14,11.553c.3,6.563.361,8.528.361,25.14s-.061,18.577-.361,25.14A34.5,34.5,0,0,1,99.5,87.694,20.6,20.6,0,0,1,87.691,99.5a34.342,34.342,0,0,1-11.553,2.14c-6.557.3-8.528.361-25.14.361s-18.577-.058-25.134-.361"
                        data-name="Path 17"></path>
                    <path fill="#fff"
                        d="M461.114,477.413a12.631,12.631,0,1,1,12.629,12.632,12.631,12.631,0,0,1-12.629-12.632m-6.829,0a19.458,19.458,0,1,0,19.458-19.458,19.457,19.457,0,0,0-19.458,19.458m35.139-20.229a4.547,4.547,0,1,0,4.549-4.545h0a4.549,4.549,0,0,0-4.547,4.545m-30.99,51.074a20.943,20.943,0,0,1-7.037-1.3,12.547,12.547,0,0,1-7.193-7.19,20.923,20.923,0,0,1-1.3-7.037c-.184-3.994-.22-5.194-.22-15.313s.04-11.316.22-15.314a21.082,21.082,0,0,1,1.3-7.037,12.54,12.54,0,0,1,7.193-7.193,20.924,20.924,0,0,1,7.037-1.3c3.994-.184,5.194-.22,15.309-.22s11.316.039,15.314.221a21.082,21.082,0,0,1,7.037,1.3,12.541,12.541,0,0,1,7.193,7.193,20.926,20.926,0,0,1,1.3,7.037c.184,4,.22,5.194.22,15.314s-.037,11.316-.22,15.314a21.023,21.023,0,0,1-1.3,7.037,12.547,12.547,0,0,1-7.193,7.19,20.925,20.925,0,0,1-7.037,1.3c-3.994.184-5.194.22-15.314.22s-11.316-.037-15.309-.22m-.314-68.509a27.786,27.786,0,0,0-9.2,1.76,19.373,19.373,0,0,0-11.083,11.083,27.794,27.794,0,0,0-1.76,9.2c-.187,4.04-.229,5.332-.229,15.623s.043,11.582.229,15.623a27.793,27.793,0,0,0,1.76,9.2,19.374,19.374,0,0,0,11.083,11.083,27.813,27.813,0,0,0,9.2,1.76c4.042.184,5.332.229,15.623.229s11.582-.043,15.623-.229a27.8,27.8,0,0,0,9.2-1.76,19.374,19.374,0,0,0,11.083-11.083,27.716,27.716,0,0,0,1.76-9.2c.184-4.043.226-5.332.226-15.623s-.043-11.582-.226-15.623a27.786,27.786,0,0,0-1.76-9.2,19.379,19.379,0,0,0-11.08-11.083,27.748,27.748,0,0,0-9.2-1.76c-4.041-.185-5.332-.229-15.621-.229s-11.583.043-15.626.229"
                        data-name="Path 18" transform="translate(-422.637 -426.196)"></path>
                </svg>
                <label for="name" class=" ml-5 mt-5 mr-1 leading-7 text-lg text-gray-600">Username</label>
                <input type="text" id="name" name="username" v-model="username"
                    class=" w-40 mr-5 mt-5 ml-0 bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <button @click="submit()"
                    class="text-white bg-blue-600 p-3 m-3  focus:outline-none hover:bg-indigo-300 rounded-full ">
                    Save Username</button>
                <a :href="route('instagram.data')"
                    class="text-white bg-blue-600 p-3 m-3  focus:outline-none hover:bg-indigo-300 rounded-full ">
                    Get Report
                </a>
            </div>
        </div>
        <div class=" mt-10 mb-10 flex flex-col">
            <div class="flex items-center justify-center   overflow-x-auto">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="80" viewBox="0 0 48 48">
                    <linearGradient id="awSgIinfw5_FS5MLHI~A9a_yGcWL8copNNQ_gr1" x1="6.228" x2="42.077" y1="4.896"
                        y2="43.432" gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-color="#0d61a9"></stop>
                        <stop offset="1" stop-color="#16528c"></stop>
                    </linearGradient>
                    <path fill="url(#awSgIinfw5_FS5MLHI~A9a_yGcWL8copNNQ_gr1)"
                        d="M42,40c0,1.105-0.895,2-2,2H8c-1.105,0-2-0.895-2-2V8c0-1.105,0.895-2,2-2h32	c1.105,0,2,0.895,2,2V40z">
                    </path>
                    <path
                        d="M25,38V27h-4v-6h4v-2.138c0-5.042,2.666-7.818,7.505-7.818c1.995,0,3.077,0.14,3.598,0.208	l0.858,0.111L37,12.224L37,17h-3.635C32.237,17,32,18.378,32,19.535V21h4.723l-0.928,6H32v11H25z"
                        opacity=".05"></path>
                    <path
                        d="M25.5,37.5v-11h-4v-5h4v-2.638c0-4.788,2.422-7.318,7.005-7.318c1.971,0,3.03,0.138,3.54,0.204	l0.436,0.057l0.02,0.442V16.5h-3.135c-1.623,0-1.865,1.901-1.865,3.035V21.5h4.64l-0.773,5H31.5v11H25.5z"
                        opacity=".07"></path>
                    <path fill="#fff"
                        d="M33.365,16H36v-3.754c-0.492-0.064-1.531-0.203-3.495-0.203c-4.101,0-6.505,2.08-6.505,6.819V22h-4v4	h4v11h5V26h3.938l0.618-4H31v-2.465C31,17.661,31.612,16,33.365,16z">
                    </path>
                </svg>
                <!--
                <label for="name" class=" ml-5 mt-5 mr-1 leading-7 text-lg text-gray-600">Username</label>
                <input type="text" id="name" name="username"
                    class=" w-40 mr-5 mt-5 ml-0 bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <button @click="submit()"
                    class="text-white bg-blue-600 p-3 m-3  focus:outline-none hover:bg-indigo-300 rounded-full ">
                    Save Username</button>-->
                <a :href="route('facebook.data')"
                    class="text-white bg-blue-600 p-3 m-3  focus:outline-none hover:bg-indigo-300 rounded-full ">
                    Login with facebook
                </a>
            </div>

        </div>
        <div class=" flex flex-col mt-10 mb-10">
            <div class="flex items-center justify-center mt   overflow-x-auto">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0,0,256,256">
                    <defs>
                        <linearGradient x1="10.341" y1="8.312" x2="40.798" y2="38.769" gradientUnits="userSpaceOnUse"
                            id="color-1_5MQ0gPAYYx7a_gr1">
                            <stop offset="0" stop-color="#2aa4f4"></stop>
                            <stop offset="1" stop-color="#007ad9"></stop>
                        </linearGradient>
                    </defs>
                    <g fill="url(#color-1_5MQ0gPAYYx7a_gr1)" fill-rule="nonzero" stroke="none" stroke-width="1"
                        stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray=""
                        stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none"
                        style="mix-blend-mode: normal">
                        <g transform="scale(5.33333,5.33333)">
                            <path
                                d="M46.105,11.02c-1.551,0.687 -3.219,1.145 -4.979,1.362c1.789,-1.062 3.166,-2.756 3.812,-4.758c-1.674,0.981 -3.529,1.702 -5.502,2.082c-1.576,-1.67 -3.824,-2.706 -6.314,-2.706c-4.783,0 -8.661,3.843 -8.661,8.582c0,0.671 0.079,1.324 0.226,1.958c-7.196,-0.361 -13.579,-3.782 -17.849,-8.974c-0.75,1.269 -1.172,2.754 -1.172,4.322c0,2.979 1.525,5.602 3.851,7.147c-1.42,-0.043 -2.756,-0.438 -3.926,-1.072c0,0.026 0,0.064 0,0.101c0,4.163 2.986,7.63 6.944,8.419c-0.723,0.198 -1.488,0.308 -2.276,0.308c-0.559,0 -1.104,-0.063 -1.632,-0.158c1.102,3.402 4.299,5.889 8.087,5.963c-2.964,2.298 -6.697,3.674 -10.756,3.674c-0.701,0 -1.387,-0.04 -2.065,-0.122c3.837,2.429 8.39,3.852 13.278,3.852c15.927,0 24.641,-13.079 24.641,-24.426c0,-0.372 -0.012,-0.742 -0.029,-1.108c1.7,-1.201 3.165,-2.715 4.322,-4.446">
                            </path>
                        </g>
                    </g>
                </svg>
            <!--
                <label for="name" class=" ml-5 mt-5 mr-1 leading-7 text-lg text-gray-600">Username</label>
                <input type="text" id="name" name="username" v-model="username"
                    class=" w-40 mr-5 mt-5 ml-0 bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <button @click="submit()"
                    class="text-white bg-blue-600 p-3 m-3  focus:outline-none hover:bg-indigo-300 rounded-full ">
                    Save Username</button>-->
            <a :href="route('twitter')"
                class="text-white bg-blue-600 p-3 m-3  focus:outline-none hover:bg-indigo-300 rounded-full ">
                Login With Twitter
            </a>
        </div>
    </div>
</div></template>