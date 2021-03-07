<div class="form">
    <div class="formTab">
        <label id="customerTab" class="labelLink" for="customerForm" onclick="triggerUserTabCss('true')">Customers</label>
        <label id="deliveryTab" class="labelLink" for="deliveryForm" onclick="triggerUserTabCss('false')">Delivery Man</label>
    </div>

    <input id="customerForm" type="radio" name="userForm" checked>
    <div class="customers">
        <input id="customerSignInForm" type="radio" name="customerForm" checked>
        <form class="customerSignInForm" action="./../../config/config.php" method="post">
            <h3>Customer Connexion</h3>

            <input class="input" type="text" name="email"  placeholder="Email">
            <input class="input" type="password" name="password" placeholder="Password">

            <input type="hidden" name="formType" value="signIn">
            <input class="submit" type="submit" value="Send">

            <hr>

            <label>Don't have an account? <label class="labelLink" for="customerSignUpForm">Sign Up</label>.</label>
        </form>

        <input id="customerSignUpForm" type="radio" name="customerForm">
        <form class="customerSignUpForm" action="./../../config/config.php" method="post">
            <h3>Customer Inscription</h3>

            <input class="input" type="text" name="email" placeholder="Email">
            <input class="input" type="password" name="password" placeholder="Password">
            <input class="input" type="password" name="confirmPassword" placeholder="Confirm your password">
            <input class="input" type="text" name="companyName"  placeholder="Company Name">
            <input class="input" type="text" name="address" placeholder="Address">
            <input class="input" type="text" name="numTel" placeholder="Tel number">

            <input type="hidden" name="formType" value="signUpCustomers">
            <input class="submit" type="submit" value="Send">

            <hr>

            <label>Already have an account? <label class="labelLink" for="customerSignInForm">Log In</label>.</label>
        </form>
    </div>

    <input id="deliveryForm" type="radio" name="userForm">
    <div class="delivery">
        <input id="deliverySignInForm" type="radio" name="deliveryForm" checked>
        <form class="deliverySignInForm" action="./../../config/config.php" method="post">
            <h3>Delivery Connexion</h3>

            <input class="input" type="text" name="email"  placeholder="Email">
            <input class="input" type="password" name="password" placeholder="Password">

            <input type="hidden" name="formType" value="signIn">
            <input class="submit" type="submit" value="Send">

            <hr>

            <label>Don't have an account? <label class="labelLink" for="deliverySignUpForm">Sign Up</label>.</label>
        </form>

        <input id="deliverySignUpForm" type="radio" name="deliveryForm">
        <form class="deliverySignUpForm" action="./../../config/config.php" method="post">
            <h3>Delivery Inscription</h3>

            <input class="input" type="text" name="email"  placeholder="Email">
            <input class="input" type="password" name="password" placeholder="Password">
            <input class="input" type="password" name="confirmPassword" placeholder="Confirm your password">
            <input class="input" type="text" name="firstName" placeholder="First Name">
            <input class="input" type="text" name="name" placeholder="Last name">
            <input class="input" type="text" name="numTel" placeholder="Tel Number">

            <input type="hidden" name="formType" value="signUpDelivery">
            <input class="submit" type="submit" value="Send">

            <hr>

            <label>Already have an account? <label class="labelLink" for="deliverySignInForm">Log In</label>.</label>
        </form>
    </div>
</div>

<script type="text/javascript">
    triggerUserTabCss("true");

    function triggerUserTabCss(trigger)
    {
        let labelCustomer = document.getElementById("customerTab");
        let labelDelivery = document.getElementById("deliveryTab");
        let inputCustomer = document.getElementById("customerForm");
        let inputDelivery = document.getElementById("deliveryForm");


        if (trigger == "true")
        {
            labelCustomer.style.backgroundColor = "#303952";
            labelCustomer.style.cursor = "default";
            labelDelivery.style.backgroundColor = "#718093";
            labelDelivery.style.cursor = "pointer";
        }
        else
        {
            labelCustomer.style.backgroundColor = "#718093";
            labelCustomer.style.cursor = "pointer";
            labelDelivery.style.backgroundColor = "#303952";
            labelDelivery.style.cursor = "default";
        }
    }
</script>
