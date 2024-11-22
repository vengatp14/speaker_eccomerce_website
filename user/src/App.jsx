import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import 'bootstrap/dist/css/bootstrap.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

// Components
import Header from './UserComponent/Header';
import Faq from './UserComponent/Faq';
import Footer from './UserComponent/Footer';
import Ourmission from './UserComponent/Ourmission';
import TestimonialSection from './UserComponent/TestimonialSection';
import Banner from './UserComponent/Banner';
import SingleProductPage from './UserComponent/SingleProductPage';
import NewCartPage from './UserComponent/NewCartPage'; // Import the new CartPage component
import ShippingPage from './UserComponent/ShippingPage';
import Shopcard from './UserComponent/Shopcard';
import ProductListing from './UserComponent/ProductListing';
import AccountPage from './UserComponent/AccountPage'; // Import the AccountPage component
import OrderHistory from './UserComponent/OrderHistory';
// Home page component
const HomePage = () => {
  return (
    <>
      <Banner />
      <Shopcard />
      <Faq />
      <Ourmission />
      <TestimonialSection />
    </>
  );
};

function App() {
  return (
    <Router>
      <div className="App">
        <Header />
        <Routes>
          <Route path="/" element={<HomePage />} />
          <Route path="/product/:productId" element={<SingleProductPage />} />
          <Route path="/category/:categoryId" element={<ProductListing />} />
          <Route path="/cart" element={<NewCartPage />} /> {/* Add the new route for CartPage */}
          <Route path="/shipping" element={<ShippingPage />} />
          <Route path="/account" element={<AccountPage />} /> {/* Add the route for AccountPage */}
           <Route path="/order" element={<OrderHistory />} /> 
        </Routes>
        <Footer />
      </div>
    </Router>
  );
}

export default App;
