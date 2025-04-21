import React from 'react'
import { useState } from "react";
const Contact = () => {
  const [responseMessage, setResponseMessage] = useState("");
  const handleSubmit = (e) => {
    e.preventDefault(); // Prevent the default form submission
    setResponseMessage("Thank you for contacting us!"); // Set the thank you message
    e.target.reset(); // Clear the form fields after submission
  };
  return (
    <div>
       {/* inner page section */}
       <section className="inner_page_head">
        <div className="container_fuild">
          <div className="row">
            <div className="col-md-12">
              <div className="full">
                <h3>Contact Us</h3>
              </div>
            </div>
          </div>
        </div>
       </section>
      {/* end inner page section */}

       {/* why section */}
       <section className="why_section layout_padding">
      <div className="container">
        <div className="row">
          <div className="col-lg-8 offset-lg-2">
            <div className="full">
              <form id="contactForm" onSubmit={handleSubmit}>
                <fieldset>
                  <input
                    type="text"
                    placeholder="Enter your full name"
                    name="name"
                    required
                  />
                  <input
                    type="email"
                    placeholder="Enter your email address"
                    name="email"
                    required
                  />
                  <input
                    type="text"
                    placeholder="Enter subject"
                    name="subject"
                    required
                  />
                  <textarea
                    placeholder="Enter your message"
                    required
                    defaultValue={""}
                  />
                  <input type="submit" value="Submit" />
                </fieldset>
              </form>
              {/* Display response message */}
              {responseMessage && (
                <p style={{ color: "green", marginTop: "10px" }}>{responseMessage}</p>
              )}
            </div>
          </div>
        </div>
      </div>
    </section>
      {/* end why section */}

      {/* arrival section */}
      <section className="arrival_section">
        <div className="container">
          <div className="box">
            <div className="arrival_bg_box">
              <img src="assets/images/arrival-bg.png" alt="" />
            </div>
            <div className="row">
              <div className="col-md-6 ml-auto">
                <div className="heading_container remove_line_bt">
                  <h2>
                    #NewArrivals
                  </h2>
                </div>
                <p style={{marginTop: '20px', marginBottom: '30px'}}>
                Exciting news! Our latest collection of new arrivals is here. Packed with trendy designs, vibrant colors, and fresh styles, it’s everything you need to upgrade your wardrobe. Shop now and stay ahead!
                </p>
                <a href>
                  Shop Now
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* end arrival section */}
    </div>
  )
}

export default Contact