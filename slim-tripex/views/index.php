<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platba</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous">
    </script>

</head>
<body>
    <!--
    <p>Customer mail: <?=$customer_mail?></p>
    <p>Agent mail: <?=$agent_mail?></p>
    <p>Order amount: <?=$order_amount?></p>
    <p>Order number: <?=$order_number?></p>
    <p>Note: <?=$note?></p>
    <p>Currency: <?=$currency?></p>
    <p>Name and surname: <?=$name_surname?></p>
    <p>Company: <?=$company?></p>
    <p>Street: <?=$street?></p>
    <p>Street 2: <?=$street_2?></p>
    <p>Postal code: <?=$postal_code?></p>
    <p>City: <?=$city?></p>
    <p>Country: <?=$country?></p>
    -->



    <section class="container">
 
        <h3>Order Detail</h3>
        <form id="form1">
          <div class="row mb-3">
            <label for="customer_mail" class="col-sm-2 col-form-label" >Customer's  e-mail:</label>
  
            <div class="col-sm-10">
              <input type="text" name="customer_mail" class="form-control" placeholder="example@gmail.com" value="<?= $customer_mail ?>" readonly>
            </div>
          </div>
  
          <div class="row mb-3">
            <label for="agent_mail" class="col-sm-2 col-form-label">Agent's e-mail: </label>
  
            <div class="col-sm-4">
              <select class="form-select" id="agent_mail" name="agent_mail">
                 <?php if ($agent_mail == "info@tripex.sk"): ?> 
                    <option value="info@tripex.sk" selected="selected">info@tripex.sk (Slovak)</option>
                    <option value="info@sk.fcm.travel" disabled>info@sk.fcm.travel (Czech)</option>
                 <?php else: ?> 
                    <option value="info@tripex.sk" disabled>info@tripex.sk (Slovak)</option>
                    <option value="info@sk.fcm.travel" selected="selected">info@sk.fcm.travel (Czech)</option>
                 <?php endif; ?>  
                    

                
              </select>
            </div>
          </div>
  
          <div class="row mb-3">
            <label for="order_amount" class="col-sm-2 col-form-label">Order amount: </label>
            
            <div class="col-sm-10">
              <input type="text" name="order_amount" class="form-control" value="<?= $order_amount ?>" readonly>
            </div>
          </div>
  
          <div class="row mb-3">
            <label for="order_number" class="col-sm-2 col-form-label">Order Number: </label>
  
            <div class="col-sm-10">
              <input type="text" name="order_number" class="form-control" value="<?= $order_number ?>" readonly>
            </div>
          </div>
  
          <div class="row mb-3">
            <label for="note" class="col-sm-2 col-form-label">Note: </label>
  
            <div class="col-sm-10">
              <textarea type="text" name="note" class="form-control" readonly><?= $note ?></textarea>
            </div>
          </div>
  
          <div class="row mb-3">
            <label for="select_currency" class="col-sm-2 col-form-label">Currency: </label>
  
            <div class="col-sm-2">
  
              <select class="form-select" id="select_currency" name="currency">
                <?php if ($currency == "EUR"): ?>
                    <option value="EUR" selected="selected">EUR</option>
                    <option value="CZK" disabled>CZK</option>
                <?php else: ?>
                    <option value="EUR" disabled>EUR</option>
                    <option value="CZK" selected="selected">CZK</option>
                <?php endif; ?>  


              </select>
            </div>
          </div>

          <hr>


        <h3>Billing address</h3>
  
          <div class="row mb-3">
            <label for="name_surname" class="col-sm-2 col-form-label">Name and surname:</label>
  
            <div class="col-sm-10">
              <input type="text" name="name_surname" class="form-control" value="<?=$name_surname ?>" readonly>
            </div>
          </div>
  
          <div class="row mb-3">
            <label for="company" class="col-sm-2 col-form-label">Company:</label>
  
            <div class="col-sm-10">
              <input type="text" name="company" class="form-control" value="<?=$company ?>" readonly>
            </div>
          </div>
  
  
          <div class="row mb-3">
            <label for="street" class="col-sm-2 col-form-label">Street:</label>
  
            <div class="col-sm-10">
              <input type="text" name="street" class="form-control" value="<?= $street ?>" readonly>
            </div>
          </div>
  
          <div class="row mb-3">
            <label for="street_2" class="col-sm-2 col-form-label">Street 2:</label>
  
            <div class="col-sm-10">
              <input type="text" name="street_2" class="form-control" value="<?= $street_2 ?>" readonly>
            </div>
          </div>
  
          <div class="row mb-3">
            <label for="postal_code" class="col-sm-2 col-form-label">Postal code:</label>
  
            <div class="col-sm-10">
              <input type="text" name="postal_code" class="form-control" value="<?= $postal_code ?>" readonly>
            </div>
          </div>
  
          <div class="row mb-3">
            <label for="city" class="col-sm-2 col-form-label">City:</label>
  
            <div class="col-sm-10">
              <input type="text" name="city" class="form-control" value=" <?= $city ?>" readonly>
            </div>
          </div>
  
        <div class="row mb-3">
          <label for="select_country" class="col-sm-2 col-form-label">Country: </label>
  
          <div class="col-sm-3">
  
            <select class="form-select" id="select_country" name="country">
              <?php if ($country == "Slovak"): ?>
                <option value="Slovak" selected="seleted">Slovak Republic</option>
                <option value="Czech" disabled>Czech Republic</option>
              <?php else: ?>
                <option value="Slovak" disabled>Slovak Republic</option>
                <option value="Czech" selected="selected">Czech Republic</option>
              <?php endif; ?>   

            </select>
          </div>
        </div>
  
        </form>
  
  
  
        <hr>
        <div class="buttons">
          <button type="submit" class="btn btn-dark" id="pay" onclick="javascript:window.location.href='<?=$cardpay_url?>'" form="form2">CONFIRM</button>
        </div>

  
  
      </section>
</body>
</html>
