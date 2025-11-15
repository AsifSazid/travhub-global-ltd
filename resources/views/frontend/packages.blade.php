<x-frontend.layouts.master>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Travel Packages</h1>
            <p>Discover our carefully curated travel packages to exotic destinations around the world. Find your perfect
                getaway today!</p>
        </div>
    </section>

    <!-- Packages Filter Section -->
    <section class="packages-filter">
        <div class="container">
            <div class="filter-container">
                <form class="filter-form">
                    <div class="form-group">
                        <label for="destination">Destination</label>
                        <select id="destination">
                            <option value="">All Destinations</option>
                            <option value="thailand">Thailand</option>
                            <option value="malaysia">Malaysia</option>
                            <option value="dubai">Dubai</option>
                            <option value="singapore">Singapore</option>
                            <option value="bali">Bali</option>
                            <option value="europe">Europe</option>
                            <option value="usa">USA</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="duration">Duration</label>
                        <select id="duration">
                            <option value="">Any Duration</option>
                            <option value="1-3">1-3 Days</option>
                            <option value="4-7">4-7 Days</option>
                            <option value="8-14">8-14 Days</option>
                            <option value="15+">15+ Days</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="travel-date">Travel Date</label>
                        <input type="date" id="travel-date">
                    </div>
                    <div class="form-group">
                        <label for="price-range">Price Range</label>
                        <select id="price-range">
                            <option value="">Any Price</option>
                            <option value="0-20000">Under BDT 20,000</option>
                            <option value="20000-40000">BDT 20,000 - 40,000</option>
                            <option value="40000-60000">BDT 40,000 - 60,000</option>
                            <option value="60000+">BDT 60,000+</option>
                        </select>
                    </div>
                    <button type="submit" class="filter-btn">Filter Packages</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section class="packages">
        <div class="container">
            <h2 class="section-title">Our Packages</h2>
            <div class="packages-grid">
                @forelse ($packages as $package)
                    @php
                        $imageUrl = $package->images->first()?->url ?? 'default_image_url.jpg';
                    @endphp
                    <div class="package-card" data-package="bangkok-phuket">
                        <div class="package-image"
                            style="background-image: url('{{ asset('storage/images/packages') . '/' . $imageUrl }}');">
                            <div class="package-price">{{ $package->packPrices->currency->currency_code }}
                                {{ $package->packPrices->overall_price }}</div>
                        </div>
                        <div class="package-content">
                            <h3>{{ $package->title }}</h3>
                            <div class="package-meta">
                                <span><i class="fas fa-clock"></i> {{ $package->packQuatDetails->duration }} Days</span>
                                <span><i class="fas fa-map-marker-alt"></i>
                                    {{ $package->packDestinationInfos->country_title }}</span>
                                <span><i class="fas fa-star"></i> {{ $package->rating }}</span>
                            </div>
                            <p>Experience the perfect blend of vibrant city life and tropical paradise on this romantic
                                Thailand honeymoon.</p>
                            <a href="#" class="package-btn view-details">View Details</a>
                        </div>
                    </div>
                @empty
                    <p class="">No Packages Found!</p>
                @endforelse
                <div class="package-card" data-package="bangkok-phuket">
                    <div class="package-image"
                        style="background-image: url('https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                        <div class="package-price">BDT ‌‌44999</div>
                    </div>
                    <div class="package-content">
                        <h3>5 Nights 6 Days Bangkok & Phuket Honeymoon Tour</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> 6 Days</span>
                            <span><i class="fas fa-map-marker-alt"></i> Thailand</span>
                            <span><i class="fas fa-star"></i> 4.8</span>
                        </div>
                        <p>Experience the perfect blend of vibrant city life and tropical paradise on this romantic
                            Thailand honeymoon.</p>
                        <a href="#" class="package-btn view-details">View Details</a>
                    </div>
                </div>

                <div class="package-card" data-package="dubai-luxury">
                    <div class="package-image"
                        style="background-image: url('https://images.unsplash.com/photo-1534008897995-27a23e859048?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                        <div class="package-price">BDT 59999</div>
                    </div>
                    <div class="package-content">
                        <h3>Dubai Luxury Experience</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> 5 Days</span>
                            <span><i class="fas fa-map-marker-alt"></i> Dubai</span>
                            <span><i class="fas fa-star"></i> 4.9</span>
                        </div>
                        <p>Indulge in luxury with desert safaris, Burj Khalifa visits, and premium shopping experiences.
                        </p>
                        <a href="#" class="package-btn view-details">View Details</a>
                    </div>
                </div>

                <div class="package-card" data-package="bali-paradise">
                    <div class="package-image"
                        style="background-image: url('https://images.unsplash.com/photo-1528164344705-47542687000d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                        <div class="package-price">BDT 29999</div>
                    </div>
                    <div class="package-content">
                        <h3>Bali Tropical Paradise</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> 6 Days</span>
                            <span><i class="fas fa-map-marker-alt"></i> Bali</span>
                            <span><i class="fas fa-star"></i> 4.7</span>
                        </div>
                        <p>Relax in Bali's beautiful beaches, rice terraces, and spiritual temples in this tropical
                            getaway.</p>
                        <a href="#" class="package-btn view-details">View Details</a>
                    </div>
                </div>

                <div class="package-card" data-package="europe-tour">
                    <div class="package-image"
                        style="background-image: url('https://images.unsplash.com/photo-1467269204594-9661b134dd2b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                        <div class="package-price">BDT 89999</div>
                    </div>
                    <div class="package-content">
                        <h3>European Highlights Tour</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> 10 Days</span>
                            <span><i class="fas fa-map-marker-alt"></i> Europe</span>
                            <span><i class="fas fa-star"></i> 4.9</span>
                        </div>
                        <p>Explore the iconic cities of Paris, Rome, and Amsterdam in this comprehensive European tour.
                        </p>
                        <a href="#" class="package-btn view-details">View Details</a>
                    </div>
                </div>

                <div class="package-card" data-package="malaysia-explorer">
                    <div class="package-image"
                        style="background-image: url('https://images.unsplash.com/photo-1596422846543-75e6d59b35e1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                        <div class="package-price">BDT 34999</div>
                    </div>
                    <div class="package-content">
                        <h3>Malaysia Explorer</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> 7 Days</span>
                            <span><i class="fas fa-map-marker-alt"></i> Malaysia</span>
                            <span><i class="fas fa-star"></i> 4.6</span>
                        </div>
                        <p>Discover the vibrant culture, stunning landscapes, and delicious cuisine of Malaysia.</p>
                        <a href="#" class="package-btn view-details">View Details</a>
                    </div>
                </div>

                <div class="package-card" data-package="singapore-family">
                    <div class="package-image"
                        style="background-image: url('https://images.unsplash.com/photo-1525625293386-3f8f99389edd?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                        <div class="package-price">BDT 39999</div>
                    </div>
                    <div class="package-content">
                        <h3>Singapore Family Fun</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> 5 Days</span>
                            <span><i class="fas fa-map-marker-alt"></i> Singapore</span>
                            <span><i class="fas fa-star"></i> 4.8</span>
                        </div>
                        <p>Perfect for families with visits to Sentosa Island, Universal Studios, and Gardens by the
                            Bay.</p>
                        <a href="#" class="package-btn view-details">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Package Details Modal -->
    <div class="package-modal" id="packageModal">
        <div class="package-details">
            <div class="close-modal" id="closeModal">
                <i class="fas fa-times"></i>
            </div>
            <div class="package-hero" id="packageHero">
                <div class="package-hero-content">
                    <h1 id="packageTitle">5 Nights 6 Days Bangkok & Phuket Honeymoon Tour</h1>
                    <div class="location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span id="packageLocation">Thailand</span>
                    </div>
                    <div class="price" id="packagePrice">BDT ‌‌44999</div>
                </div>
            </div>
            <div class="package-body">
                <div class="package-tabs">
                    <div class="tab active" data-tab="overview">Overview</div>
                    <div class="tab" data-tab="itinerary">Itinerary</div>
                    <div class="tab" data-tab="inclusions">Inclusions/Exclusions</div>
                    <div class="tab" data-tab="policies">Policies</div>
                </div>

                <div class="tab-content active" id="overview">
                    <div class="overview-content">
                        <h2>About the Tour</h2>
                        <p id="packageDescription">This 5 nights 6 days Thailand honeymoon package offers the perfect
                            blend of vibrant city life and tropical paradise. Experience the bustling streets of Bangkok
                            and the serene beaches of Phuket on this romantic journey designed for newlyweds.</p>

                        <h2>Tour Highlights</h2>
                        <div class="highlights">
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Romantic Experiences</h4>
                                    <p>Candlelight dinners & couple activities</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-umbrella-beach"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Beach Paradise</h4>
                                    <p>Relax on Phuket's stunning beaches</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-monument"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Cultural Immersion</h4>
                                    <p>Explore Bangkok's temples and palaces</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-ship"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Island Hopping</h4>
                                    <p>Visit Phi Phi Islands & James Bond Island</p>
                                </div>
                            </div>
                        </div>

                        <h2>Quick Facts</h2>
                        <div class="highlights">
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Duration</h4>
                                    <p>5 Nights / 6 Days</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Group Size</h4>
                                    <p>2 People (Customizable)</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-plane"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Flights</h4>
                                    <p>International flights included</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-hotel"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Accommodation</h4>
                                    <p>4-star hotels & resorts</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="itinerary">
                    <h2>Detailed Itinerary</h2>
                    <div class="itinerary-day">
                        <h3>Day 1: Arrival in Bangkok</h3>
                        <p>Arrive at Bangkok airport, meet our representative and transfer to your hotel. Check-in and
                            relax. In the evening, enjoy a romantic dinner cruise along the Chao Phraya River with
                            stunning views of Bangkok's illuminated temples and skyline.</p>
                    </div>
                    <div class="itinerary-day">
                        <h3>Day 2: Bangkok City Tour</h3>
                        <p>After breakfast, visit the Grand Palace and Wat Phra Kaew (Temple of the Emerald Buddha).
                            Continue to Wat Arun (Temple of Dawn) and enjoy a traditional Thai lunch. In the evening,
                            explore the vibrant Khao San Road or enjoy a couple's spa treatment.</p>
                    </div>
                    <div class="itinerary-day">
                        <h3>Day 3: Flight to Phuket</h3>
                        <p>After breakfast, transfer to the airport for your flight to Phuket. Upon arrival, check into
                            your beachfront resort. Spend the rest of the day relaxing on the beach or by the pool.
                            Enjoy a romantic seaside dinner in the evening.</p>
                    </div>
                    <div class="itinerary-day">
                        <h3>Day 4: Phi Phi Island Tour</h3>
                        <p>Embark on a full-day speedboat tour to the stunning Phi Phi Islands. Visit Maya Bay (featured
                            in the movie "The Beach"), go snorkeling in crystal-clear waters, and enjoy a beachside
                            lunch. Return to Phuket in the evening.</p>
                    </div>
                    <div class="itinerary-day">
                        <h3>Day 5: James Bond Island & Phang Nga Bay</h3>
                        <p>Take a canoe tour through the limestone caves and mangroves of Phang Nga Bay. Visit James
                            Bond Island, famous for its appearance in "The Man with the Golden Gun." Enjoy a sunset view
                            from Promthep Cape in the evening.</p>
                    </div>
                    <div class="itinerary-day">
                        <h3>Day 6: Departure</h3>
                        <p>After breakfast, check out from your hotel. Depending on your flight schedule, you may have
                            time for some last-minute shopping or beach time. Transfer to Phuket International Airport
                            for your departure flight.</p>
                    </div>
                </div>

                <div class="tab-content" id="inclusions">
                    <div class="highlights">
                        <div style="flex: 1;">
                            <h2>Inclusions</h2>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>Accommodation in 4-star hotels</span>
                            </div>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>Daily breakfast at the hotel</span>
                            </div>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>All airport and inter-city transfers</span>
                            </div>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>Sightseeing tours as per itinerary</span>
                            </div>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>English speaking guide</span>
                            </div>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>Entrance fees to monuments</span>
                            </div>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>Bangkok to Phuket flight</span>
                            </div>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>All taxes and service charges</span>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <h2>Exclusions</h2>
                            <div class="exclusion-item">
                                <i class="fas fa-times"></i>
                                <span>International airfare</span>
                            </div>
                            <div class="exclusion-item">
                                <i class="fas fa-times"></i>
                                <span>Visa fees</span>
                            </div>
                            <div class="exclusion-item">
                                <i class="fas fa-times"></i>
                                <span>Travel insurance</span>
                            </div>
                            <div class="exclusion-item">
                                <i class="fas fa-times"></i>
                                <span>Lunch and dinner (unless specified)</span>
                            </div>
                            <div class="exclusion-item">
                                <i class="fas fa-times"></i>
                                <span>Personal expenses</span>
                            </div>
                            <div class="exclusion-item">
                                <i class="fas fa-times"></i>
                                <span>Optional tours and activities</span>
                            </div>
                            <div class="exclusion-item">
                                <i class="fas fa-times"></i>
                                <span>Tips and gratuities</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="policies">
                    <h2>Cancellation Policy</h2>
                    <p>Cancellations made 30 days or more before departure: 90% refund</p>
                    <p>Cancellations made 15-29 days before departure: 50% refund</p>
                    <p>Cancellations made 8-14 days before departure: 25% refund</p>
                    <p>Cancellations made 7 days or less before departure: No refund</p>

                    <h2>Payment Policy</h2>
                    <p>25% of the total package cost to be paid at the time of booking</p>
                    <p>Remaining 75% to be paid 30 days before departure</p>

                    <h2>Other Policies</h2>
                    <p>Rates are subject to change based on hotel availability and flight fares</p>
                    <p>Check-in time at hotels is 2:00 PM, check-out time is 12:00 PM</p>
                    <p>Early check-in and late check-out are subject to availability and additional charges</p>
                </div>

                <div class="booking-card">
                    <h3>Book This Package</h3>
                    <div class="booking-price">
                        <div class="price">BDT ‌‌44999</div>
                        <div class="per-person">per person</div>
                    </div>
                    <form class="booking-form">
                        <div class="form-group">
                            <label for="checkin">Check-in Date</label>
                            <input type="date" id="checkin" required>
                        </div>
                        <div class="form-group">
                            <label for="checkout">Check-out Date</label>
                            <input type="date" id="checkout" required>
                        </div>
                        <div class="form-group">
                            <label for="travelers">Number of Travelers</label>
                            <select id="travelers" required>
                                <option value="1">1 Traveler</option>
                                <option value="2" selected>2 Travelers</option>
                                <option value="3">3 Travelers</option>
                                <option value="4">4 Travelers</option>
                                <option value="5">5 Travelers</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="room-type">Room Type</label>
                            <select id="room-type" required>
                                <option value="standard">Standard Room</option>
                                <option value="deluxe" selected>Deluxe Room</option>
                                <option value="suite">Suite</option>
                            </select>
                        </div>
                        <div class="form-group full-width">
                            <label for="special-requests">Special Requests</label>
                            <textarea id="special-requests" rows="3" placeholder="Any special requests or requirements..."></textarea>
                        </div>
                        <button type="submit" class="book-now-btn">Book Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            // Package data
            const packages = {
                'bangkok-phuket': {
                    title: '5 Nights 6 Days Bangkok & Phuket Honeymoon Tour',
                    location: 'Thailand',
                    price: 'BDT ‌‌44999',
                    image: 'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                    description: 'This 5 nights 6 days Thailand honeymoon package offers the perfect blend of vibrant city life and tropical paradise. Experience the bustling streets of Bangkok and the serene beaches of Phuket on this romantic journey designed for newlyweds.'
                },
                'dubai-luxury': {
                    title: 'Dubai Luxury Experience',
                    location: 'Dubai, UAE',
                    price: 'BDT 59999',
                    image: 'https://images.unsplash.com/photo-1534008897995-27a23e859048?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                    description: 'Indulge in luxury with desert safaris, Burj Khalifa visits, and premium shopping experiences in the dazzling city of Dubai.'
                },
                'bali-paradise': {
                    title: 'Bali Tropical Paradise',
                    location: 'Bali, Indonesia',
                    price: 'BDT 29999',
                    image: 'https://images.unsplash.com/photo-1528164344705-47542687000d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                    description: 'Relax in Bali\'s beautiful beaches, rice terraces, and spiritual temples in this tropical getaway perfect for couples and families.'
                },
                'europe-tour': {
                    title: 'European Highlights Tour',
                    location: 'Europe',
                    price: 'BDT 89999',
                    image: 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                    description: 'Explore the iconic cities of Paris, Rome, and Amsterdam in this comprehensive European tour covering the best of Western Europe.'
                },
                'malaysia-explorer': {
                    title: 'Malaysia Explorer',
                    location: 'Malaysia',
                    price: 'BDT 34999',
                    image: 'https://images.unsplash.com/photo-1596422846543-75e6d59b35e1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                    description: 'Discover the vibrant culture, stunning landscapes, and delicious cuisine of Malaysia with visits to Kuala Lumpur, Penang, and Langkawi.'
                },
                'singapore-family': {
                    title: 'Singapore Family Fun',
                    location: 'Singapore',
                    price: 'BDT 39999',
                    image: 'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                    description: 'Perfect for families with visits to Sentosa Island, Universal Studios, Gardens by the Bay, and other family-friendly attractions in Singapore.'
                }
            };

            // DOM Elements
            const packageModal = document.getElementById('packageModal');
            const closeModalBtn = document.getElementById('closeModal');
            const packageHero = document.getElementById('packageHero');
            const packageTitle = document.getElementById('packageTitle');
            const packageLocation = document.getElementById('packageLocation');
            const packagePrice = document.getElementById('packagePrice');
            const packageDescription = document.getElementById('packageDescription');
            const viewDetailsButtons = document.querySelectorAll('.view-details');
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');

            // Open package details modal
            viewDetailsButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const packageCard = button.closest('.package-card');
                    const packageId = packageCard.getAttribute('data-package');
                    const packageData = packages[packageId];

                    // Update modal content
                    packageTitle.textContent = packageData.title;
                    packageLocation.textContent = packageData.location;
                    packagePrice.textContent = packageData.price;
                    packageDescription.textContent = packageData.description;
                    packageHero.style.backgroundImage = `url('${packageData.image}')`;

                    // Update booking price
                    document.querySelector('.booking-price .price').textContent = packageData.price;

                    // Show modal
                    packageModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            });

            // Close modal
            closeModalBtn.addEventListener('click', () => {
                packageModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            });

            // Close modal when clicking outside
            packageModal.addEventListener('click', (e) => {
                if (e.target === packageModal) {
                    packageModal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });

            // Tab switching
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active class from all tabs and contents
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    // Add active class to clicked tab and corresponding content
                    tab.classList.add('active');
                    const tabId = tab.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });

            // Booking form submission
            document.querySelector('.booking-form').addEventListener('submit', (e) => {
                e.preventDefault();
                alert(
                    'Thank you for your booking! Our travel consultant will contact you shortly to confirm your reservation.'
                );
                packageModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            });

            // Mobile menu toggle
            document.querySelector('.mobile-menu').addEventListener('click', function() {
                const nav = document.querySelector('nav ul');
                nav.style.display = nav.style.display === 'flex' ? 'none' : 'flex';
            });

            // Newsletter form submission
            document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const email = this.querySelector('input[type="email"]').value;
                alert(`Thank you for subscribing with ${email}! You'll receive our latest travel updates soon.`);
                this.reset();
            });

            // Filter form submission
            document.querySelector('.filter-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const destination = document.getElementById('destination').value;
                const duration = document.getElementById('duration').value;
                const travelDate = document.getElementById('travel-date').value;
                const priceRange = document.getElementById('price-range').value;

                // In a real implementation, you would filter the packages based on these criteria
                alert(
                    `Filtering packages for destination: ${destination || 'All'}, duration: ${duration || 'Any'}, price range: ${priceRange || 'Any'}, travel date: ${travelDate || 'Any'}`
                );
            });
        </script>
    @endpush

</x-frontend.layouts.master>
