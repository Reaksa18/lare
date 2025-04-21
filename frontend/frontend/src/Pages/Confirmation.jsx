import React from "react";
import { useLocation, useNavigate } from "react-router-dom";

const Confirmation = () => {
  const navigate = useNavigate();
  const { state } = useLocation();
  const { orderId, carts, total } = state || {};

  return (
    <div style={styles.confirmationContainer}>
      <h2 style={styles.heading}>Thank You for Your Purchase!</h2>
      <p>
        Your order has been successfully placed. Your order ID is{" "}
        <strong>{orderId}</strong>.
      </p>
      <div style={styles.orderDetails}>
        <h3>Order Summary</h3>
        <ul style={styles.list}>
          {carts.map((item) => (
            <li key={item.id} style={styles.listItem}>
              {item.title} x {item.quantity} - $
              {(item.price * item.quantity).toFixed(2)}
            </li>
          ))}
        </ul>
        <p>
          <strong>Total: ${total.toFixed(2)}</strong>
        </p>
      </div>
      <div style={styles.buttons}>
        <button style={styles.primaryButton} onClick={() => navigate("/product")}>
          Continue Shopping
        </button>
      </div>
    </div>
  );
};

const styles = {
  confirmationContainer: {
    textAlign: "center",
    maxWidth: "800px",
    margin: "50px auto",
    padding: "20px",
    background: "#f9f9f9",
    borderRadius: "8px",
  },
  heading: {
    fontSize: "2rem",
    marginBottom: "20px",
    color: "#4caf50",
  },
  orderDetails: {
    textAlign: "left",
    margin: "20px auto",
    padding: "10px",
    background: "#fff",
    borderRadius: "8px",
    boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)",
  },
  list: {
    listStyle: "none",
    padding: "0",
    margin: "0",
  },
  listItem: {
    marginBottom: "10px",
  },
  buttons: {
    marginTop: "20px",
  },
  primaryButton: {
    backgroundColor: "#007bff",
    color: "#fff",
    border: "none",
    padding: "10px 20px",
    borderRadius: "4px",
    cursor: "pointer",
  },
};

export default Confirmation;
