import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import './Shopcard.css';

const Shopcard = () => {
  return (
    <div className="container">
      <h1 className="poo text-center">OUR DEVELOPMENT PROCESS</h1>

      <div className="row">
        {/* Row 1 */}
        <div className="col-md-6 mb-4">
          <div className="card shadow-card">
            <div className="card-body text-center">
              <img src="/src/assets/imq.png" alt="Discover Icon" className="card-img-top" />
              <h3>Discover</h3>
              <p>We collate accurate information for you to discover. We believe in a brief discussion about your needs so we can proceed together to achieve the core objectives.</p>
            </div>
          </div>
        </div>

        <div className="col-md-6 mb-4">
          <div className="card shadow-card">
            <div className="card-body text-center">
              <img src="/src/assets/img2.png" alt="Define Icon" className="card-img-top" />
              <h3>Define</h3>
              <p>We make sure there is clarity in everything we discuss, from what time period the tasks will require. After mentioning all the requirements, we prefer signing an NDA.</p>
            </div>
          </div>
        </div>
      </div>

      <div className="row">
        {/* Row 2 */}
        <div className="col-md-6 mb-4">
          <div className="card shadow-card">
            <div className="card-body text-center">
              <img src="/src/assets/img3.png" alt="Design Icon" className="card-img-top" />
              <h3>Design</h3>
              <p>We help you build a structured depiction for your online store. Also, we make sure of your confirmation at every change we implement.</p>
            </div>
          </div>
        </div>

        <div className="col-md-6 mb-4">
          <div className="card shadow-card">
            <div className="card-body text-center">
              <img src="/src/assets/img4.png" alt="Develop Icon" className="card-img-top" />
              <h3>Develop</h3>
              <p>The development process helps bring flexibility for the project. We have the best hand-picked plugins and themes for any online store.</p>
            </div>
          </div>
        </div>
      </div>

      <div className="row">
        {/* Row 3 */}
        <div className="col-md-6 mb-4">
          <div className="card shadow-card">
            <div className="card-body text-center">
              <img src="/src/assets/img5.png" alt="Deliver Icon" className="card-img-top" />
              <h3>Deliver</h3>
              <p>We ensure timely delivery with precision. We also ensure your confirmation at every step.</p>
            </div>
          </div>
        </div>

        <div className="col-md-6 mb-4">
          <div className="card shadow-card">
            <div className="card-body text-center">
              <img src="/src/assets/img6.png" alt="Support Icon" className="card-img-top" />
              <h3>Support</h3>
              <p>We provide continuous support after delivery, ensuring smooth operation for your project.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Shopcard;
