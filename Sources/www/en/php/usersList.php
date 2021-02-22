<div class="usersListModule">
    <div class="usersListOption">
        <label>Users List</label>
        <input id="searchUser" type="search" name="searchUser" oninput="usersList()" placeholder="Search an user">
        <select id="userSelect" onchange="usersList()" class="select">
            <option value=-1 selected>All</option>
            <option value=0>Customers</option>
            <option value=1>Delivery Man</option>
        </select>
    </div>
    <div id="usersList" class="usersList">
        <?php usersList(); ?>
    </div>
</div>
