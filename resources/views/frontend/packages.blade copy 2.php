<x-frontend.layouts.master>
    @push('css')
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #fff;
                margin: 0;
            }

            :root {
                --primary: #1A2039;
                --secondary: #50BC81;
                --light: #f8f9fa;
                --gray: #6c757d;
            }

            .logo {
                font-weight: 700;
                font-size: 24px;
                color: var(--primary);
                text-decoration: none;
            }

            .logo span {
                color: var(--secondary);
            }

            /* PAGE TITLE */
            .page-header {
                background: linear-gradient(rgba(26, 32, 57, 0.8), rgba(26, 32, 57, 0.8)),
                    url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e');
                background-size: cover;
                background-position: center;
                padding: 100px 20px;
                text-align: center;
                color: #fff;
            }

            .page-header h1 {
                font-size: 42px;
                margin-bottom: 10px;
            }

            .page-header p {
                font-size: 16px;
                opacity: 0.9;
            }

            /* FILTER BAR */
            .filter-bar {
                max-width: 1200px;
                margin: 30px auto;
                padding: 20px;
                background: var(--light);
                border-radius: 8px;
                display: flex;
                gap: 20px;
            }

            .filter-bar select {
                width: 100%;
                padding: 12px;
                border: 1px solid #ddd;
                border-radius: 6px;
            }

            .filter-bar button {
                background: var(--secondary);
                color: #fff;
                border: none;
                padding: 12px 25px;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 600;
            }

            /* PACKAGE GRID */
            .packages-grid {
                max-width: 1200px;
                margin: 30px auto;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 30px;
                padding: 0 20px;
            }

            .package-card {
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
                background: #fff;
                transition: 0.3s;
            }

            .package-card:hover {
                transform: translateY(-5px);
            }

            .package-img {
                height: 200px;
                background-size: cover;
                background-position: center;
                position: relative;
            }

            .price-tag {
                position: absolute;
                top: 15px;
                right: 15px;
                background: var(--secondary);
                color: #fff;
                padding: 5px 12px;
                border-radius: 5px;
                font-weight: 600;
            }

            .package-body {
                padding: 20px;
            }

            .package-body h3 {
                font-size: 18px;
                margin-bottom: 10px;
                color: var(--primary);
            }

            .meta {
                display: flex;
                gap: 15px;
                font-size: 13px;
                margin-bottom: 12px;
                color: var(--gray);
            }

            .package-btn {
                display: inline-block;
                background: var(--primary);
                color: #fff;
                padding: 8px 15px;
                border-radius: 5px;
                text-decoration: none;
                font-size: 14px;
            }

            .package-btn:hover {
                background: var(--secondary);
            }
        </style>
    @endpush

    <!-- PAGE TITLE -->
    <section class="page-header">
        <h1>Travel Packages</h1>
        <p>Choose from our curated and affordable holiday packages worldwide</p>
    </section>

    <!-- FILTER BAR -->
    <div class="filter-bar">
        <select>
            <option>Choose Destination</option>
            <option>Thailand</option>
            <option>Dubai</option>
            <option>Bali</option>
            <option>Singapore</option>
        </select>

        <select>
            <option>Duration</option>
            <option>3 Days</option>
            <option>5 Days</option>
            <option>7 Days</option>
        </select>

        <button>Filter</button>
    </div>

    <!-- PACKAGES GRID -->
    <div class="packages-grid">

        <!-- 1 -->
        <div class="package-card">
            <div class="package-img"
                style="background-image:url('https://images.unsplash.com/photo-1552465011-b4e21bf6e79a');">
                <div class="price-tag">BDT 44,999</div>
            </div>
            <div class="package-body">
                <h3>Bangkok & Phuket Honeymoon</h3>
                <div class="meta">
                    <span><i class="fa fa-clock"></i> 6 Days</span>
                    <span><i class="fa fa-map-marker-alt"></i> Thailand</span>
                </div>
                <a href="#" class="package-btn">View Details</a>
            </div>
        </div>

        <!-- 2 -->
        <div class="package-card">
            <div class="package-img"
                style="background-image:url('https://images.unsplash.com/photo-1534008897995-27a23e859048');">
                <div class="price-tag">BDT 59,999</div>
            </div>
            <div class="package-body">
                <h3>Dubai Luxury Tour</h3>
                <div class="meta">
                    <span><i class="fa fa-clock"></i> 5 Days</span>
                    <span><i class="fa fa-map-marker-alt"></i> Dubai</span>
                </div>
                <a href="#" class="package-btn">View Details</a>
            </div>
        </div>

        <!-- 3 -->
        <div class="package-card">
            <div class="package-img"
                style="background-image:url('https://images.unsplash.com/photo-1528164344705-47542687000d');">
                <div class="price-tag">BDT 29,999</div>
            </div>
            <div class="package-body">
                <h3>Bali Tropical Escape</h3>
                <div class="meta">
                    <span><i class="fa fa-clock"></i> 6 Days</span>
                    <span><i class="fa fa-map-marker-alt"></i> Bali</span>
                </div>
                <a href="#" class="package-btn">View Details</a>
            </div>
        </div>

    </div>

</x-frontend.layouts.master>
