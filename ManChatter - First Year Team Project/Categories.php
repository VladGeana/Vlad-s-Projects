<style>

#theCats {
  float: right;
  width: 10%;
  margin-right: 20%;
}

</style>


<div id="theCats">
<h2>Categories</h2>
<!-- the links are placeholders for the proper pages -->
<a id="0" href="HomePage.php">All</a><br>
<br><a id="1" href="HomePage.php?cat=1">Finances</a><br>
<br><a id="2" href="HomePage.php?cat=2">Accommodation</a><br>
<br><a id="3" href="HomePage.php?cat=3">Applying</a><br>
<br><a id="4" href="HomePage.php?cat=4">Courses</a><br>
<br><a id="5" href="HomePage.php?cat=5">Food</a><br>
<br><a id="6" href="HomePage.php?cat=6">Shopping</a><br>
<br><a id="7" href="HomePage.php?cat=7">Nightlife</a><br>
<br><a id="8" href="HomePage.php?cat=8">Other</a><br>
</div>

<script>
  var c = "<?php Print($categoryNumber); ?>"
  document.getElementById(c).style.backgroundColor = "#d6ccdd";
</script>