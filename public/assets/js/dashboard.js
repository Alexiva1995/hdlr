var vm_dashboard = new Vue({
    el: '#dashboard-analytics',
    created: function () {
        this.getDataGraphics()
    },
    data: function () {
        return {
            Colores: {
                primary: '#13192E',
                success: '#28C76F',
                danger: '#EA5455',
                warning: '#FF9F43',
                primary_light: '#A9A2F6',
                success_light: '#55DD92',
                warning_light: '#ffc085',
            },
            DataInfoGraphic: []
        }
    },
    methods: {
        /**
         * Permite obtener los datos para la grafica e inicializarla
         */
        getDataGraphics: function () {
            let url = route('home.data.graphic')
            axios.get(url).then((response) => {
                this.DataInfoGraphic = response.data
                this.graphicInversiones()
                this.graphicPucharse()
            })
        },

        /**
         * Permite Actualizar el lado a registrar un usuario
         * @param {string} side 
         */
        updateBinarySide: function (side) {
            // let url = route('ajax.update.side.binary', side)
            // axios.get(url).then((response) => {
            //     if (response.data == 'bien') {
            getlink(side)
            // }
            // }).catch(function (error) {
            //     toastr.warning("Ocurrio un error al Actualizar el lado binario", '¡Advertencia!', { "progressBar": true });
            // })
        },

        /**
         * Muestra la grafica de comisiones
         */
        graphicPucharse: function () {
            // Column Chart
            // ----------------------------------
            var columnChartOptions = {
                chart: {
                    height: 350,
                    type: 'bar',
                },
                colors: [this.Colores.primary],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        endingShape: 'rounded',
                        columnWidth: '55%',
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                series: [{
                    name: 'Total Compras',
                    data: this.DataInfoGraphic.ordenes.series,
                }],
                legend: {
                    offsetY: -10
                },
                xaxis: {
                    categories: this.DataInfoGraphic.ordenes.categorias,
                },
                yaxis: {
                    title: {
                        text: '$ (total)'
                    }
                },
                fill: {
                    opacity: 1

                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "$ " + val + " Total"
                        }
                    }
                }
            }
            var columnChart = new ApexCharts(
                document.querySelector("#gcomisiones"),
                columnChartOptions
            );

            columnChart.render();
        },

        /**
         * Muestar la grafica de inversiones
         */
        graphicInversiones: function () {
            let colores = []
            for (const element in this.Colores) {
                if (Object.hasOwnProperty.call(this.Colores, element)) {
                    colores.push(this.Colores[element]);
                }
            }
            // Pie Chart
            // -----------------------------
            var pieChartOptions = {
                chart: {
                    type: 'pie',
                    height: 350
                },
                colors: colores,
                labels: this.DataInfoGraphic.inversiones.label,
                series: this.DataInfoGraphic.inversiones.cantidad,
                legend: {
                    itemMargin: {
                        horizontal: 2
                    },
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 350
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            }
            var pieChart = new ApexCharts(
                document.querySelector("#ginversiones"),
                pieChartOptions
            );
            pieChart.render();
        }

    }
})
