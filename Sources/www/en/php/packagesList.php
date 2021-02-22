<div class="packageListModule">
    <div class="packagesListOption">
        <label>Packages List</label>
        <input id="searchPackage" type="search" name="searchPackage" oninput="packagesList()" placeholder="Search a package">
        <select id="packageSelect" onchange="packagesList()" class="select">
            <option value=-1 selected>All</option>
            <option value=0>In deposit</option>
            <option value=1>In delivery</option>
            <option value=2>Delivered</option>
        </select>
    </div>
    <div id="packagesList" class="packagesList">
        <?php packagesList(); ?>
    </div>

</div>
