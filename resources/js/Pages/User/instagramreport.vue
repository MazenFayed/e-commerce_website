<script setup >
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';
import { ref } from 'vue'
import PieChart from '@/Components/piechart.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import donutchart from '@/Components/Donutchart.vue'
import XYchart from '@/Components/XYchart.vue'
import $ from "jquery";
import { jsPDF } from "jspdf";
import html2canvas from "html2canvas";

const x_axis = ref([]);
const likes = ref([]);
const comments = ref([]);
const caption = ref([]);
const header1_def = "{Likes - Comments - Caption word count} Chart"
const header1 = "Media Stats Chart"
var likes_avg = 0;
var comments_avg = 0;

const instagramreport = computed(() => usePage().props.report.data.Pagedata.Pagedata);
const data = JSON.parse(instagramreport.value);
const username = ref();
function submit() {
    router.visit(route('instagram.data'), {
        method: 'get',
        preserveState: true,
        replace: true,
        data: {
            username: username
        }
    })
}
function printpdf() {
    window.print();
}
/*
function dopdf() {
    // Get HTML to print from element
    const prtHtml = document.getElementById('app').innerHTML;

    // Get all stylesheets HTML
    let stylesHtml = '';
    for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
        stylesHtml += node.outerHTML;
    }

    // Open the print window
    const WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');

    WinPrint.document.write(`<!DOCTYPE html>
<html>
  <head>
    ${stylesHtml}
  </head>
  <body>
    ${prtHtml}
  </body>
</html>`);

    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}
*/
for (let index = 0; index < data.business_discovery.media.data.length; index++) {
    x_axis.value.push("" + data.business_discovery.media.data[index].timestamp.substr(0, 10));
    likes.value.push(data.business_discovery.media.data[index].like_count);
    likes_avg = likes_avg + data.business_discovery.media.data[index].like_count;
    comments.value.push(data.business_discovery.media.data[index].comments_count);
    comments_avg = comments_avg + data.business_discovery.media.data[index].comments_count;
    caption.value.push(("" + data.business_discovery.media.data[index].caption).length);
}
likes_avg = likes_avg / data.business_discovery.media.data.length;
comments_avg = comments_avg / data.business_discovery.media.data.length;
</script>
<template>
    <div class=" flex flex-col min-w-0">
        <div class="  w-full bg-slate-400 ">
            <h5
                class="mt-3 mx-60 mb-3 underline underline-offset-8 leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2">
                <span class="flex items-center justify-start "><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                    </svg>
                    Report For Instagram Page : {{ data.business_discovery.name }}
                </span>
            </h5>
        </div>
        <div class=" flex items-center justify-center">
            <button @click="printpdf"
                class=" m-5 w-32  text-center  text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5  me-2 ">
                Print As Pdf</button>
        </div>
        <div class="mx-60 mt-10 mb-3">
            <h5 class=" underline underline-offset-8 leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2">
                <span class="flex items-center justify-start "> <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    Account Statistics Summary:-</span>
            </h5>
        </div>
        <div class="flex items-center justify-center   overflow-x-auto ">
            <table class=" mx-60 max-w-xlg  w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">followers</th>
                        <th scope="col" class="px-4 py-3">following</th>
                        <th scope="col" class="px-4 py-3">Uplooads</th>
                        <th scope="col" class="px-4 py-3">Connectivity</th>
                        <th scope="col" class="px-4 py-3">likes_avg</th>
                        <th scope="col" class="px-4 py-3">comments_avg</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b bg-gray-50 dark:border-gray-700">
                        <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                            data.business_discovery.followers_count }}</th>
                        <td class="px-4 py-3">{{ data.business_discovery.follows_count }}</td>
                        <td class="px-4 py-3">{{ data.business_discovery.media_count }}</td>
                        <td class="px-4 py-3">{{ }}</td>
                        <td class="px-4 py-3">{{ Math.round(likes_avg) }}</td>
                        <td class="px-4 py-3">{{ Math.round(comments_avg) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mx-60 mt-10 mb-3">
            <h5 class=" underline underline-offset-8 leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2">
                <span class="flex items-center justify-start "> <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    Media Stats Summary:-</span>
            </h5>
            <p class="text-base font-normal text-gray-500 dark:text-gray-400">Showing last 10 entries.</p>
        </div>
        <div class="flex items-center justify-center   overflow-x-auto ">
            <table class=" mx-60 max-w-xlg  w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 w-40 text-center">posted in</th>
                        <th scope="col" class="px-4 py-3">post Link</th>
                        <th scope="col" class="px-4 py-3">Media Link</th>
                        <th scope="col" class="px-4 py-3 w-80 text-center">caption </th>
                        <th scope="col" class="px-4 py-3 w-40 ">Likes</th>
                        <th scope="col" class="px-4 py-3 w-40">comments</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in data.business_discovery.media.data "
                        class="border-b bg-gray-50 dark:border-gray-700">
                        <th scope="row"
                            class=" w-20 px-4 py-3 font-medium  text-gray-900 whitespace-nowrap dark:text-white">
                            {{ item.timestamp.substr(0, 10) }}</th>
                        <td class="px-4 py-3 "><a :href="item.permalink"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                </svg></a></td>
                        <td class="px-4 py-3 "><a :href="item.media_url"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg></a></td>
                        <td class="px-4 py-3 w-80"><span v-if="item.caption">{{ item.caption }}</span><span
                                class="text-red-600" v-else>No Caption Detected</span></td>
                        <td class="px-4 py-3 w-40   "><span class="flex items-center justify-start"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 47.5 47.5" width="20" id="heart"
                                    class=" pr-1">
                                    <defs>
                                        <clipPath id="a">
                                            <path d="M0 38h38V0H0v38Z"></path>
                                        </clipPath>
                                    </defs>
                                    <g clip-path="url(#a)" transform="matrix(1.25 0 0 -1.25 0 47.5)">
                                        <path fill="#be1931"
                                            d="M36.885 25.166c0 5.45-4.418 9.868-9.867 9.868-3.308 0-6.227-1.633-8.018-4.129-1.79 2.496-4.71 4.129-8.017 4.129-5.45 0-9.868-4.418-9.868-9.868 0-.772.098-1.52.266-2.241C2.752 14.413 12.216 5.431 19 2.965c6.783 2.466 16.249 11.448 17.617 19.96.17.721.268 1.469.268 2.241">
                                        </path>
                                    </g>
                                </svg>{{ item.like_count }} <span class=" text-green-400"
                                    v-if="(item.like_count - likes_avg) / likes_avg > 0">+{{ parseInt(((item.like_count -
                                        likes_avg) / likes_avg) * 100) }}%</span><span class=" text-red-600" v-else>{{
        parseInt(((item.like_count - likes_avg) / likes_avg) * 100) }}
                                    %</span></span></td>
                        <td class="px-4 py-3 w-40  "><span class="flex items-center justify-start"><svg
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6 pr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                                </svg>
                                {{ item.comments_count }}<span class=" text-green-400"
                                    v-if="(item.comments_count - comments_avg) / comments_avg > 0">+{{
                                        parseInt(((item.comments_count - comments_avg) / comments_avg) * 100) }}%</span><span
                                    class=" text-red-600" v-else>{{
                                        parseInt(((item.comments_count - comments_avg) / comments_avg) * 100) }} %</span></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-center   overflow-x-auto ">
            <XYchart class=" mx-60 max-w-xlg  w-full my-10" :x_axis="x_axis" :first="likes" :second="comments"
                :third="caption" :header="header1" :header_def="header1_def">

            </XYchart>

        </div>
    </div>
</template>