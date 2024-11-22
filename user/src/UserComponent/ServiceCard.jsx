import React from 'react';

const ServiceCard = () => {
  return (
    <div style={styles.container}>
      <h2 style={styles.header}>Services We Offer!</h2>
      <div style={styles.row}>
        <div style={styles.textColumn}>
          <h3 style={styles.title}>WordPress</h3>
          <p style={styles.description}>
            Get hands-on experience with the content management system people love to have!
            WordPress is free, hosting isn’t a hassle, and the plugins enable you to do just 
            anything you want on the website. With full hold over the website functionalities, 
            we’ll help you out at every step you take.
          </p>
          <button style={styles.button}>View More</button>
        </div>
        <div style={styles.imageColumn}>
          {/* You can replace this with the actual image or icon */}
          <div style={styles.icon}>
            {/* Placeholder for the image */}
          </div>
        </div>
      </div>
    </div>
  );
};

const styles = {
  container: {
    textAlign: 'center',
    padding: '40px 20px',
    backgroundColor: '#fff',
  },
  header: {
    fontSize: '32px',
    fontWeight: 'bold',
    marginBottom: '40px',
  },
  row: {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    maxWidth: '1200px',
    margin: '0 auto',
  },
  textColumn: {
    flex: '1',
    paddingRight: '20px',
  },
  imageColumn: {
    flex: '1',
    display: 'flex',
    justifyContent: 'center',
  },
  title: {
    fontSize: '28px',
    fontWeight: 'bold',
    color: '#000',
  },
  description: {
    fontSize: '16px',
    color: '#666',
    margin: '20px 0',
    lineHeight: '1.5',
  },
  button: {
    padding: '10px 20px',
    fontSize: '16px',
    backgroundColor: '#6c63ff',
    color: '#fff',
    border: 'none',
    borderRadius: '5px',
    cursor: 'pointer',
  },
  icon: {
    width: '250px',
    height: '250px',
    backgroundColor: '#e0e0e0',
    borderRadius: '50%',
  },
};

export default ServiceCard;
