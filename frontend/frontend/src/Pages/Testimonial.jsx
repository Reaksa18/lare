import React from 'react'


const Testimonial = () => {
  return (
    <div>
         {/* inner page section */}
      <section className="inner_page_head">
        <div className="container_fuild">
          <div className="row">
            <div className="col-md-12">
              <div className="full">
                <h3>Testimonial</h3>
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* end inner page section */}
      {/* client section */}
      <section className="client_section layout_padding">
        <div className="container">
          <div className="heading_container heading_center">
            <h2>
              Customer's Testimonial
            </h2>
          </div>
          <div id="carouselExample3Controls" className="carousel slide" data-ride="carousel">
            <div className="carousel-inner">
              <div className="carousel-item active">
                <div className="box col-lg-10 mx-auto">
                  <div className="img_container">
                    <div className="img-box">
                      <div className="img_box-inner">
                        <img src="assets/images/client.jpg" alt="" />
                      </div>
                    </div>
                  </div>
                  <div className="detail-box">
                    <h5>
                      Anna Trevor
                    </h5>
                    <h6>
                      Customer
                    </h6>
                    <p>
                    Absolutely love shopping here! The quality is unmatched, and the styles are always on point. Fast delivery and excellent customer service. Definitely my go-to store for fashionable, high-quality clothes!
                    </p>
                  </div>
                </div>
              </div>
              <div className="carousel-item">
                <div className="box col-lg-10 mx-auto">
                  <div className="img_container">
                    <div className="img-box">
                      <div className="img_box-inner">
                        <img src="assets/images/client.jpg" alt="" />
                      </div>
                    </div>
                  </div>
                  <div className="detail-box">
                    <h5>
                      Anna Trevor
                    </h5>
                    <h6>
                      Customer
                    </h6>
                    <p>
                    Fantastic experience shopping here! The clothes are stylish, comfortable, and made with great quality. Fast shipping, and customer service is always helpful. Highly recommend for anyone looking for trendy fashion!
                    </p>
                  </div>
                </div>
              </div>
              <div className="carousel-item">
                <div className="box col-lg-10 mx-auto">
                  <div className="img_container">
                    <div className="img-box">
                      <div className="img_box-inner">
                        <img src="assets/images/client.jpg" alt="" />
                      </div>
                    </div>
                  </div>
                  <div className="detail-box">
                    <h5>
                      Anna Trevor
                    </h5>
                    <h6>
                      Customer
                    </h6>
                    <p>
                    I'm thrilled with my purchase! The clothes are stylish, high-quality, and fit perfectly. Fast delivery and excellent customer service. This shop is my new favorite for trendy, must-have pieces!                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div className="carousel_btn_box">
              <a className="carousel-control-prev" href="#carouselExample3Controls" role="button" data-slide="prev">
                <i className="fa fa-long-arrow-left" aria-hidden="true" />
                <span className="sr-only">Previous</span>
              </a>
              <a className="carousel-control-next" href="#carouselExample3Controls" role="button" data-slide="next">
                <i className="fa fa-long-arrow-right" aria-hidden="true" />
                <span className="sr-only">Next</span>
              </a>
            </div>
          </div>
        </div>
      </section>
      {/* end client section */}
    </div>
  )
}

export default Testimonial