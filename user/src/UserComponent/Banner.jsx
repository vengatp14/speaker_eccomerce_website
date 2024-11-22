import Carousel from 'react-bootstrap/Carousel';

function Banner() {
  return (
    <Carousel>
      {/* First Slide */}
      <Carousel.Item interval={1000}>
        <img
          className="d-block w-100"
          src="/src/assets/op.png"
          alt="First slide"
        />
        <Carousel.Caption>

        </Carousel.Caption>
      </Carousel.Item>

      {/* Second Slide */}
      <Carousel.Item interval={500}>
        <img
          className="d-block w-100"
          src="/src/assets/op1.png"
          alt="Second slide"
        />
        <Carousel.Caption>

        </Carousel.Caption>
      </Carousel.Item>

      {/* Third Slide */}
      <Carousel.Item>
        <img
          className="d-block w-100"
          src="/src/assets/banner2.jpg"
          alt="Third slide"
        />
        <Carousel.Caption>
          
        </Carousel.Caption>
      </Carousel.Item>
    </Carousel>
  );
}

export default Banner;
