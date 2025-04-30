<style>
    /*body { font-family: Arial; padding: 20px; }*/
    .hotel {     margin-bottom: 20px;
        box-sizing: border-box;
        width: 150px;
        display: inline-table;}
    .hotel h3 { margin: 0; }
</style>
<h1>Top Hotels in Luxembourg</h1>
<script>
    $(document).ready(function () {
        $('#recommended-hotels').html('Loading...');
        $.ajax({
            url: 'assets/php/api/get_hotels.php',
            method: 'GET',
            success: function (data) {
                console.log(data)
                const hotels = data//JSON.parse(data);

                let html = '';
                if (hotels.length > 0) {
                    hotels.forEach(function (hotel) {
                        html += `
                <div class="hotel">
                  <h3>${hotel.title}</h3>
                  <img src="${hotel.image}">
                  <p>Rating: ${hotel.rating} (${hotel.reviews} reviews)</p>
                  <p>Address: ${hotel.address}</p>
                </div>
              `;
                    });
                } else {
                    html = 'No hotels found.';
                }
                $('#recommended-hotels').html(html);
            },
            error: function () {
                $('#recommended-hotels').html('Failed to load hotel data.');
            }
        });
    })
</script>
<div id="recommended-hotels">

</div>
<div id="recommended-activity">

</div>
<div id="recommended-locations">

</div>
<?php
