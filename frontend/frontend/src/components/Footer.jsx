import React from 'react'

const Footer = () => {
  return (
    <div>
        {/* footer start */}
      <footer>
        <div className="container">
          <div className="row">
            <div className="col-md-4">
              <div className="full">
                <div className="logo_footer">
                  <a href="#"><img width={210} src="assets/images/logo.png" alt="#" /></a>
                </div>
                <div className="information_f">
                  <p><strong>ADDRESS:</strong> 168D, Steung Meanchy, Phnom Penh</p>
                  <p><strong>TELEPHONE:</strong> +855 973 111 899</p>
                  <p><strong>EMAIL:</strong> reaksatan1801@gmail.com</p>
                </div>
              </div>
            </div>
            <div className="col-md-8">
              <div className="row">
                <div className="col-md-7">
                  <div className="row">
                    <div className="col-md-6">
                      <div className="widget_menu">
                        <h3>Menu</h3>
                        <ul>
                          <li><a href="/">Home</a></li>
                          <li><a href="/about">About</a></li>
                          <li><a href="#">Services</a></li>
                          <li><a href="/testimonial">Testimonial</a></li>
                          <li><a href="/blog">Blog</a></li>
                          <li><a href="/contact">Contact</a></li>
                        </ul>
                      </div>
                    </div>
                    <div className="col-md-6">
                      <div className="widget_menu">
                        <h3>Account</h3>
                        <ul>
                          <li><a href="#">Account</a></li>
                          <li><a href="#">Checkout</a></li>
                          <li><a href="#">Login</a></li>
                          <li><a href="#">Register</a></li>
                          <li><a href="/product">Shopping</a></li>
                          <li><a href="#">Widget</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>     
                <div className="col-md-5">
                  <div className="widget_menu">
                    <h3>Newsletter</h3>
                    <div className="information_f">
                      <p>Subscribe by our newsletter and get update protidin.</p>
                    </div>
                    <div className="form_sub">
                      <form>
                        <fieldset>
                          <div className="field">
                            <input type="email" placeholder="Enter Your Mail" name="email" />
                            <input type="submit" defaultValue="Subscribe" />
                          </div>
                        </fieldset>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </footer>
      {/* footer end */}
    </div>
  )
}

export default Footer