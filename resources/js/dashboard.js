import {
    Chart,
    registerables
} from "chart.js";
import $ from 'jquery';
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
        const myChart = new Chart(
            document.getElementById('grafico'),
            config
        );

        response.ticketsArray.forEach(element => {
            max = max > element ? max : element;
        });
        let i = 0;
        let lineaArray = [];
        while (moment().month() >= i - 1) {
            lineaArray[i - 1] = response.ticketsArray[i];
            i++;
        }
        response.ticketsResueltosArray.forEach(element => {
            max = max > element ? max : element;
        });
        max = max + 3;
        i = 0;
        let lineaResueltosArray = [];
        while (moment().month() >= i - 1) {
            lineaResueltosArray[i - 1] = response.ticketsResueltosArray[i];
            i++;
        }
        const data2 = {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            datasets: [{
                label: 'Tickets Resueltos',
                backgroundColor: 'rgb(54, 252, 35)',
                borderColor: 'rgb(54, 252, 35)',
                data: lineaResueltosArray
            }, {
                label: 'Tickets Creados',
                backgroundColor: 'rgb(99, 132, 255)',
                borderColor: 'rgb(99, 132, 255)',
                data: lineaArray
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

        setInterval(() => {
            $.ajax({
                type: "get",
                url: "/estadisticas",
                success: function (response) {

                    response.ticketsArray.forEach(element => {
                        max = max > element ? max : element;
                    });
                    let i = 0;
                    let lineaArray = [];
                    while (moment().month() >= i - 1) {
                        lineaArray[i - 1] = response.ticketsArray[i];
                        i++;
                    }
                    response.ticketsResueltosArray.forEach(element => {
                        max = max > element ? max : element;
                    });
                    max = max + 3;
                    i = 0;
                    let lineaResueltosArray = [];
                    while (moment().month() >= i - 1) {
                        lineaResueltosArray[i - 1] = response.ticketsResueltosArray[i];
                        i++;
                    }
                    myChart2.data.datasets[0].data = lineaArray;
                    myChart2.data.datasets[0].data = lineaResueltosArray;
                    myChart2.update();
                }
            });
        }, 5000);
    }
})
