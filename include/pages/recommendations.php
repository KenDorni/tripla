<link rel="stylesheet" href="assets/css/recommendations.css">

<div class="container">
    <h1>Top Hotels in Luxembourg</h1>
    
    <div id="recommended-hotels">
        <!-- Hotels will be loaded here -->
    </div>
    
    <h1>Recommended Activities</h1>
    <div id="recommended-activity">
        <!-- Activities will be loaded here -->
    </div>
    
    <h1>Popular Locations</h1>
    <div id="recommended-locations">
        <!-- Locations will be loaded here -->
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#recommended-hotels').html('<div class="loading">Loading...</div>');
        
        $.ajax({
            url: 'assets/php/api/get_hotels.php',
            method: 'GET',
            success: function (data) {
                const hotels = data;
                
                let html = '';
                if (hotels && hotels.length > 0) {
                    hotels.forEach(function (hotel) {
                        html += `
                        <div class="hotel">
                            <img src="${hotel.image || 'assets/images/hotel-placeholder.jpg'}" alt="${hotel.title}">
                            <div class="hotel-content">
                                <h3>${hotel.title}</h3>
                                <div class="rating">
                                    <span class="rating-value">${hotel.rating}</span>
                                    <span class="reviews">${hotel.reviews} reviews</span>
                                </div>
                                <div class="address">${hotel.address}</div>
                            </div>
                        </div>`;
                    });
                } else {
                    html = '<div class="no-data">No hotels found.</div>';
                }
                
                $('#recommended-hotels').html(html);
            },
            error: function () {
                $('#recommended-hotels').html('<div class="error">Failed to load hotel data.</div>');
            }
        });
        
        // You can add similar AJAX calls for activities and locations
    });
</script>