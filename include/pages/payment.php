<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    const itinerary = JSON.parse(<?php echo $_SESSION["Itinerary"]; ?>)

    $(document).ready(function () {


        $.ajax({
            url: "assets/php/index.php",
            method: "POST",
            data: {
                Type: "Itinerary",
                Value: {
                    fk_user_created": 1
                }
            },
            success: function (response){
                let itinerary_id = response['itinerary_id'];

                let stops = function (itinerary_id){

                    return
                }

                stops.each(function () {
                    $.ajax({
                        url: "assets/php/index.php",
                        method: "POST",
                        data: {
                            Type: "Itinerary_Stop",
                            Value: {
                                fk_itinerary_includes: itinerary_id,
                                type: "hotel",
                                value: "Grand Hotel",
                                booking_ref: "HOTEL12345",
                                link: "https://example.com/booking/HOTEL12345",
                                start: "2023-12-15 14:00:00",
                                stop: "2023-12-20 11:00:00"
                            }
                        }
                    })
                })

            }
        })


    })
</script>