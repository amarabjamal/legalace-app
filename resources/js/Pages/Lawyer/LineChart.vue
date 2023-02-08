<template>
    <Line
      id="line-chart-id"
      :options="chartOptions"
      :data="chartData"
    />
  </template>
  
  <script>
  import { Line } from 'vue-chartjs'
  import { Chart as ChartJS, Title, Tooltip, Legend, PointElement, LineElement, CategoryScale, LinearScale, Colors } from 'chart.js'
  
  ChartJS.register(Title, Tooltip, Legend, CategoryScale, LinearScale, Colors, PointElement, LineElement)
  
  export default {
    name: 'LineChart',
    components: { Line },
    data() {
      return {
        chartData: {
          labels: [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September'],
          datasets: [ { 
            label: 'Cost',
            data: [10, 56, 32, 47, 55, 20, 12, 33, 11] } ]
        },
        chartOptions: {
          responsive: true,
          scales: {
            y: {
                suggestedMin: 50,
                suggestedMax: 100,
                // grid: {
                //   color: 'white'
                // }
                // ticks: {
                //   color: 'black',
                // }
            },
            
          },
          plugins: {
            colors:{
              enabled: true,
            },
            title: {
                  display: true,
                  text: 'Operational cost',
                  font: {
                        size: 18,
                  },
                  color: '#000',
                  padding: {
                    top: 10,
                    bottom: 10
                  }
                },
            legend: {
                labels: {
                    color: '#000',  
                    font: {
                        size: 18,
                    }
                }
            },
          },
        },
      }
    }
  }
  </script>