import React from 'react';
import { Container, Row, Col, Card } from 'react-bootstrap';

const testimonials = [
  {
    name: 'Celia Almeda',
    image: '/src/assets/john.jpg',
    text: 'Proin sed libero enim sed faucibus turpis. At imperdiet dui accumsan sit amet nulla facilisi morbi tempus. Ut sem nulla pharetra diam sit amet nisl.'
  },
  {
    name: 'Frank Kinney',
    image: '/src/assets/john.jpg',
    text: 'Proin sed libero enim sed faucibus turpis. At imperdiet dui accumsan sit amet nulla facilisi morbi tempus. Ut sem nulla pharetra diam sit amet nisl.'
  },
  {
    name: 'Nick Jhonson',
    image: '/src/assets/john.jpg',
    text: 'Proin sed libero enim sed faucibus turpis. At imperdiet dui accumsan sit amet nulla facilisi morbi tempus. Ut sem nulla pharetra diam sit amet nisl.'
  }
];

const TestimonialSection = () => {
  return (
    <div className="py-5" style={{ backgroundColor: '#f5f5f5' }}>
      <Container>
        <h2 className="text-center text-black mb-3"> OUR Testimonials</h2>
        <p className="text-center text-white-50 mb-5" style={{ maxWidth: '600px', margin: '0 auto' }}>
       
        </p>
        <Row>
          {testimonials.map((testimonial, index) => (
            <Col key={index} md={4} className="mb-4">
              <Card className="h-120" style={{ backgroundColor: 'white', border: 'none' }}>
                <Card.Body>
                  <div className="d-flex align-items-center mb-3">
                    <img
                      src={testimonial.image}
                      alt={testimonial.name}
                      className="rounded-circle me-3"
                      width="50"
                      height="60"
                    />
                    <h5 className="card-title mb-0 text-black">{testimonial.name}</h5>
                  </div>
                  <Card.Text className="text-black-50">
                    {testimonial.text}
                  </Card.Text>
                </Card.Body>
              </Card>
            </Col>
          ))}
        </Row>
      </Container>
    </div>
  );
};

export default TestimonialSection;