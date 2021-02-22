<div class="orderListModule">
    <div class="ordersListOption">
        <label>Orders List</label>
        <input id="searchOrder" type="search" name="searchOrder" oninput="ordersList()" placeholder="Search an order">
        <select id="orderSelect" onchange="ordersList()" class="select">
            <option value=-1 selected>All</option>
            <option value=0>Waiting for payment</option>
            <option value=1>In preparation</option>
            <option value=2>Finish</option>
        </select>
    </div>
    <div id="ordersList" class="ordersList">
        <?php ordersList(); ?>
    </div>

</div>
