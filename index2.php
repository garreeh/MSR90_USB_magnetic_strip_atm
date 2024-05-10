<!-- JAVASCRIPT CONNECTED VIA CLASS -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Magnetic Stripe Reader</title>
</head>
<body>
  <form id="magneticForm">
    <div class="form-group">
      <label for="magneticData">Click here and Swipe Card:</label><br>
      <input class="form-control magneticData" type="text" id="" name="magneticData" placeholder="Click and Swipe here"><br>
      <button style="display: none;" id="parseButton">Parse Magnetic Data</button> <!-- Button to parse magnetic stripe data -->
    </div>
  </form>

  <div class="form-group">
    <label for="firstname">Bank</label>
    <input class="form-control" type="text" name="card_payment_reference1">
  </div>

  <div class="form-group">
    <label for="firstname">Name</label>
    <input class="form-control nameInput" type="text" id="" placeholder="Name"><br>
  </div>

  <div class="form-group">
    <label for="firstname">Card Type</label>
    <input class="form-control cardTypeInput" type="text" id="" name="card_payment_reference2" placeholder="Card Type"><br>
  </div>

  <div class="form-group">
    <label for="firstname">Card Number</label>
    <input class="form-control cardNumberInput" type="text" id="" name="card_payment_reference3" placeholder="Card Number"><br>
  </div>

  <div class="form-group">
    <label for="firstname">Expiry Date</label>
    <input class="form-control expiryDateInput" type="text" id="" placeholder="Expiry Date"><br>
  </div>

  <div class="form-group">
    <label for="firstname">Service Code</label>
    <input class="form-control serviceCodeInput" type="text" id="" placeholder="Service Code"><br>
  </div>

</body>
</html>

<script>
  function parseMagneticStripe() {
    var magneticData = document.querySelector('.magneticData').value;

    var track1Pattern = /%B(.*?)\?/;
    var track2Pattern = /;(.*?)\?/;

    var track1Match = magneticData.match(track1Pattern);
    var track2Match = magneticData.match(track2Pattern);

    if (track1Match && track2Match) {
      var track1Data = track1Match[1];
      var track2Data = track2Match[1];

      var track1Fields = track1Data.split('^');
      var track2Fields = track2Data.split(';')[0].split('='); // Splitting correctly

      var name = track1Fields[1].trim();
      var cardNumber = track2Fields[0].substring(0, 16); // Extract the first 16 digits as the card number
      var expiryData = track2Fields[1].substring(0, 4); // Extract the next 4 digits as the expiry date (YYMM format)
      var serviceCode = track2Fields[0].substring(22); // Extract the service code

      var cardType = ""; // Variable to store the card type

      // Check the card number prefix to determine the card type
      if (cardNumber.startsWith('4')) {
        cardType = "Visa";
      } else if (cardNumber.startsWith('5')) {
        var firstTwoDigits = parseInt(cardNumber.substring(0, 2));
        if (firstTwoDigits >= 51 && firstTwoDigits <= 55) {
          cardType = "Master Card";
        } else if (firstTwoDigits == 62) {
          cardType = "Union Pay";
        }
      } else if (cardNumber.startsWith('3')) {
        var firstTwoDigits = parseInt(cardNumber.substring(0, 2));
        if (firstTwoDigits == 34 || firstTwoDigits == 37) {
          cardType = "American Express";
        }
      }


      // Format card number
      var formattedCardNumber = "";
      for (var i = 0; i < cardNumber.length - 4; i++) {
        if (i > 0 && i % 4 === 0) {
          formattedCardNumber += "-";
        }
        formattedCardNumber += "*";
      }
      formattedCardNumber += "-" + cardNumber.substring(cardNumber.length - 4);

      // Extract year and month from the expiry data
      var expiryYear = expiryData.substring(0, 2);
      var expiryMonth = expiryData.substring(2, 4);

      // Populate input fields with parsed data
      document.querySelector('.nameInput').value = name;
      document.querySelector('.cardNumberInput').value = formattedCardNumber;
      document.querySelector('.expiryDateInput').value = expiryMonth + "/" + expiryYear;
      document.querySelector('.serviceCodeInput').value = serviceCode;
      document.querySelector('.cardTypeInput').value = cardType;

      document.querySelector('.magneticData').value = "";
    } else {
      // Display error message if parsing fails
      alert("Error parsing magnetic stripe data. Please check the input format.");
      document.querySelector('.magneticData').value = "";
    }
  }

  // Event listener for button click (to parse magnetic stripe data)
  document.getElementById('parseButton').addEventListener('click', function(event) {
    // Prevent default form submission behavior
    event.preventDefault();

    // Parse magnetic stripe data
    parseMagneticStripe();
  });


</script>
