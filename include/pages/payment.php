<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<h1>Payment Placeholder</h1>
<button type="submit" id="payandsave">Pay & Save</button>
<script>
    const itinerary = JSON.parse(<?php echo $_SESSION["Itinerary"]; ?>)

    $(document).ready(function () {
        $("payandsave").click(function (){
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
                    let itinerary_id = response['itinerary_id']
                    itinerary.each(function (item) {
                        $.ajax({
                            url: "assets/php/index.php",
                            method: "POST",
                            data: item
                        }
                    })
                })

        }
        })
        })
    })

    async function sendItineraryToAPI(itineraryArray) {
        for (const item of itineraryArray) {
            try {
                const response = await fetch('index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(item)
                });

                const result = await response.json();

                if (!response.ok) {
                    console.error('Failed to send:', item, 'Response:', result);
                } else {
                    console.log('Successfully sent:', item, 'Response:', result);
                }
            } catch (error) {
                console.error('Error sending item:', item, error);
            }
        }
    }

</script>
<?php
echo $_SESSION["Itinerary"];