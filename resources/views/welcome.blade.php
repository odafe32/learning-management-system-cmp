
<x-landing-layout>
    <!-- Navigation Bar -->
    <header class="uk-cover-container uk-background-cover uk-background-norepeat uk-background-center-center" 
        style="background-image: url({{ url('assets/images/landing/hero-bg.jpg') }});">
        <video src="{{ url('assets/videos/hero.mp4') }}" data-uk-cover></video>
        <div class="uk-overlay uk-position-cover uk-overlay-video"></div>

        <!-- Sticky Navbar -->
        <div data-uk-sticky="animation: uk-animation-slide-top; sel-target: .uk-navbar-container; 
            cls-active: uk-navbar-sticky; cls-inactive: uk-navbar-transparent uk-light; top: 500">
            <nav class="uk-navbar-container uk-letter-spacing-small uk-text-bold">
                <div class="uk-container uk-container-large">
                    <div class="uk-position-z-index" data-uk-navbar>
                        <div class="uk-navbar-left">
                            <a class="uk-navbar-item uk-logo" href="{{ route('dashboard') }}">EduMate</a>
                        </div>
                        <div class="uk-navbar-center">
                            <ul class="uk-navbar-nav uk-visible@m">
                                <li class="uk-active"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li><a href="#about">About</a></li>
                                <li><a href="#courses">Courses</a></li>
                                <li><a href="#contact">Contact</a></li>
                            </ul>
                        </div>
                        <div class="uk-navbar-right">
                            <div class="uk-navbar-item">
                                <div>
                                    <a class="uk-button uk-button-success-outline" href="{{ route('login') }}">Sign in</a>
                                </div>
                            </div>
                            <a class="uk-navbar-toggle uk-hidden@m" href="#offcanvas" data-uk-toggle>
                                <span data-uk-navbar-toggle-icon></span>
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Hero Section -->
        <div class="uk-container uk-container-large uk-light" data-uk-height-viewport="offset-top: true">
            <div data-uk-grid data-uk-height-viewport="offset-top: true">
                <!-- Scroll Left -->
                <div class="uk-header-left uk-section uk-visible@m uk-flex uk-flex-bottom">
                    <div class="uk-text-xsmall uk-text-bold">
                        <a class="hvr-back" href="#about" data-uk-scroll="offset: 80">
                            <span class="uk-margin-small-right" data-uk-icon="arrow-left"></span>Scroll down
                        </a>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="uk-width-expand@m uk-section uk-flex uk-flex-column">
                    <div class="uk-margin-auto-top uk-margin-auto-bottom">
                        <h1 class="uk-heading-medium uk-margin-remove-top uk-letter-spacing-xl" 
                            data-uk-scrollspy="cls: uk-animation-slide-bottom-medium; repeat: true">
                            <mark>Learn</mark> anytime, <mark>practice</mark> anywhere, and get <mark>feedback</mark> instantly
                        </h1>

                        <!-- Search Bar -->
                        <div class="uk-grid-collapse uk-width-3-4@m uk-margin-medium-top" 
                            data-uk-grid data-uk-scrollspy="cls: uk-animation-slide-bottom-medium; delay: 200; repeat: true">
                            <div class="uk-width-expand">
                                <input class="uk-input uk-form-large uk-border-remove-right" type="text"  
                                    placeholder="Find your course...">
                            </div>
                            <div class="uk-width-auto">
                                <button class="uk-button uk-button-large uk-button-success-outline">Search courses</button>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="uk-margin-auto-top" 
                        data-uk-scrollspy="cls: uk-animation-slide-bottom-medium; delay: 400; repeat: true">
                        <div class="uk-child-width-1-2@s uk-grid-large uk-margin-medium-top" data-uk-grid>
                            <div>
                                <h4 class="uk-margin-remove">Choose from a wide range of courses</h4>
                                <p class="uk-margin-xsmall-top uk-text-small uk-text-muted uk-text-bold">
                                    Explore technology, business, design, and more — all taught by experienced instructors.
                                </p>
                            </div>
                            <div>
                                <h4 class="uk-margin-remove">Learn at your own pace</h4>
                                <p class="uk-margin-xsmall-top uk-text-small uk-text-muted uk-text-bold">
                                    Access lecture materials, assignments, and coding practice 24/7, on any device.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="uk-header-right uk-section uk-visible@m uk-flex uk-flex-right uk-flex-bottom">
                    <div>
                        <ul class="uk-subnav uk-text-xsmall uk-text-bold">
                            <li><a class="uk-link-border" href="#" target="_blank">Facebook</a></li>
                            <li><a class="uk-link-border" href="#" target="_blank">Twitter</a></li>
                            <li><a class="uk-link-border" href="#" target="_blank">Instagram</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>


<div class="uk-section uk-section-muted uk-section-large">
  <div class="uk-container uk-container-expand">
    <div class="uk-child-width-1-2@s uk-grid-large" data-uk-grid>
      <div>
        <div class="uk-child-width-1-3 uk-grid-small" data-uk-grid="parallax: 150">
          <div>
            <img src="https://images.unsplash.com/photo-1529070538774-1843cb3265df?auto=format&fit=crop&w=400&q=80" alt="Students learning online">
          </div>
          <div>
            <img src="https://images.unsplash.com/photo-1584697964192-6b1aadcffda1?auto=format&fit=crop&w=400&q=80" alt="E-learning workspace">
          </div>
          <div>
            <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=400&q=80" alt="Team studying">
          </div>
          <div>
            <img src="https://images.unsplash.com/photo-1554774853-b415df9eeb92?auto=format&fit=crop&w=400&q=80" alt="Online course study">
          </div>
          <div>
            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=400&q=80" alt="Laptop learning">
          </div>
          <div>
            <img src="https://images.unsplash.com/photo-1584697964350-8da7516f44e7?auto=format&fit=crop&w=400&q=80" alt="Coding practice">
          </div>
        </div>
      </div>
      <div class="uk-flex uk-flex-middle">
        <div class="uk-width-3-4@m">
        <h2 class="uk-heading-small">The <mark>NSUK Community</mark> is here every step of the way</h2>
<ul class="uk-list uk-list-large uk-list-bullet uk-text-large uk-margin-medium-top">
  <li>Connect with lecturers and fellow students across all faculties</li>
  <li>Access verified course materials and past questions</li>
  <li>Engage in online discussions and academic groups</li>
  <li>Get feedback and support to excel in your studies</li>
</ul>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Call to Action -->
<div class="uk-section uk-section-muted uk-section-large uk-padding-remove-bottom">
  <div class="uk-container uk-container-small uk-text-center">
<h2 class="uk-heading-small"><mark>Join</mark> thousands of NSUK students and start learning smarter <mark>today</mark></h2>
    <a href="{{ route('login') }}" class="uk-button uk-button-primary uk-button-large uk-margin-medium-top">Sign in</a>
  </div>
  <div class="uk-child-width-1-5 uk-child-width-expand@m uk-margin-xlarge-top uk-grid-collapse" data-uk-grid>
    <div><img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=400&q=80" alt="Online class"></div>
    <div><img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=400&q=80" alt="Laptop study"></div>
    <div><img src="https://images.unsplash.com/photo-1581090700227-4c4f30e2e6a1?auto=format&fit=crop&w=400&q=80" alt="Student studying"></div>
    <div><img src="https://images.unsplash.com/photo-1596495577886-d920f1fb7238?auto=format&fit=crop&w=400&q=80" alt="Team collaboration"></div>
    <div><img src="https://images.unsplash.com/photo-1554774853-b415df9eeb92?auto=format&fit=crop&w=400&q=80" alt="Learning workspace"></div>
  </div>
</div>

<!-- Courses Section -->
<div class="uk-section uk-section-large uk-background-cover uk-background-norepeat 
  uk-background-center-center uk-background-blend-soft-light uk-light uk-background-primary"
  style="background-image: url(https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1400&q=80);">
  <div class="uk-container uk-container-large">
    <div class="uk-width-1-2@m">
      <h2 class="uk-heading-small uk-margin-remove">What do you want to <mark>learn today</mark>?</h2>
    </div>
  </div>
  <div class="uk-container uk-container-large uk-margin-large-top">
    <div class="uk-grid-large" data-uk-grid>
      <div class="uk-width-1-3@m">
        <p class="uk-text-large">Discover curated online courses in development, business, design, and more. Learn from industry experts at your own pace.</p>
        <a href="" class="uk-button uk-button-success-outline uk-button-large uk-margin-medium-top">Browse Courses</a>
      </div>
      <div class="uk-width-expand@m">
        <!-- Categories Slider -->
        <div data-uk-slider="sets: true">
          <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1">
            <ul class="uk-slider-items uk-child-width-1-3@s uk-grid-large">
              <li>
                <div class="uk-card uk-card-default uk-card-small uk-height-small uk-flex uk-flex-column uk-border-rounded">
                  <div class="uk-card-body uk-margin-auto-top">
                    <h3 class="uk-card-title uk-margin-remove">Business</h3>
                    <p class="uk-text-small uk-text-demi-bold uk-margin-xsmall-top">185 courses</p>
                  </div>
                  <a href="" class="uk-position-cover"></a>
                </div>
              </li>
              <li>
                <div class="uk-card uk-card-default uk-card-small uk-height-small uk-flex uk-flex-column uk-border-rounded">
                  <div class="uk-card-body uk-margin-auto-top">
                    <h3 class="uk-card-title uk-margin-remove">Development</h3>
                    <p class="uk-text-small uk-text-demi-bold uk-margin-xsmall-top">79 courses</p>
                  </div>
                  <a href="" class="uk-position-cover"></a>
                </div>
              </li>
              <li>
                <div class="uk-card uk-card-default uk-card-small uk-height-small uk-flex uk-flex-column uk-border-rounded">
                  <div class="uk-card-body uk-margin-auto-top">
                    <h3 class="uk-card-title uk-margin-remove">Design</h3>
                    <p class="uk-text-small uk-text-demi-bold uk-margin-xsmall-top">256 courses</p>
                  </div>
                  <a href="" class="uk-position-cover"></a>
                </div>
              </li>
              <li>
                <div class="uk-card uk-card-default uk-card-small uk-height-small uk-flex uk-flex-column uk-border-rounded">
                  <div class="uk-card-body uk-margin-auto-top">
                    <h3 class="uk-card-title uk-margin-remove">Finance</h3>
                    <p class="uk-text-small uk-text-demi-bold uk-margin-xsmall-top">214 courses</p>
                  </div>
                  <a href="" class="uk-position-cover"></a>
                </div>
              </li>
              <li>
                <div class="uk-card uk-card-default uk-card-small uk-height-small uk-flex uk-flex-column uk-border-rounded">
                  <div class="uk-card-body uk-margin-auto-top">
                    <h3 class="uk-card-title uk-margin-remove">Marketing</h3>
                    <p class="uk-text-small uk-text-demi-bold uk-margin-xsmall-top">79 courses</p>
                  </div>
                  <a href="" class="uk-position-cover"></a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="uk-section uk-section-secondary">
  <div class="uk-container">
    <div class="uk-child-width-1-2@m uk-grid-large uk-flex uk-flex-middle" data-uk-grid>
      <div>
        <h3>📩 Subscribe to get updates on new courses, events, and special offers.</h3>
      </div>
      <div>
        <div class="uk-grid-collapse" data-uk-grid>
          <div class="uk-width-expand">
            <input class="uk-input uk-form-large uk-border-remove-right" type="email" placeholder="Enter your email address">
          </div>
          <div class="uk-width-auto">
            <button class="uk-button uk-button-large uk-button-success-outline">Subscribe</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="uk-border-dark-top">
  <div class="uk-section uk-section-secondary">
    <div class="uk-container uk-h6 uk-margin-top">
      <div class="uk-child-width-1-2@s uk-child-width-1-4@m uk-grid-large" data-uk-grid>
        
        <!-- Logo -->
        <div>
          <a href="{{ route('dashboard') }}" class="uk-logo">EduMate</a>
          <p class="uk-text-small uk-margin-small-top">
            A modern Learning Management System designed to help you <br>
            learn faster, practice better, and connect with instructors.
          </p>
        </div>



      </div>
    </div>
  </div>

  <!-- Bottom Footer -->
  <div class="uk-section uk-section-secondary uk-padding-small">
    <div class="uk-container uk-h6"> 
      <div class="uk-child-width-1-2@m uk-grid-large" data-uk-grid>
        
        <div class="uk-flex-first@m">
          <p>&copy; {{ date('Y') }} EduMate LMS. All rights reserved.</p>
        </div>

        <div class="uk-flex uk-flex-right@m">
          <ul class="uk-subnav">
            <li><a class="uk-link-border" href="#" target="_blank">Facebook</a></li>
            <li><a class="uk-link-border" href="#" target="_blank">Twitter</a></li>
            <li><a class="uk-link-border" href="#" target="_blank">Instagram</a></li>
            <li><a class="uk-link-border" href="#" target="_blank">LinkedIn</a></li>
          </ul>
        </div>

      </div>
    </div>
  </div>
</footer>



</x-landing-layout>