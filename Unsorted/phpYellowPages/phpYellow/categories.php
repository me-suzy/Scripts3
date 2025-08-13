<!-- START of categories.php -->

<SELECT NAME="category" class="input">
<?php if($goal != "Add Category"):?> 
	<option value="<?php echo $category;?>" SELECTED><?php echo $category;?></option>
	<option value="*">All Categories</option>
    <option value="*">- - - - - - - - - - - - - -</option>
    <option value="*">It does not matter</option>
    <option value="*">- - - - - - - - - - - - - -</option>
<?php endif;?>
<!-- Add, remove or change any of the select option items below. -->
<option value="Test">Test</option>
<option value="Aerospace">Aerospace</option>
<option value="Association">Association</option>
<option value="Automotive">Automotive</option>
<option value="Banking">Banking</option>
<option value="Chemicals">Chemicals</option>
<option value="Computer Hardware">Computer Hardware</option>
<option value="Computer Software">Computer Software</option>
<option value="Conglomerates">Conglomerates</option>
<option value="Consulting">Consulting</option>
<option value="Consumer">Consumer</option>
<option value="Defense">Defense</option>
<option value="Diversified Services">Diversified Services</option>
<option value="Drugs">Drugs</option>
<option value="Education">Education</option>
<option value="Electronics">Electronics</option>
<option value="Energy">Energy</option>
<option value="Financial Services">Financial Services</option>
<option value="Food-Beverage">Food-Beverage</option>
<option value="Government">Government</option>
<option value="Health Products">Health Products</option>
<option value="Health Services">Health Services</option>
<option value="Import-Export">Import-Export</option>
<option value="Insurance">Insurance</option>
<option value="Internet-Products">Internet-Products</option>
<option value="Internet-Services">Internet-Services</option>
<option value="International Trade">International Trade</option>
<option value="Law">Law</option>
<option value="Leisure">Leisure</option>
<option value="Manufacturing">Manufacturing</option>
<option value="Marketing">Marketing</option>
<option value="Materials-Construction">Materials-Construction</option>
<option value="Media">Media</option>
<option value="Metals-Mining">Metals-Mining</option>
<option value="Real Estate">Real Estate</option>
<option value="Retail">Retail</option>
<option value="Specialty Retail">Specialty Retail</option>
<option value="Telecommunications">Telecommunications</option>
<option value="Transportation">Transportation</option>
<option value="Utilities">Utilities</option>
</SELECT>
<!-- END of categoriesINDUSTRY.php -->