<!-- <template>
    <Bar
      id="my-chart-id"
      :options="chartOptions"
      :data="chartData"
    />
  </template>
  
  <script>
  import { Bar } from 'vue-chartjs'
  import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
  
  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
  
  export default {
    name: 'BarChart',
    components: { Bar },
    props: {
      eachCost: Object,
    },
    data() {
      return {
        chartData: {
          labels: [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September'],
          datasets: [ { 
            label: 'Account Balance',
            backgroundColor: '#f87979',
            color: '#000',
            borderColor: '#ffffff',
            data: [40, 20, 12, 12, 55, 20, 12, 33, 71] } ]
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
            title: {
                  display: true,
                  text: 'Firm Account',
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
    },
    mounted(){

      console.log(this.eachCost)
    }
  }
  </script> -->