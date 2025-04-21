import React from 'react';

const aboutUs = () => {
  return (
    <div>
       {/* inner page section */}
       <section className="inner_page_head">
       <div className="container_fuild">
         <div className="row">
           <div className="col-md-12">
             <div className="full">
               <h3>About Us</h3>
             </div>
           </div>
         </div>
       </div>
     </section>
     {/* end inner page section */}
    <div style={styles.container}>
      <div style={styles.header}>
        <h1 style={styles.title}>Welcome To Our Store</h1>
        <p style={styles.subtitle}>Your trusted destination for quality and convenience</p>
      </div>

      <section style={styles.section}>
        <h2 style={styles.sectionTitle}>Who We Are</h2>
        <p style={styles.text}>
          We’re a passionate team of creators, designers, and innovators dedicated to bringing you the best online shopping experience.
          Our mission is to connect you with high-quality products and unbeatable service, all in one seamless platform.
        </p>
      </section>

      <section style={styles.section}>
        <h2 style={styles.sectionTitle}>Our Story</h2>
        <p style={styles.text}>
          Founded in 2024, we started as a small shop with a big dream—to make online shopping personal again.
          Today, we serve thousands of happy customers, and we’re just getting started.
        </p>
      </section>

      <section style={styles.section}>
        <h2 style={styles.sectionTitle}>Why Choose Us?</h2>
        <ul style={styles.list}>
          <li>✅ Wide selection of products</li>
          <li>✅ Fast & secure checkout</li>
          <li>✅ Customer-first support team</li>
          <li>✅ Affordable pricing & regular deals</li>
        </ul>
      </section>

      <div style={styles.footer}>
        <p>&copy; {new Date().getFullYear()} E-Shop. All rights reserved.</p>
      </div>
    </div>
    </div>
  );
};

const styles = {
  container: {
    maxWidth: '900px',
    margin: '0 auto',
    padding: '2rem',
    fontFamily: 'Segoe UI, sans-serif',
    lineHeight: '1.6',
    color: '#1f2937',
  },
  header: {
    textAlign: 'center',
    marginBottom: '2rem',
  },
  title: {
    fontSize: '2.5rem',
    fontWeight: '700',
    color: '#DE1416',
  },
  subtitle: {
    fontSize: '1.1rem',
    color: '#4b5563',
    marginTop: '0.5rem',
  },
  section: {
    marginBottom: '2rem',
  },
  sectionTitle: {
    fontSize: '1.5rem',
    color: '#111827',
    fontWeight: '600',
    marginBottom: '0.75rem',
  },
  text: {
    fontSize: '1rem',
    color: '#374151',
  },
  list: {
    paddingLeft: '1.5rem',
    fontSize: '1rem',
    color: '#374151',
    listStyleType: 'disc',
  },
  footer: {
    marginTop: '3rem',
    textAlign: 'center',
    color: '#6b7280',
    fontSize: '0.9rem',
  },
};

export default aboutUs;
