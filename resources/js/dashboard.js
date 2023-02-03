import {
    Chart,
    registerables
} from "chart.js";
import $ from 'jquery';
import { capitalize } from "lodash";
var moment = require('moment')

Chart.register(...registerables);
moment.locale('es')

let max = 0;

$.ajax({
    type: "get",
    url: "/estadisticas",
    success: function (response) {
        const data = {
            labels: [
                'Sin Asignar',
                'Asignados',
                'Atendidos',
                'Resueltos'
            ],
            datasets: [{
                label: 'My First Dataset',
                data: response.dataPie,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(254, 162, 135)',
                    'rgb(54, 202, 35)',
                    'rgb(54, 252, 35)'
                ],
                hoverOffset: 4
            }]
        };
        const config = {
            type: 'pie',
            data: data,
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Estado de Tickets'
                    }
                }
            }
        };
        const ctx = document.getElementById('grafico');
        const myChart = new Chart(
            ctx,
            config
        );
        function pieLink(click) {
            const clickedInfo = myChart.getElementsAtEventForMode(click, 'nearest', { intersect: true }, true);
            if (clickedInfo.length) {
                const clickSeg = clickedInfo[0];
                console.log(clickSeg.index);
                if (clickSeg.index == 0) {
                    window.location.href = "tickets?cliente_id=&estado=creado&fecha="
                } else if (clickSeg.index == 1) {
                    window.location.href = "tickets?cliente_id=&estado=asignado&fecha="
                }
                else if (clickSeg.index == 2) {
                    window.location.href = "tickets?cliente_id=&estado=atendido&fecha="
                }
                else if (clickSeg.index == 3) {
                    window.location.href = "tickets?cliente_id=&estado=resueltos&fecha="
                }
                /* const link = myChart.data.datasets[clickSeg.datasetIndex].data[0];
                            console.log(link); */
                /* window.open(link);  */
            }
        }
        ctx.onclick = pieLink;
        response.ticketsArray.forEach(element => {
            max = max > element ? max : element;
        });
        let etiquetas = [];
        let i = 0;
        response.meses.forEach(mes => {
            etiquetas[i] = capitalize(moment().set("M", mes - 1).format("MMMM"));
            i++;
        });
        response.ticketsResueltosArray.forEach(element => {
            max = max > element ? max : element;
        });
        max = max + 3;
        const data2 = {
            labels: etiquetas,
            datasets: [{
                label: 'Tickets Resueltos',
                backgroundColor: 'rgb(54, 252, 35)',
                borderColor: 'rgb(54, 252, 35)',
                data: response.ticketsResueltosArray
            }, {
                label: 'Tickets Creados',
                backgroundColor: 'rgb(99, 132, 255)',
                borderColor: 'rgb(99, 132, 255)',
                data: response.ticketsArray
            }]
        };
        const config2 = {
            type: 'line',
            data: data2,
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Creación de Tickets por Mes'
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: max,
                        ticks: {
                            precision: 0
                        },
                    }
                }
            }
        };
        const myChart2 = new Chart(
            document.getElementById('grafico2'),
            config2
        );
        /*    $('#grafico2').click(function (e) {
               e.preventDefault();
               window.location.href = "/tickets";
           });
    */
        setInterval(() => {
            $.ajax({
                type: "get",
                url: "/estadisticas",
                success: function (response) {

                    response.ticketsArray.forEach(element => {
                        max = max > element ? max : element;
                    });
                    // let i = 0;
                    // let lineaArray = [];
                    // while (moment().month() >= i - 1) {
                    //     lineaArray[i - 1] = response.ticketsArray[i];
                    //     i++;
                    // }
                    response.ticketsResueltosArray.forEach(element => {
                        max = max > element ? max : element;
                    });
                    max = max + 3;
                    // i = 0;
                    // let lineaResueltosArray = [];
                    // while (moment().month() >= i - 1) {
                    //     lineaResueltosArray[i - 1] = response.ticketsResueltosArray[i];
                    //     i++;
                    // }
                    myChart2.data.datasets[0].data = response.ticketsArray;
                    myChart2.data.datasets[0].data = response.ticketsResueltosArray;
                    myChart2.update();
                }
            });
        }, 5000);
    }
})
