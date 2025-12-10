<?php
/**
 * @var WorldCityModel $city
 */
?>
<h1>
    City: <?php echo e($city->getCityWithCountry()); ?> 
    <?php echo $city->getFlag(); ?>
</h1>
<div style="margin-bottom: 1rem;">
    <a href="edit.php?id=<?php echo $city->id; ?>" class="button" style="background: orange; padding: 5px 10px; text-decoration: none; color: black; border: 1px solid black;">
        ✏️ Edit this city
    </a>
</div>

<table>
    <tbody>
        <tr>
            <th>City name:</th>
            <td><?php echo e($city->city); ?></td>
        </tr>
        <tr>
            <th>City name (ascii):</th>
            <td><?php echo e($city->cityAscii); ?></td>
        </tr>
        <tr>
            <th>Country:</th>
            <td><?php echo e($city->country); ?></td>
        </tr>
        <tr>
            <th>Flag of the country:</th>
            <td><?php echo $city->getFlag(); ?></td>
        </tr>
        <tr>
            <th>ISO2 code of country:</th>
            <td><?php echo e($city->iso2); ?></td>
        </tr>
        <tr>
            <th>Population:</th>
            <td><?php echo e($city->population); ?></td>
        </tr>
    </tbody>
</table>