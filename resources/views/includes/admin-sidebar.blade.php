<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('dashboard')}}">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>


          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#masters" aria-expanded="false" aria-controls="charts">
            <i class="mdi mdi-group menu-icon"></i> 
              <span class="menu-title">Masters </span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="masters">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('home-video-gallary')}}"> Video Slider</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('what-in-farm')}}"> What Do In Farm</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('certificate')}}"> Caertificate</a></li>
                <!-- <li class="nav-item"> <a class="nav-link" href="{{URL::to('about-us-images')}}"> About-Us Content</a></li> -->
               <li class="nav-item"> <a class="nav-link" href="{{URL::to('about-us-images')}}"> About-Us Images</a></li>
            </ul>
            </div>
            
     
          </li>


          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#masters" aria-expanded="false" aria-controls="charts">
            <i class="mdi mdi-group menu-icon"></i> 
              <span class="menu-title">Blog </span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="masters">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('video-gallary')}}"> Video Gallary</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('testimonial')}}"> Testimonial</a></li>
            
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('news-letter')}}"> News Letter</a></li>
            </ul>
            </div>
            
     
          </li>

              

          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#product_master" aria-expanded="false" aria-controls="charts">
            <i class="mdi mdi-group menu-icon"></i> 
              <span class="menu-title">Product Master </span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="product_master">
              <ul class="nav flex-column sub-menu">
                
                <li class="nav-item"> <a class="nav-link" href="{{URL::to('categories')}}">Categories</a></li>
                
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('products')}}">Products</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('product-price-manage')}}">Price Manage</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('products-status')}}">Product Manage </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('products-size-status')}}">Product Size Manage </a></li>


              </ul>
            </div>
            
    
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#review" aria-expanded="false" aria-controls="charts">
            <i class="mdi mdi-group menu-icon"></i> 
              <span class="menu-title">Review </span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="review">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('review-pending')}}">Pending</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('review-active')}}">Active</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('review-block')}}">Blocked</a></li>

              </ul>
            </div>
            
     
          </li>
                 

<li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#website_other" aria-expanded="false" aria-controls="charts">
              <i class="icon-bar-graph menu-icon"></i>
              <span class="menu-title">Orders</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="website_other">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('order-dashboard')}}">Dashboard</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('orders/tracking')}}">Order Tracking</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL::to('all-pending-orders')}}">Pending Orders</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL::to('all-accepted-orders')}}">Accepted Orders</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL::to('all-packed-orders')}}">Packed Orders</a></li>
                 <li class="nav-item"> <a class="nav-link" href="{{URL::to('all-delivered-orders')}}">Delivered Orders</a></li>
                 </ul>
            </div>
     
          </li>
        

          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#transaction" aria-expanded="false" aria-controls="charts">
            <i class="mdi mdi-group menu-icon"></i> 
              <span class="menu-title">Transaction </span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="transaction">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('canceled-order')}}"> Canceld Order</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('return-items-pending')}}"> Return Items</a></li>
              </ul>
            </div>
            
     
          </li>
 

          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#product_master" aria-expanded="false" aria-controls="charts">
            <i class="icon-bar-graph menu-icon"></i>
              <span class="menu-title">Sales Report</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="product_master">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('sales-report/date-wise')}}">Date Wise</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('sales-report/product-wise')}}">Product Wise</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('sales-report/area-wise')}}">Area Wise </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('reports/most-moving')}}">Most Moving </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('reports/least-moving')}}">Least Moving </a></li>
            
              </ul>
            </div>
            
     
          </li>

          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#re_purchase_report" aria-expanded="false" aria-controls="charts">
            <i class="icon-bar-graph menu-icon"></i>
              <span class="menu-title">Re Purchase Report</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="re_purchase_report">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('most-repurchased-customers')}}">Customer List</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('most-repurchased-products')}}">Product List</a></li>
             
              </ul>
            </div>
            
     
          </li>
         
        </ul>
      </nav>