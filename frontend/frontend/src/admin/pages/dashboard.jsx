import React from 'react'
import { Line } from 'react-chartjs-2';
import { Chart as ChartJS } from 'chart.js/auto';


const dashboard = () => {
  // Example data for the chart
  const chartData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
    datasets: [
      {
        label: 'Sales',
        data: [65, 59, 80, 81, 56, 55, 40],
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        fill: true,
      },
    ],
  };

  return (
    <div className="dashboard-container">
      <div className="dashboard-header">
        <h3 className="fw-bold">Dashboard</h3>
        <p className="subheading">Overview of key statistics</p>
        <div className="action-buttons">
          <button className="btn btn-primary">Add New</button>
          <button className="btn btn-info">Export</button>
        </div>
      </div>

      <div className="stats-cards">
        <div className="card">
          <div className="card-body">
            <i className="fas fa-users"></i>
            <h4>Visitors</h4>
            <p>1,294</p>
          </div>
        </div>
        <div className="card">
          <div className="card-body">
            <i className="fas fa-user-check"></i>
            <h4>Subscribers</h4>
            <p>1,303</p>
          </div>
        </div>
        <div className="card">
          <div className="card-body">
            <i className="fas fa-luggage-cart"></i>
            <h4>Sales</h4>
            <p>$1,345</p>
          </div>
        </div>
        <div className="card">
          <div className="card-body">
            <i className="fas fa-check-circle"></i>
            <h4>Orders</h4>
            <p>576</p>
          </div>
        </div>
      </div>

      <div className="chart-section">
        <h4 className="chart-title">Sales Overview</h4>
        <div className="chart-container">
          <Line data={chartData} />
        </div>
      </div>
    </div>

               
       
)
}

export default dashboard